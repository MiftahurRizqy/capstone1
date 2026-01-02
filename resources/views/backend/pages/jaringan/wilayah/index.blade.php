@extends('backend.layouts.app')

@section('title', 'Manajemen Wilayah')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('admin-content')
<div class="container mx-auto px-4 py-6" x-data="wilayahManager()">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Manajemen Wilayah (Bagian)</h1>
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.jaringan.wilayah.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow dark:bg-green-500 dark:hover:bg-green-600 transition-colors duration-200">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                    </a>
                    <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Bagian</span>
                    </button>
                </div>

                {{-- Modal Tambah Bagian --}}
                <div x-show="open"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    x-cloak>
                    <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-2xl shadow-lg p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Bagian Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Gagal!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif
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

                        <form action="{{ route('admin.jaringan.wilayah.store') }}" method="POST" class="space-y-4" x-data="{
                            allProvinces: {{ json_encode($provinsi) }},
                            selectedProvinsi: '{{ old('external_provinsi_id', '') }}',
                            selectedKabupaten: '{{ old('external_kabupaten_id', '') }}',
                            selectedKecamatan: '{{ old('external_kecamatan_id', '') }}',
                            selectedKelurahan: '{{ old('external_kelurahan_id', '') }}',

                            kabupatenOptions: [],
                            kecamatanOptions: [],
                            kelurahanOptions: [],

                            isLoadingKabupaten: false,
                            isLoadingKecamatan: false,
                            isLoadingKelurahan: false,

                            async initDropdowns() {
                                console.log('initDropdowns called');
                                if (this.selectedProvinsi) {
                                    await this.fetchKabupaten(true);
                                }
                                // Only fetch subsequent levels if the parent was successfully loaded or previously set
                                if (this.selectedKabupaten && this.kabupatenOptions.some(k => k.id == this.selectedKabupaten)) {
                                    await this.fetchKecamatan(true);
                                }
                                if (this.selectedKecamatan && this.kecamatanOptions.some(k => k.id == this.selectedKecamatan)) {
                                    await this.fetchKelurahan(true);
                                }
                            },

                            async fetchKabupaten(isInitialLoad = false) {
                                if (!isInitialLoad) {
                                    this.kabupatenOptions = [];
                                    this.kecamatanOptions = [];
                                    this.kelurahanOptions = [];
                                    this.selectedKabupaten = '';
                                    this.selectedKecamatan = '';
                                    this.selectedKelurahan = '';
                                }

                                if (this.selectedProvinsi) {
                                    this.isLoadingKabupaten = true;
                                    try {
                                        const response = await fetch(`{{ route('admin.jaringan.wilayah.children') }}?parent_id=${this.selectedProvinsi}&child_type=kabupaten`);
                                        if (!response.ok) {
                                            const errorText = await response.text();
                                            throw new Error(`Network response was not ok: ${response.status} - ${errorText}`);
                                        }
                                        const data = await response.json();
                                        this.kabupatenOptions = data;

                                        if (isInitialLoad && '{{ old('external_kabupaten_id') }}') {
                                            // Check if the old selected kabupaten exists in the fetched data
                                            if (data.some(k => k.id == '{{ old('external_kabupaten_id') }}')) {
                                                this.selectedKabupaten = '{{ old('external_kabupaten_id') }}';
                                                // Fetch next level only if current level selected value is valid
                                                await this.fetchKecamatan(true);
                                            } else {
                                                // If old kabupaten_id is not in the list, reset it
                                                this.selectedKabupaten = '';
                                            }
                                        }
                                    } catch (error) {
                                        console.error('Error fetching regencies:', error);
                                        // alert('Gagal mengambil data Kabupaten/Kota. Silakan cek konsol browser untuk detail.');
                                    } finally {
                                        this.isLoadingKabupaten = false;
                                    }
                                }
                            },

                            async fetchKecamatan(isInitialLoad = false) {
                                if (!isInitialLoad) {
                                    this.kecamatanOptions = [];
                                    this.kelurahanOptions = [];
                                    this.selectedKecamatan = '';
                                    this.selectedKelurahan = '';
                                }

                                if (this.selectedKabupaten) {
                                    this.isLoadingKecamatan = true;
                                    try {
                                        const response = await fetch(`{{ route('admin.jaringan.wilayah.children') }}?parent_id=${this.selectedKabupaten}&child_type=kecamatan`);
                                        if (!response.ok) {
                                            const errorText = await response.text();
                                            throw new Error(`Network response was not ok: ${response.status} - ${errorText}`);
                                        }
                                        const data = await response.json();
                                        this.kecamatanOptions = data;

                                        if (isInitialLoad && '{{ old('external_kecamatan_id') }}') {
                                            if (data.some(k => k.id == '{{ old('external_kecamatan_id') }}')) {
                                                this.selectedKecamatan = '{{ old('external_kecamatan_id') }}';
                                                await this.fetchKelurahan(true);
                                            } else {
                                                this.selectedKecamatan = '';
                                            }
                                        }
                                    } catch (error) {
                                        console.error('Error fetching districts:', error);
                                        // alert('Gagal mengambil data Kecamatan. Silakan cek konsol browser untuk detail.');
                                    } finally {
                                        this.isLoadingKecamatan = false;
                                    }
                                }
                            },

                            async fetchKelurahan(isInitialLoad = false) {
                                if (!isInitialLoad) {
                                    this.kelurahanOptions = [];
                                    this.selectedKelurahan = '';
                                }

                                if (this.selectedKecamatan) {
                                    this.isLoadingKelurahan = true;
                                    console.log('Fetching villages for district ID:', this.selectedKecamatan);
                                    try {
                                        const response = await fetch(`{{ route('admin.jaringan.wilayah.children') }}?parent_id=${this.selectedKecamatan}&child_type=kelurahan`);
                                        if (!response.ok) {
                                            const errorText = await response.text();
                                            throw new Error(`Network response was not ok: ${response.status} - ${errorText}`);
                                        }
                                        const data = await response.json();
                                        this.kelurahanOptions = data;

                                        if (isInitialLoad && '{{ old('external_kelurahan_id') }}') {
                                            if (data.some(k => k.id == '{{ old('external_kelurahan_id') }}')) {
                                                this.selectedKelurahan = '{{ old('external_kelurahan_id') }}';
                                            } else {
                                                this.selectedKelurahan = '';
                                            }
                                        }
                                    } catch (error) {
                                        console.error('Error fetching villages:', error);
                                        // alert('Gagal mengambil data Kelurahan/Desa. Silakan cek konsol browser untuk detail.');
                                    } finally {
                                        this.isLoadingKelurahan = false;
                                    }
                                }
                            }
                        }" x-init="initDropdowns()">
                            @csrf
                            <div>
                                <label for="external_provinsi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                                <select name="external_provinsi_id" id="external_provinsi_id" x-model="selectedProvinsi" @change="fetchKabupaten()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('external_provinsi_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="p in allProvinces" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                                @error('external_provinsi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="external_kabupaten_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten/Kota</label>
                                <div class="relative">
                                    <select name="external_kabupaten_id" id="external_kabupaten_id" x-model="selectedKabupaten" @change="fetchKecamatan()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('external_kabupaten_id') border-red-500 @enderror" required>
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        <template x-for="kab in kabupatenOptions" :key="kab.id">
                                            <option :value="kab.id" x-text="kab.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="isLoadingKabupaten" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                    </div>
                                </div>
                                @error('external_kabupaten_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="external_kecamatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                                <div class="relative">
                                    <select name="external_kecamatan_id" id="external_kecamatan_id" x-model="selectedKecamatan" @change="fetchKelurahan()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('external_kecamatan_id') border-red-500 @enderror" required>
                                        <option value="">Pilih Kecamatan</option>
                                        <template x-for="kec in kecamatanOptions" :key="kec.id">
                                            <option :value="kec.id" x-text="kec.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="isLoadingKecamatan" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                    </div>
                                </div>
                                @error('external_kecamatan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            {{-- Baru: Dropdown Kelurahan/Desa --}}
                            <div>
                                <label for="external_kelurahan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan/Desa</label>
                                <div class="relative">
                                    <select name="external_kelurahan_id" id="external_kelurahan_id" x-model="selectedKelurahan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('external_kelurahan_id') border-red-500 @enderror" required>
                                        <option value="">Pilih Kelurahan/Desa</option>
                                        <template x-for="kel in kelurahanOptions" :key="kel.id">
                                            <option :value="kel.id" x-text="kel.name"></option>
                                        </template>
                                    </select>
                                    <div x-show="isLoadingKelurahan" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                                    </div>
                                </div>
                                @error('external_kelurahan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            {{-- Akhir Dropdown Kelurahan/Desa --}}

                            <div>
                                <label for="nama_bagian" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Bagian</label>
                                <input type="text" name="nama_bagian" id="nama_bagian" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_bagian') border-red-500 @enderror" value="{{ old('nama_bagian') }}" required>
                                @error('nama_bagian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6 flex justify-end gap-2">
                                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel daftar Bagian (ubah header jika perlu) --}}
        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white/80">
                            <tr>
                                <th class="px-4 py-3">No.</th>
                                <th class="px-4 py-3">Provinsi</th>
                                <th class="px-4 py-3">Kabupaten/Kota</th>
                                <th class="px-4 py-3">Kecamatan</th>
                                <th class="px-4 py-3">Kelurahan/Desa</th> {{-- Baru --}}
                                <th class="px-4 py-3">Nama Bagian</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($bagian as $index => $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">{{ $item->provinsi_nama ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->kabupaten_nama ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->kecamatan_nama ?? '-' }}</td> {{-- Nama Kecamatan --}}
                                    <td class="px-4 py-3">{{ $item->kelurahan_nama ?? '-' }}</td> {{-- Nama Kelurahan/Desa --}}
                                    <td class="px-4 py-3">{{ $item->nama }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        {{-- Tombol Edit --}}
                                        <button @click="openEditModal({{ $item->id }})" 
                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span class="sr-only">Edit</span>
                                        </button>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.jaringan.wilayah.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bagian ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                     class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                                                     title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada data wilayah tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Edit Bagian --}}
        <div x-show="editModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display: none;">
            
            <div @click.away="editModalOpen = false" class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-2xl shadow-lg p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Bagian</h2>
                    <button type="button" @click="editModalOpen = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form :action="`{{ route('admin.jaringan.wilayah.index') }}/${editingId}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="edit_external_provinsi_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                        <select name="external_provinsi_id" id="edit_external_provinsi_id" x-model="editData.selectedProvinsi" @change="editFetchKabupaten()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Pilih Provinsi</option>
                            <template x-for="p in editData.allProvinces" :key="p.id">
                                <option :value="p.id" x-text="p.name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit_external_kabupaten_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten/Kota</label>
                        <div class="relative">
                            <select name="external_kabupaten_id" id="edit_external_kabupaten_id" x-model="editData.selectedKabupaten" @change="editFetchKecamatan()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="">Pilih Kabupaten/Kota</option>
                                <template x-for="kab in editData.kabupatenOptions" :key="kab.id">
                                    <option :value="kab.id" x-text="kab.name"></option>
                                </template>
                            </select>
                            <div x-show="editData.isLoadingKabupaten" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_external_kecamatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                        <div class="relative">
                            <select name="external_kecamatan_id" id="edit_external_kecamatan_id" x-model="editData.selectedKecamatan" @change="editFetchKelurahan()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="">Pilih Kecamatan</option>
                                <template x-for="kec in editData.kecamatanOptions" :key="kec.id">
                                    <option :value="kec.id" x-text="kec.name"></option>
                                </template>
                            </select>
                            <div x-show="editData.isLoadingKecamatan" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_external_kelurahan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan/Desa</label>
                        <div class="relative">
                            <select name="external_kelurahan_id" id="edit_external_kelurahan_id" x-model="editData.selectedKelurahan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="">Pilih Kelurahan/Desa</option>
                                <template x-for="kel in editData.kelurahanOptions" :key="kel.id">
                                    <option :value="kel.id" x-text="kel.name"></option>
                                </template>
                            </select>
                            <div x-show="editData.isLoadingKelurahan" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="edit_nama_bagian" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Bagian</label>
                        <input type="text" name="nama_bagian" id="edit_nama_bagian" x-model="editData.nama_bagian" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    
                    <div>
                        <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" x-model="editData.deskripsi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function wilayahManager() {
    return {
        open: {{ $errors->any() ? 'true' : 'false' }},
        editModalOpen: false,
        editingId: null,
        editData: {
            allProvinces: [],
            selectedProvinsi: '',
            selectedKabupaten: '',
            selectedKecamatan: '',
            selectedKelurahan: '',
            kabupatenOptions: [],
            kecamatanOptions: [],
            kelurahanOptions: [],
            isLoadingKabupaten: false,
            isLoadingKecamatan: false,
            isLoadingKelurahan: false,
            nama_bagian: '',
            deskripsi: ''
        },

        async openEditModal(id) {
            this.editingId = id;
            this.editModalOpen = true;
            
            try {
                const response = await fetch(`{{ route('admin.jaringan.wilayah.index') }}/${id}/edit`);
                const result = await response.json();
                
                if (result.success) {
                    this.editData.allProvinces = result.provinsi;
                    this.editData.selectedProvinsi = result.data.external_provinsi_id;
                    this.editData.selectedKabupaten = result.data.external_kabupaten_id;
                    this.editData.selectedKecamatan = result.data.external_kecamatan_id;
                    this.editData.selectedKelurahan = result.data.external_kelurahan_id;
                    this.editData.nama_bagian = result.data.nama;
                    this.editData.deskripsi = result.data.deskripsi || '';
                    
                    // Load cascading dropdowns
                    await this.editFetchKabupaten();
                    await this.editFetchKecamatan();
                    await this.editFetchKelurahan();
                }
            } catch (error) {
                console.error('Error loading edit data:', error);
                alert('Gagal memuat data bagian');
            }
        },

        async editFetchKabupaten() {
            if (!this.editData.selectedProvinsi) return;
            
            this.editData.isLoadingKabupaten = true;
            this.editData.kabupatenOptions = [];
            
            try {
                const response = await fetch(`{{ route('admin.jaringan.wilayah.children') }}?parent_id=${this.editData.selectedProvinsi}&child_type=kabupaten`);
                if (response.ok) {
                    this.editData.kabupatenOptions = await response.json();
                }
            } catch (error) {
                console.error('Error fetching kabupaten:', error);
            } finally {
                this.editData.isLoadingKabupaten = false;
            }
        },

        async editFetchKecamatan() {
            if (!this.editData.selectedKabupaten) return;
            
            this.editData.isLoadingKecamatan = true;
            this.editData.kecamatanOptions = [];
            
            try {
                const response = await fetch(`{{ route('admin.jaringan.wilayah.children') }}?parent_id=${this.editData.selectedKabupaten}&child_type=kecamatan`);
                if (response.ok) {
                    this.editData.kecamatanOptions = await response.json();
                }
            } catch (error) {
                console.error('Error fetching kecamatan:', error);
            } finally {
                this.editData.isLoadingKecamatan = false;
            }
        },

        async editFetchKelurahan() {
            if (!this.editData.selectedKecamatan) return;
            
            this.editData.isLoadingKelurahan = true;
            this.editData.kelurahanOptions = [];
            
            try {
                const response = await fetch(`{{ route('admin.jaringan.wilayah.children') }}?parent_id=${this.editData.selectedKecamatan}&child_type=kelurahan`);
                if (response.ok) {
                    this.editData.kelurahanOptions = await response.json();
                }
            } catch (error) {
                console.error('Error fetching kelurahan:', error);
            } finally {
                this.editData.isLoadingKelurahan = false;
            }
        }
    }
}
</script>
@endpush