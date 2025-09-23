@extends('backend.layouts.app')

@section('title', 'Edit Layanan Induk')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
        <div class="card-body p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Layanan Induk</h2>

            <form action="{{ route('admin.layanan.induk.update', $layananInduk->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="nama_layanan_induk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Layanan Induk</label>
                    <input type="text" name="nama_layanan_induk" id="nama_layanan_induk" value="{{ old('nama_layanan_induk', $layananInduk->nama_layanan_induk) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_layanan_induk') border-red-500 @enderror">
                    @error('nama_layanan_induk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('admin.layanan.induk.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection