@extends('backend.layouts.app')

@section('title', 'Edit SPK')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90 mb-4">Edit SPK #{{ $spk->nomor_spk }}</h2>

        <form action="{{ route('admin.spk.update', urlencode($spk->nomor_spk)) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Informasi SPK (Read-only) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <h3 class="font-bold text-lg dark:text-white/90">Informasi SPK</h3>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor SPK</label>
                    <p class="mt-1 p-2 border border-gray-300 rounded-md dark:border-gray-600 dark:text-white/90">{{ $spk->nomor_spk }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Layanan Induk</label>
                    <p class="mt-1 p-2 border border-gray-300 rounded-md dark:border-gray-600 dark:text-white/90">{{ $spk->layananInduk->nama_layanan_induk ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">POP</label>
                    <p class="mt-1 p-2 border border-gray-300 rounded-md dark:border-gray-600 dark:text-white/90">{{ $spk->pop->nama_pop ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                    <p class="mt-1 p-2 border border-gray-300 rounded-md dark:border-gray-600 dark:text-white/90">{{ $spk->alamat }}</p>
                </div>
            </div>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            {{-- Informasi Pelanggan (Read-only) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <h3 class="font-bold text-lg dark:text-white/90">Informasi Pelanggan</h3>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Pelanggan</label>
                    <p class="mt-1 p-2 border border-gray-300 rounded-md dark:border-gray-600 dark:text-white/90">{{ $spk->nomor_pelanggan }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pelanggan</label>
                    <p class="mt-1 p-2 border border-gray-300 rounded-md dark:border-gray-600 dark:text-white/90">
                        @if ($spk->pelanggan && $spk->pelanggan->tipe == 'personal')
                            {{ $spk->nama_lengkap ?? '-' }}
                        @elseif ($spk->pelanggan)
                            {{ $spk->nama_perusahaan ?? '-' }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            {{-- Form Edit --}}
            <h3 class="font-bold text-lg dark:text-white/90 mb-4">Detail Pengerjaan</h3>

            <div>
                <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                <select name="tipe" id="tipe" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach(['instalasi', 'migrasi', 'survey', 'dismantle', 'lain-lain'] as $tipe)
                        <option value="{{ $tipe }}" {{ $spk->tipe == $tipe ? 'selected' : '' }}>{{ ucfirst($tipe) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    {{-- Opsi status dari ENUM --}}
                    @foreach(['dijadwalkan', 'dalam_pengerjaan', 'reschedule', 'selesai_sebagian', 'selesai'] as $status)
                        <option value="{{ $status }}" {{ $spk->status == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kelengkapan_kerja" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelengkapan Kerja</label>
                <textarea name="kelengkapan_kerja" id="kelengkapan_kerja" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('kelengkapan_kerja', $spk->kelengkapan_kerja) }}</textarea>
                @error('kelengkapan_kerja') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('keterangan', $spk->keterangan) }}</textarea>
                @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="rencana_pengerjaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rencana Pengerjaan</label>
                <input type="date" name="rencana_pengerjaan" value="{{ old('rencana_pengerjaan', $spk->rencana_pengerjaan ? $spk->rencana_pengerjaan->format('Y-m-d') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('rencana_pengerjaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="pelaksana_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelaksana 1</label>
                <select name="pelaksana_1" id="pelaksana_1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Pelaksana</option>
                    @foreach($pelaksanaOptions as $user)
                        <option value="{{ $user->name }}" {{ ($spk->pelaksana_1 ?? old('pelaksana_1')) == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('pelaksana_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="pelaksana_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelaksana 2</label>
                <select name="pelaksana_2" id="pelaksana_2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Pelaksana</option>
                    @foreach($pelaksanaOptions as $user)
                        <option value="{{ $user->name }}" {{ ($spk->pelaksana_2 ?? old('pelaksana_2')) == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('pelaksana_2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="koordinator" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Koordinator</label>
                <input type="text" name="koordinator" value="{{ old('koordinator', $spk->koordinator) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('koordinator') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Update</button>
                <a href="{{ route('admin.spk.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
