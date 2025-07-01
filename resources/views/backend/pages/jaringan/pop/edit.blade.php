@extends('backend.layouts.app')

@section('title', 'Edit POP')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Point of Presence (POP)</h1>
            <a href="{{ route('admin.jaringan.pop.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg shadow dark:bg-gray-500 dark:hover:bg-gray-600 transition-colors duration-200">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl w-full shadow-lg p-6">
            {{-- Display success or error messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.jaringan.pop.update', $pop->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT') {{-- Penting untuk method PUT --}}

                <div>
                    <label for="nama_pop" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama POP</label>
                    <input type="text" name="nama_pop" id="nama_pop" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_pop') border-red-500 @enderror" value="{{ old('nama_pop', $pop->nama_pop) }}" required>
                    @error('nama_pop') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="kabupaten_kota" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten/Kota</label>
                    <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('kabupaten_kota') border-red-500 @enderror" value="{{ old('kabupaten_kota', $pop->kabupaten_kota) }}" required>
                    @error('kabupaten_kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="daerah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Daerah</label>
                    <input type="text" name="daerah" id="daerah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('daerah') border-red-500 @enderror" value="{{ old('daerah', $pop->daerah) }}" required>
                    @error('daerah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rt_rw" class="block text-sm font-medium text-gray-700 dark:text-gray-300">RT/RW (Opsional)</label>
                    <input type="text" name="rt_rw" id="rt_rw" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('rt_rw') border-red-500 @enderror" value="{{ old('rt_rw', $pop->rt_rw) }}">
                    @error('rt_rw') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
