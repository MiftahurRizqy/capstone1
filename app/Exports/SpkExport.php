<?php

namespace App\Exports;

use App\Models\Spk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpkExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Nomor SPK',
            'Pelanggan',
            'Layanan',
            'Tipe SPK',
            'Status',
            'Rencana Pengerjaan',
        ];
    }

    /** @param Spk $spk */
    public function map($spk): array
    {
        $pelanggan = $spk->pelanggan;
        $pelangganNama = $pelanggan
            ? (($pelanggan->tipe === 'personal') ? ($pelanggan->nama_lengkap ?? '-') : ($pelanggan->nama_perusahaan ?? '-'))
            : '-';

        return [
            $spk->nomor_spk,
            $pelangganNama,
            optional($spk->layananInduk)->nama_layanan_induk ?? '-',
            $spk->tipe,
            ucfirst(str_replace('_', ' ', (string) $spk->status)),
            optional($spk->rencana_pengerjaan)?->format('d-m-Y') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
