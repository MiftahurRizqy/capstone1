@extends('backend.layouts.app')

@section('title', 'Detail Invoice')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Invoice: {{ $invoice->nomor_invoice }}</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.invoice.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg shadow dark:bg-gray-500 dark:hover:bg-gray-600 transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('admin.invoice.print', $invoice->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow dark:bg-green-500 dark:hover:bg-green-600 transition-all duration-200">
                    <i class="fas fa-print"></i>
                    <span>Cetak Invoice</span>
                </a>
            </div>
        </div>

        {{-- Main content card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-gray-700 dark:text-gray-300">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-l-4 border-blue-500 pl-3">Informasi Invoice</h2>
                    <p><strong>Nomor Invoice:</strong> <span class="font-medium">{{ $invoice->nomor_invoice }}</span></p>
                    <p><strong>Tipe:</strong> <span class="font-medium">{{ $invoice->tipe }}</span></p>
                    <p><strong>Jatuh Tempo:</strong> <span class="font-medium">{{ $invoice->jatuh_tempo->format('d M Y') }}</span></p>
                    <p><strong>Status:</strong> <span class="font-medium">{{ ucfirst($invoice->status) }}</span></p>
                    <p><strong>Total Biaya:</strong> <span class="font-medium">{{ number_format($invoice->total_biaya, 2, ',', '.') }}</span></p>
                    <p><strong>Tanggal Bayar:</strong> <span class="font-medium">{{ $invoice->tanggal_bayar ? $invoice->tanggal_bayar->format('d M Y') : '-' }}</span></p>
                    <p><strong>Metode Pembayaran:</strong> <span class="font-medium">{{ $invoice->metode_pembayaran ?? '-' }}</span></p>
                    <p><strong>Keterangan:</strong> <span class="font-medium">{{ $invoice->keterangan ?? '-' }}</span></p>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-l-4 border-blue-500 pl-3">Informasi Pelanggan</h2>
                    <p><strong>Nama Pelanggan:</strong> <span class="font-medium">{{ $invoice->pelanggan->nama_lengkap ?? $invoice->pelanggan->nama_perusahaan ?? 'N/A' }}</span></p>
                    <p><strong>Nomor Pelanggan:</strong> <span class="font-medium">{{ $invoice->pelanggan->nomor_pelanggan ?? 'N/A' }}</span></p>
                    <p><strong>Layanan:</strong> <span class="font-medium">{{ $invoice->layanan->layananEntry->nama_paket ?? 'N/A' }}</span></p>
                    <p><strong>Homepass:</strong> <span class="font-medium">{{ $invoice->layanan->homepass ?? 'N/A' }}</span></p>
                    <p><strong>No. HP:</strong> <span class="font-medium">{{ $invoice->pelanggan->no_hp ?? 'N/A' }}</span></p>
                    <p><strong>Alamat:</strong> <span class="font-medium">{{ $invoice->pelanggan->alamat ?? 'N/A' }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
