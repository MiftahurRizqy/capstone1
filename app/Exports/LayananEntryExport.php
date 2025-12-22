<?php

namespace App\Exports;

use App\Models\LayananEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LayananEntryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Kode',
            'Nama Paket',
            'Status',
            'Tipe',
            'Kelompok Layanan',
            'Layanan Induk',
        ];
    }

    /** @param LayananEntry $entry */
    public function map($entry): array
    {
        return [
            $entry->kode,
            $entry->nama_paket,
            ucfirst((string) $entry->status),
            $entry->tipe,
            $entry->kelompok_layanan,
            optional($entry->layananInduk)->nama_layanan_induk ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
