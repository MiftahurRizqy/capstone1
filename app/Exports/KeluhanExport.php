<?php

namespace App\Exports;

use App\Models\Keluhan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KeluhanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'ID',
            'Layanan',
            'Pelanggan',
            'Buat SPK?',
            'Tujuan',
            'Prioritas',
            'Keluhan 1',
            'Keluhan 2',
            'Via',
            'Tanggal Input',
        ];
    }

    /** @param Keluhan $keluhan */
    public function map($keluhan): array
    {
        $pelanggan = $keluhan->pelanggan;
        $pelangganNama = $pelanggan
            ? (($pelanggan->tipe === 'personal') ? ($pelanggan->nama_lengkap ?? '-') : ($pelanggan->nama_perusahaan ?? '-'))
            : '-';

        return [
            $keluhan->id_keluhan,
            optional($keluhan->layananInduk)->nama_layanan_induk ?? '-',
            $pelangganNama,
            $keluhan->jenis_spk,
            $keluhan->tujuan,
            ucfirst((string) $keluhan->prioritas),
            $keluhan->keluhan1,
            $keluhan->keluhan2,
            $keluhan->via,
            optional($keluhan->tanggal_input)?->format('d-m-Y H:i') ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
