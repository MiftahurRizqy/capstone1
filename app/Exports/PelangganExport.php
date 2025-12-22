<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelangganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(private Collection $rows)
    {
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Nomor Pelanggan',
            'Member Card',
            'Kategori',
            'Nama',
            'No. HP',
            'Alamat',
            'POP',
            'Jenis Layanan',
        ];
    }

    /** @param Pelanggan $pelanggan */
    public function map($pelanggan): array
    {
        $displayName = $pelanggan->nama_lengkap
            ?? $pelanggan->nama_perusahaan
            ?? $pelanggan->nama_kontak
            ?? '-';

        $jenisLayanan = $pelanggan->layanan->isNotEmpty()
            ? (optional($pelanggan->layanan->first()->layananEntry)->nama_paket ?? 'N/A')
            : 'Belum ada';

        return [
            $pelanggan->nomor_pelanggan,
            $pelanggan->member_card,
            optional($pelanggan->kategori)->nama ?? 'N/A',
            $displayName,
            $pelanggan->no_hp,
            $pelanggan->alamat,
            optional($pelanggan->pop)->nama_pop ?? 'N/A',
            $jenisLayanan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
