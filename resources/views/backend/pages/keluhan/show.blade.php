@extends('backend.layouts.app')

@section('title', 'Detail Keluhan')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        {{-- Flash message for highlight --}}
        @if (session('highlight_keluhan'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4 animate-pulse">
                <strong class="font-bold">Info:</strong>
                <span class="block sm:inline">Ini adalah detail keluhan dari notifikasi yang baru saja Anda klik.</span>
            </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Keluhan #{{ $keluhan->id_keluhan }}</h1>
            <a href="{{ route('admin.keluhan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Keluhan Details --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Informasi Keluhan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Deskripsi Masalah</span>
                            <p class="text-gray-800 dark:text-gray-200 text-lg">{{ $keluhan->deskripsi }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                             <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Kategori Keluhan</span>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        {{ $keluhan->keluhan1 }}
                                    </span>
                                    @if($keluhan->keluhan2)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        {{ $keluhan->keluhan2 }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                             <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Prioritas</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($keluhan->prioritas === 'High') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    @elseif($keluhan->prioritas === 'Normal') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                    {{ ucfirst($keluhan->prioritas) }}
                                </span>
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                             <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Sumber Laporan</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ ucfirst($keluhan->sumber) }} (Via: {{ $keluhan->via }})</span>
                            </div>
                             <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Disampaikan Oleh</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ $keluhan->disampaikan_oleh }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Handling Info --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Penanganan</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Tanggal Laporan</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($keluhan->tanggal_input)->format('d F Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Tujuan Divisi</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ $keluhan->tujuan }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Jenis SPK</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ $keluhan->jenis_spk }}</span>
                            </div>
                             <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Status Penyelesaian</span>
                                <p class="text-gray-800 dark:text-gray-200">{{ $keluhan->penyelesaian ?? 'Belum ada catatan penyelesaian' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="space-y-6">
                {{-- Customer Info --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Data Pelanggan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                         <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Nama</span>
                            <a href="{{ route('admin.pelanggan.show', $keluhan->pelanggan_id) }}" class="text-blue-600 hover:underline font-medium">
                                {{ $keluhan->pelanggan->nama_lengkap ?? $keluhan->pelanggan->nama_perusahaan ?? '-' }}
                            </a>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">ID Pelanggan</span>
                            <span class="text-gray-800 dark:text-gray-200 font-mono text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $keluhan->pelanggan->nomor_pelanggan }}
                            </span>
                        </div>
                         <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Alamat</span>
                            <span class="text-gray-800 dark:text-gray-200 text-sm">{{ $keluhan->pelanggan->alamat }}</span>
                        </div>
                         <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Layanan Utama</span>
                            <span class="text-gray-800 dark:text-gray-200">{{ $keluhan->layananInduk->nama_layanan_induk ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col gap-3">
                    @can('keluhan.edit')
                    <a href="{{ route('admin.keluhan.edit', $keluhan->id_keluhan) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit"></i> Edit Keluhan
                    </a>
                    @endcan
                    
                    @can('keluhan.delete')
                    <form action="{{ route('admin.keluhan.destroy', $keluhan->id_keluhan) }}" method="POST" onsubmit="return confirm('Hapus keluhan ini?');" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition-colors">
                            <i class="fas fa-trash"></i> Hapus Keluhan
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
