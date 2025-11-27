@extends('backend.layouts.app')

@section('title', 'Edit Kategori Pelanggan')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Kategori Pelanggan</h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $kategori->nama) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama') border-red-500 @enderror" required>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                {{-- Opsi Field Personal --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Field untuk Tipe Personal</h3>
                    <div class="space-y-2">
                        @foreach($availableFields['personal'] as $fieldKey => $fieldLabel)
                            <div class="flex items-center">
                                <input type="checkbox" name="personal_fields[]" id="personal_{{ $fieldKey }}" value="{{ $fieldKey }}" 
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" 
                                       {{ in_array($fieldKey, old('personal_fields', $kategori->personal_fields ?? [])) ? 'checked' : '' }}>
                                <label for="personal_{{ $fieldKey }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">{{ $fieldLabel }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Opsi Field Perusahaan --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Field untuk Tipe Perusahaan</h3>
                    <div class="space-y-2">
                        @foreach($availableFields['perusahaan'] as $fieldKey => $fieldLabel)
                            <div class="flex items-center">
                                <input type="checkbox" name="perusahaan_fields[]" id="perusahaan_{{ $fieldKey }}" value="{{ $fieldKey }}" 
                                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" 
                                       {{ in_array($fieldKey, old('perusahaan_fields', $kategori->perusahaan_fields ?? [])) ? 'checked' : '' }}>
                                <label for="perusahaan_{{ $fieldKey }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">{{ $fieldLabel }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
