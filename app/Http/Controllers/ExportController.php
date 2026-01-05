<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Keluhan;
use App\Models\LayananEntry;
use App\Models\Pelanggan;
use App\Models\Spk;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller

{
    private function downloadXlsx(string $filename, array $headings, iterable $rows): StreamedResponse
    {
        if (!class_exists(Spreadsheet::class)) {
            abort(500, 'Library export XLSX belum terpasang. Jalankan: composer require phpoffice/phpspreadsheet (pastikan ext-zip aktif).');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($headings, null, 'A1');

        $rowIndex = 2;
        foreach ($rows as $row) {
            $sheet->fromArray($row, null, 'A' . $rowIndex);
            $rowIndex++;
        }

        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $highestColumn . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->freezePane('A2');

        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function keluhan(Request $request)
    {
        $query = Keluhan::with(['pelanggan', 'layananInduk', 'spk'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                    ->orWhere('keluhan1', 'like', "%{$search}%")
                    ->orWhere('keluhan2', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($subq) use ($search) {
                        $subq->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nomor_pelanggan', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        $rows = $query->get();

        $dataRows = $rows->map(function ($row) {
            $namaPelanggan = $row->pelanggan?->nama_lengkap ?: $row->pelanggan?->nama_perusahaan ?? '-';
            return [
                $row->created_at?->format('Y-m-d H:i:s'),
                $row->pelanggan?->nomor_pelanggan,
                $namaPelanggan,
                $row->layananInduk?->nama_layanan_induk,
                $row->prioritas,
                $row->spk?->status ?? 'Open',
                $row->deskripsi,
                $row->keluhan1,
                $row->keluhan2,
            ];
        });

        return $this->downloadXlsx('keluhan.xlsx', [
            'Tanggal',
            'No Pelanggan',
            'Nama Pelanggan',
            'Layanan Induk',
            'Prioritas',
            'Status',
            'Deskripsi',
            'Keluhan 1',
            'Keluhan 2',
        ], $dataRows);
    }

    public function pelanggan(Request $request)
    {
        $query = Pelanggan::with(['pop', 'layanan.layananEntry', 'kategori'])->latest();

        if ($request->filled('kategori_pelanggan_id')) {
            $query->where('kategori_pelanggan_id', $request->kategori_pelanggan_id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pelanggan', 'like', "%{$search}%")
                    ->orWhere('member_card', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                    ->orWhere('nama_kontak', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $rows = $query->get();

        $dataRows = $rows->map(function ($row) {
            $layananNames = $row->layanan->map(function ($l) {
                return $l->layananEntry?->nama_paket;
            })->filter()->unique()->join(', ');

            $status = 'Belum Ada Layanan';
            $firstLayanan = $row->layanan->first();
            
            if ($firstLayanan) {
                $today = Carbon::today();
                $mulai = $firstLayanan->mulai_kontrak ? Carbon::parse($firstLayanan->mulai_kontrak) : null;
                $selesai = $firstLayanan->selesai_kontrak ? Carbon::parse($firstLayanan->selesai_kontrak) : null;

                if ($mulai && $selesai) {
                    if ($today->lt($mulai)) {
                        $status = 'Belum Mulai';
                    } elseif ($today->gt($selesai)) {
                        $status = 'Berakhir';
                    } else {
                        $status = 'Aktif';
                    }
                } else {
                    $status = 'Ada Layanan';
                }
            }

            return [
                $row->nomor_pelanggan,
                $row->member_card,
                $row->nama_lengkap,
                $row->nama_perusahaan,
                $row->nama_kontak,
                $row->no_hp,
                $row->kategori?->nama,
                $row->pop?->nama,
                $layananNames,
                $status,
            ];
        });

        return $this->downloadXlsx('pelanggan.xlsx', [
            'No Pelanggan',
            'Member Card',
            'Nama Lengkap',
            'Nama Perusahaan',
            'Nama Kontak',
            'No HP',
            'Kategori',
            'POP',
            'Paket Layanan',
            'Status',
        ], $dataRows);
    }

    public function wilayahBagian(Request $request)
    {
        $query = Wilayah::bagian()->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('provinsi_nama', 'like', "%{$search}%")
                    ->orWhere('kabupaten_nama', 'like', "%{$search}%")
                    ->orWhere('kecamatan_nama', 'like', "%{$search}%")
                    ->orWhere('kelurahan_nama', 'like', "%{$search}%");
            });
        }

        $rows = $query->get();

        $dataRows = $rows->map(function ($row) {
            return [
                $row->provinsi_nama,
                $row->kabupaten_nama,
                $row->kecamatan_nama,
                $row->kelurahan_nama,
                $row->nama,
            ];
        });

        return $this->downloadXlsx('wilayah_bagian.xlsx', [
            'Provinsi',
            'Kabupaten/Kota',
            'Kecamatan',
            'Kelurahan/Desa',
            'Bagian',
        ], $dataRows);
    }

    public function layananEntry(Request $request)
    {
        $query = LayananEntry::with('layananInduk')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama_paket', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('kelompok_layanan')) {
            $query->where('kelompok_layanan', $request->kelompok_layanan);
        }

        if ($request->filled('layanan_induk_id')) {
            $query->where('layanan_induk_id', $request->layanan_induk_id);
        }

        $rows = $query->get();

        $dataRows = $rows->map(function ($row) {
            return [
                $row->kode,
                $row->nama_paket,
                $row->status,
                $row->tipe,
                $row->kelompok_layanan,
                $row->layananInduk?->nama_layanan_induk,
            ];
        });

        return $this->downloadXlsx('layanan_entry.xlsx', [
            'Kode',
            'Nama Paket',
            'Status',
            'Tipe',
            'Kelompok Layanan',
            'Layanan Induk',
        ], $dataRows);
    }

    public function spk(Request $request)
    {
        $query = Spk::with(['keluhan.pelanggan', 'layananInduk', 'pelanggan'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_spk', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('keluhan.pelanggan', function ($subq) use ($search) {
                        $subq->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nomor_pelanggan', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get();

        $dataRows = $rows->map(function ($row) {
            $namaPelanggan = $row->keluhan?->pelanggan?->nama_lengkap ?: $row->keluhan?->pelanggan?->nama_perusahaan ?? '-';
            return [
                $row->nomor_spk,
                $row->keluhan?->pelanggan?->nomor_pelanggan,
                $namaPelanggan,
                $row->layananInduk?->nama_layanan_induk,
                $row->status,
                $row->keterangan,
                $row->created_at?->format('Y-m-d H:i:s'),
            ];
        });

        return $this->downloadXlsx('spk.xlsx', [
            'Nomor SPK',
            'No Pelanggan',
            'Nama Pelanggan',
            'Layanan Induk',
            'Status',
            'Keterangan',
            'Tanggal',
        ], $dataRows);
    }

    public function invoice(Request $request)
    {
        $query = Invoice::with(['pelanggan', 'layanan.layananEntry'])->latest();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomor_invoice', 'like', "%{$searchTerm}%")
                    ->orWhereHas('pelanggan', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('nama_lengkap', 'like', "%{$searchTerm}%")
                            ->orWhere('nomor_pelanggan', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $rows = $query->get();

        $dataRows = $rows->map(function ($row) {
            $namaPelanggan = $row->pelanggan?->nama_lengkap
                ?? $row->pelanggan?->nama_perusahaan
                ?? 'N/A';

            return [
                $row->nomor_invoice,
                $row->pelanggan?->nomor_pelanggan,
                $namaPelanggan,
                $row->layanan?->layananEntry?->nama_paket,
                $row->tipe,
                $row->status,
                $row->total_biaya,
                $row->mata_uang,
                $row->jatuh_tempo,
                $row->tanggal_bayar,
                $row->metode_pembayaran,
                $row->keterangan,
            ];
        });

        return $this->downloadXlsx('invoice.xlsx', [
            'Nomor Invoice',
            'No Pelanggan',
            'Nama Pelanggan',
            'Paket Layanan',
            'Tipe',
            'Status',
            'Total Biaya',
            'Mata Uang',
            'Jatuh Tempo',
            'Tanggal Bayar',
            'Metode Pembayaran',
            'Keterangan',
        ], $dataRows);
    }
}
