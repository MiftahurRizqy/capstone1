@extends('backend.layouts.app')

@section('title', 'Detail SPK')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail SPK #{{ $spk->nomor_spk }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.spk.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <a href="{{ route('admin.spk.print', urlencode($spk->nomor_spk)) }}" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-print"></i>
                    Cetak SPK
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Informasi SPK --}}
            <div class="space-y-4">
                <h3 class="font-bold text-lg dark:text-white/90">Informasi SPK</h3>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nomor SPK</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->nomor_spk }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Layanan Induk</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->layananInduk->nama_layanan_induk ?? '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tipe</p>
                    <p class="font-medium dark:text-white/90">{{ ucfirst($spk->tipe) }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    {{-- PERBAIKAN: Memformat status agar lebih mudah dibaca --}}
                    <p class="font-medium dark:text-white/90">{{ ucfirst(str_replace('_', ' ', $spk->status)) }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Rencana Pengerjaan</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->rencana_pengerjaan ? $spk->rencana_pengerjaan->format('d-m-Y') : '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelengkapan Kerja</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->kelengkapan_kerja ?? '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Keterangan</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->keterangan ?? '-' }}</p>
                </div>
            </div>

            {{-- Informasi Pelanggan dan Pelaksana --}}
            <div class="space-y-4">
                <h3 class="font-bold text-lg dark:text-white/90">Informasi Pelanggan & Pelaksana</h3>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Pelanggan</p>
                    <p class="font-medium dark:text-white/90">
                        @if ($spk->keluhan->pelanggan->tipe == 'personal')
                            {{ $spk->keluhan->pelanggan->nama_lengkap ?? '-' }}
                        @else
                            {{ $spk->keluhan->pelanggan->nama_perusahaan ?? '-' }}
                        @endif
                    </p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nomor Pelanggan</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->keluhan->pelanggan->nomor_pelanggan ?? '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->keluhan->pelanggan->alamat ?? '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pelaksana 1</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->pelaksana_1 ?? '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pelaksana 2</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->pelaksana_2 ?? '-' }}</p>
                </div>
                <div class="border-b pb-2 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Koordinator</p>
                    <p class="font-medium dark:text-white/90">{{ $spk->koordinator ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
