@extends('backend.layouts.app')

@section('title', 'Edit Wilayah')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Wilayah (Bagian)</h1>
            <a href="{{ route('admin.jaringan.wilayah.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

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

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-6">
            <form action="{{ route('admin.jaringan.wilayah.update', $bagian->id) }}" method="POST" class="space-y-4" x-data="{
                allProvinces: {{ json_encode($provinsi) }},
                selectedProvinsi: '{{ old('external_provinsi_id', $bagian->external_provinsi_id) }}',
                selectedKabupaten: '{{ old('external_kabupaten_id', $bagian->external_kabupaten_id) }}',
                selectedKecamatan: '{{ old('external_kelurahan_id', $bagian->external_kelurahan_id) }}',
                kabupatenOptions: {{ json_encode($kabupatenOptions) }}, // Initial data from controller
                kecamatanOptions: {{ json_encode($kecamatanOptions) }}, // Initial data from controller

                async fetchKabupaten() {
                    this.kabupatenOptions = [];
                    this.kecamatanOptions = [];
                    this.selectedKabupaten = '';
                    this.selectedKecamatan = '';
                    if (this.selectedProvinsi) {
                        console.log('Fetching regencies for province ID:', this.selectedProvinsi);
                        try {
                            const response = await fetch(`/api/wilayah/children?parent_id=${this.selectedProvinsi}&child_type=kabupaten`);
                            if (!response.ok) {
                                const errorText = await response.text();
                                throw new Error(`Network response was not ok: ${response.status} - ${errorText}`);
                            }
                            const data = await response.json();
                            console.log('Regency API Response Data:', data);
                            this.kabupatenOptions = data;
                            // Re-select old or current value if available
                            if ('{{ old('external_kabupaten_id') }}') {
                                this.selectedKabupaten = '{{ old('external_kabupaten_id') }}';
                            } else if ('{{ $bagian->external_kabupaten_id }}') {
                                this.selectedKabupaten = '{{ $bagian->external_kabupaten_id }}';
                            }
                            if (this.selectedKabupaten) {
                                await this.fetchKecamatan(); // Fetch kecamatan if kabupaten is selected
                            }
                        } catch (error) {
                            console.error('Error fetching regencies:', error);
                            alert('Gagal mengambil data Kabupaten/Kota. Silakan cek konsol browser untuk detail.');
                        }
                    }
                },
                async fetchKecamatan() {
                    this.kecamatanOptions = [];
                    this.selectedKecamatan = '';
                    if (this.selectedKabupaten) {
                        console.log('Fetching districts for regency ID:', this.selectedKabupaten);
                        try {
                            const response = await fetch(`/api/wilayah/children?parent_id=${this.selectedKabupaten}&child_type=kecamatan`);
                            if (!response.ok) {
                                const errorText = await response.text();
                                throw new Error(`Network response was not ok: ${response.status} - ${errorText}`);
                            }
                            const data = await response.json();
                            console.log('District API Response Data:', data);
                            this.kecamatanOptions = data;
                            // Re-select old or current value if available
                            if ('{{ old('external_kelurahan_id') }}') {
                                this.selectedKecamatan = '{{ old('external_kelurahan_id') }}';
                            } else if ('{{ $bagian->external_kelurahan_id }}') {
                                this.selectedKecamatan = '{{ $bagian->external_kelurahan_id }}';
                            }
                        } catch (error) {
                            console.error('Error fetching districts:', error);
                            alert('Gagal mengambil data Kecamatan. Silakan cek konsol browser untuk detail.');
                        }
                    }
                }
            }" x-init="
                // The controller already passes initial kabupatenOptions and kecamatanOptions.
                // However, if the user changes the province/kabupaten, we need to re-fetch.
                // The `x-model` handles initial selection.
                // If there were validation errors and `old()` values exist, `x-init` should re-fetch.
                console.log('Edit initDropdowns called');
                if (this.selectedProvinsi && this.kabupatenOptions.length === 0 && '{{ $bagian->external_provinsi_id }}' === this.selectedProvinsi) {
                    // This condition handles if the page loads directly (not from validation error)
                    // and initial options were empty for some reason, or if the initial fetch failed.
                    // But typically, the controller already provides the initial options.
                    // The main goal is to re-fetch if old input forces a different selection.
                    console.log('Re-fetching kabupaten based on initial selectedProvinsi for edit.');
                    this.fetchKabupaten();
                }
                if (this.selectedKabupaten && this.kecamatanOptions.length === 0 && '{{ $bagian->external_kabupaten_id }}' === this.selectedKabupaten) {
                    console.log('Re-fetching kecamatan based on initial selectedKabupaten for edit.');
                    this.fetchKecamatan();
                }
            ">
                @csrf
                @method('PUT')
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
                    <select name="external_kabupaten_id" id="external_kabupaten_id" x-model="selectedKabupaten" @change="fetchKecamatan()" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('external_kabupaten_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kabupaten/Kota</option>
                        <template x-for="kab in kabupatenOptions" :key="kab.id">
                            <option :value="kab.id" x-text="kab.name"></option>
                        </template>
                    </select>
                    @error('external_kabupaten_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="external_kelurahan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan</label>
                    <select name="external_kelurahan_id" id="external_kelurahan_id" x-model="selectedKecamatan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('external_kelurahan_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kecamatan</option>
                        <template x-for="kec in kecamatanOptions" :key="kec.id">
                            <option :value="kec.id" x-text="kec.name"></option>
                        </template>
                    </select>
                    @error('external_kelurahan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="nama_bagian" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Bagian</label>
                    <input type="text" name="nama_bagian" id="nama_bagian" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_bagian') border-red-500 @enderror" value="{{ old('nama_bagian', $bagian->nama) }}" required>
                    @error('nama_bagian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $bagian->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush