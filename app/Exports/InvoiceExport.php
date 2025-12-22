<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Nomor Invoice',
            'Pelanggan',
            'Tipe',
            'Jatuh Tempo',
            'Total Biaya',
            'Mata Uang',
            'Status',
            'Metode Pembayaran',
            'Tanggal Bayar',
        ];
    }

    /** @param Invoice $invoice */
    public function map($invoice): array
    {
        $pelanggan = $invoice->pelanggan;
        $pelangganNama = $pelanggan
            ? ($pelanggan->nama_lengkap ?? $pelanggan->nama_perusahaan ?? 'N/A')
            : 'N/A';

        return [
            $invoice->nomor_invoice,
            $pelangganNama,
            $invoice->tipe,
            optional($invoice->jatuh_tempo)?->format('d-m-Y') ?? '-',
            (string) $invoice->total_biaya,
            $invoice->mata_uang ?? 'IDR',
            ucfirst((string) $invoice->status),
            $invoice->metode_pembayaran ?? '',
            optional($invoice->tanggal_bayar)?->format('d-m-Y') ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
