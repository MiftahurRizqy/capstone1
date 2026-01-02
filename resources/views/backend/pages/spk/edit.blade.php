@extends('backend.layouts.app')

@section('title', 'Edit SPK')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit SPK #{{ $spk->nomor_spk }}</h2>

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
                        @if ($spk->pelanggan)
                            {{ $spk->pelanggan->nama_lengkap ?: $spk->pelanggan->nama_perusahaan ?? '-' }}
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
                <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe</label>
                <select name="tipe" id="tipe" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                    @foreach(['instalasi', 'migrasi', 'survey', 'dismantle', 'lain-lain'] as $tipe)
                        <option value="{{ $tipe }}" {{ $spk->tipe == $tipe ? 'selected' : '' }}>{{ ucfirst($tipe) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" id="status" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                    @foreach(['dijadwalkan', 'dalam_pengerjaan', 'reschedule', 'selesai_sebagian', 'selesai'] as $status)
                        <option value="{{ $status }}" {{ $spk->status == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kelengkapan_kerja" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelengkapan Kerja</label>
                <textarea name="kelengkapan_kerja" id="kelengkapan_kerja" rows="3" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">{{ old('kelengkapan_kerja', $spk->kelengkapan_kerja) }}</textarea>
                @error('kelengkapan_kerja') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">{{ old('keterangan', $spk->keterangan) }}</textarea>
                @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="rencana_pengerjaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rencana Pengerjaan</label>
                <input type="date" name="rencana_pengerjaan" id="rencana_pengerjaan" value="{{ old('rencana_pengerjaan', $spk->rencana_pengerjaan ? $spk->rencana_pengerjaan->format('Y-m-d') : '') }}" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                @error('rencana_pengerjaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="pelaksana_1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pelaksana 1</label>
                <select name="pelaksana_1" id="pelaksana_1" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                    <option value="">Pilih Pelaksana</option>
                    @foreach($pelaksanaOptions as $user)
                        <option value="{{ $user->name }}" {{ ($spk->pelaksana_1 ?? old('pelaksana_1')) == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('pelaksana_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="pelaksana_2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pelaksana 2</label>
                <select name="pelaksana_2" id="pelaksana_2" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                    <option value="">Pilih Pelaksana</option>
                    @foreach($pelaksanaOptions as $user)
                        <option value="{{ $user->name }}" {{ ($spk->pelaksana_2 ?? old('pelaksana_2')) == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('pelaksana_2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="koordinator" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Koordinator (Disetujui Oleh)</label>
                <select name="koordinator" id="koordinator" class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                    <option value="">Pilih Koordinator</option>
                    @foreach($pelaksanaOptions as $user)
                        <option value="{{ $user->name }}" {{ ($spk->koordinator ?? old('koordinator')) == $user->name ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('koordinator') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.spk.index') }}" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-md hover:shadow-lg">Update SPK</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 Container Styling */
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        padding: 8px 12px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        background-color: white !important;
        transition: all 0.2s;
    }
    
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        outline: none !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important;
        padding-left: 0 !important;
        color: #1f2937 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    
    /* Dropdown Styling */
    .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .select2-search--dropdown {
        padding: 8px !important;
    }
    
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 6px 12px !important;
    }
    
    .select2-search--dropdown .select2-search__field::placeholder {
        color: #9ca3af !important;
    }
    
    .select2-results__option {
        padding: 8px 12px !important;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6 !important;
    }
    
    /* Dark Mode */
    .dark .select2-container--default .select2-selection--single {
        background-color: #374151 !important;
        border-color: #4b5563 !important;
    }
    
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
    }
    
    .dark .select2-dropdown {
        background-color: #374151 !important;
        border-color: #4b5563 !important;
    }
    
    .dark .select2-search--dropdown .select2-search__field {
        background-color: #1f2937 !important;
        border-color: #4b5563 !important;
        color: white !important;
    }
    
    .dark .select2-container--default .select2-results__option {
        color: white !important;
        background-color: #374151 !important;
    }
    
    .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 with tags for Pelaksana 1
        $('#pelaksana_1').select2({
            tags: true,
            placeholder: 'Pilih atau ketik nama pelaksana',
            allowClear: true,
            width: '100%'
        });
        
        // Add placeholder to search field when dropdown opens
        $('#pelaksana_1').on('select2:open', function() {
            setTimeout(function() {
                $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Ketik nama pelaksana...');
            }, 50);
        });
        
        // Initialize Select2 with tags for Pelaksana 2
        $('#pelaksana_2').select2({
            tags: true,
            placeholder: 'Pilih atau ketik nama pelaksana',
            allowClear: true,
            width: '100%'
        });
        
        // Add placeholder to search field when dropdown opens
        $('#pelaksana_2').on('select2:open', function() {
            setTimeout(function() {
                $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Ketik nama pelaksana...');
            }, 50);
        });
        
        // Initialize Select2 with tags for Koordinator
        $('#koordinator').select2({
            tags: true,
            placeholder: 'Pilih atau ketik nama koordinator',
            allowClear: true,
            width: '100%'
        });
        
        // Add placeholder to search field when dropdown opens
        $('#koordinator').on('select2:open', function() {
            setTimeout(function() {
                $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Ketik nama koordinator...');
            }, 50);
        });
    });
</script>
@endpush

@endsection
