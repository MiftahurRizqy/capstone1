@extends('backend.layouts.app')

@section('title', 'Manajemen Keluhan')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Manajemen Keluhan</h1>
            <div x-data="{ open: false }">
                <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Keluhan</span>
                </button>

                {{-- Modal Tambah Keluhan --}}
                <div x-show="open || ('{{ session('modal_open') }}' === 'add_keluhan_error')"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
                    style="display: none;">
                    <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Keluhan Baru</h2>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors duration-200">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        {{-- Display validation errors for modal --}}
                        @if ($errors->any() && session('modal_open') === 'add_keluhan_error')
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

                        <form action="{{ route('admin.keluhan.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="layanan_induk_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Layanan</label>
                                <select name="layanan_induk_id" id="layanan_induk_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('layanan_induk_id') border-red-500 @enderror">
                                    <option value="">Pilih Layanan</option>
                                    @foreach($layananInduks as $l)
                                        <option value="{{ $l->id }}" {{ old('layanan_induk_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_layanan_induk }}</option>
                                    @endforeach
                                </select>
                                @error('layanan_induk_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="pelanggan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelanggan</label>
                                <select name="pelanggan_id" id="pelanggan_id" class="select2-pelanggan mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('pelanggan_id') border-red-500 @enderror">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($pelanggan as $p)
                                        <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_lengkap ?: $p->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelanggan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            {{-- Tambahan input jenis_spk --}}
                            <div>
                                <label for="jenis_spk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buat SPK ?</label>
                                <select name="jenis_spk" id="jenis_spk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('jenis_spk') border-red-500 @enderror">
                                    <option value="Tidak" {{ old('jenis_spk') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                    <option value="SPK OSP" {{ old('jenis_spk') == 'SPK OSP' ? 'selected' : '' }}>SPK OSP</option>
                                    <option value="SPK VOIP" {{ old('jenis_spk') == 'SPK VOIP' ? 'selected' : '' }}>SPK VOIP</option>
                                    <option value="SPK TS" {{ old('jenis_spk') == 'SPK TS' ? 'selected' : '' }}>SPK TS</option>
                                </select>
                                @error('jenis_spk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tujuan</label>
                                <select name="tujuan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tujuan') border-red-500 @enderror">
                                    @foreach(['Technical Support','Maintenance Cable','Maintenance Wireless','E-Gov Kota','E-Gov Propinsi','NOC','TV Kabel','Helpdesk','Admin CLEON','Support CLEON','SYS Admin CLEON'] as $t)
                                        <option value="{{ $t }}" {{ old('tujuan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                @error('tujuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioritas</label>
                                <select name="prioritas" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('prioritas') border-red-500 @enderror">
                                    <option value="low" {{ old('prioritas') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('prioritas') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('prioritas') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('prioritas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keluhan 1</label>
                                <input type="text" name="keluhan1" value="{{ old('keluhan1') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('keluhan1') border-red-500 @enderror">
                                @error('keluhan1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keluhan 2</label>
                                <input type="text" name="keluhan2" value="{{ old('keluhan2') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('keluhan2') border-red-500 @enderror">
                                @error('keluhan2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Via</label>
                                <select name="via" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('via') border-red-500 @enderror">
                                    @foreach(['Datang','Telpon/Fax','Email','SMS/WA/BBM/LINE'] as $v)
                                        <option value="{{ $v }}" {{ old('via') == $v ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                                @error('via') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                <textarea name="deskripsi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penyelesaian</label>
                                <textarea name="penyelesaian" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('penyelesaian') border-red-500 @enderror">{{ old('penyelesaian') }}</textarea>
                                @error('penyelesaian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Disampaikan Oleh</label>
                                <input type="text" name="disampaikan_oleh" value="{{ old('disampaikan_oleh') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('disampaikan_oleh') border-red-500 @enderror">
                                @error('disampaikan_oleh') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sumber</label>
                                <input type="text" name="sumber" value="{{ old('sumber') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('sumber') border-red-500 @enderror">
                                @error('sumber') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Input</label>
                                <input type="date" name="tanggal_input" value="{{ old('tanggal_input') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tanggal_input') border-red-500 @enderror">
                                @error('tanggal_input') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
        
        {{-- Display success or error messages for main page --}}
        @if (session('success') && session('modal_open') !== 'add_keluhan_error')
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error') && session('modal_open') !== 'add_keluhan_error')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Filter Form --}}
        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('admin.keluhan.index') }}">
                <div class="flex flex-wrap items-center gap-3">
                    <select name="prioritas" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white min-w-[180px]">
                        <option value="">-- Semua Prioritas --</option>
                        <option value="low" {{ request('prioritas') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('prioritas') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('prioritas') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    <input type="text" name="search" class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Cari keluhan, deskripsi, nama, atau nomor pelanggan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('admin.keluhan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Reset</a>
                    <a href="{{ route('admin.keluhan.export', request()->query()) }}" class="btn btn-success inline-flex items-center gap-2">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                    </a>
                </div>
            </form>
        </div>

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white/80">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Layanan</th>
                                <th class="px-4 py-3">Pelanggan</th>
                                <th class="px-4 py-3">Buat SPK?</th>
                                <th class="px-4 py-3">Tujuan</th>
                                <th class="px-4 py-3">Prioritas</th>
                                <th class="px-4 py-3">Keluhan 1</th>
                                <th class="px-4 py-3">Keluhan 2</th>
                                <th class="px-4 py-3">Via</th>
                                <th class="px-4 py-3">Tanggal Input</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($keluhan as $index => $k)
                            <tr>
                                <td class="px-4 py-3">{{ $keluhan->firstItem() + $index }}</td>
                                <td class="px-4 py-3">{{ $k->layananInduk->nama_layanan_induk ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    {{ $k->pelanggan->nama_lengkap ?: $k->pelanggan->nama_perusahaan ?? '-' }}
                                </td>
                                <td class="px-4 py-3">{{ $k->jenis_spk }}</td>
                                <td class="px-4 py-3">{{ $k->tujuan }}</td>
                                <td class="px-4 py-3">{{ ucfirst($k->prioritas) }}</td>
                                <td class="px-4 py-3">{{ $k->keluhan1 }}</td>
                                <td class="px-4 py-3">{{ $k->keluhan2 }}</td>
                                <td class="px-4 py-3">{{ $k->via }}</td>
                                <td class="px-4 py-3">{{ $k->tanggal_input->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.keluhan.edit', $k->id_keluhan) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Edit</span>
                                    </a>
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.keluhan.destroy', $k->id_keluhan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus keluhan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                            <span class="sr-only">Hapus</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Belum ada data keluhan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
        $('.select2-pelanggan').select2({
            placeholder: 'Pilih Pelanggan',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#pelanggan_id').closest('div').parent(),
            language: {
                searching: function() {
                    return 'Mencari...';
                },
                noResults: function() {
                    return 'Tidak ada hasil';
                }
            }
        });
        
        // Add placeholder to search field when dropdown opens
        $('.select2-pelanggan').on('select2:open', function() {
            setTimeout(function() {
                $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Ketik nama pelanggan...');
            }, 50);
        });
    });
</script>
@endpush

@endsection