@extends('backend.layouts.app')

@section('title', 'Manajemen Layanan')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Manajemen Layanan</h1>
            <div x-data="{ open: false }">
                <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Layanan</span>
                </button>

                {{-- Modal Tambah Layanan --}}
                <div x-show="open || ('{{ session('modal_open') }}' === 'add_layanan_entry_error')"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
                    style="display: none;">
                    <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Layanan Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        {{-- Display validation errors for modal --}}
                        @if ($errors->any() && session('modal_open') === 'add_layanan_entry_error')
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

                        {{-- Form Tambah --}}
                        <form action="{{ route('admin.layanan.entry.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode</label>
                                    <input type="text" name="kode" value="{{ old('kode') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('kode') border-red-500 @enderror">
                                    @error('kode')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Paket</label>
                                    <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_paket') border-red-500 @enderror">
                                    @error('nama_paket')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('status') border-red-500 @enderror">
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                                    <select name="tipe" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tipe') border-red-500 @enderror">
                                        <option value="TV" {{ old('tipe') == 'TV' ? 'selected' : '' }}>TV</option>
                                        <option value="Internet" {{ old('tipe') == 'Internet' ? 'selected' : '' }}>Internet</option>
                                        <option value="Lain-Lain" {{ old('tipe') == 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
                                    </select>
                                    @error('tipe')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelompok Layanan</label>
                                    <select name="kelompok_layanan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('kelompok_layanan') border-red-500 @enderror">
                                        <option value="Layanan Dasar" {{ old('kelompok_layanan') == 'Layanan Dasar' ? 'selected' : '' }}>Layanan Dasar</option>
                                        <option value="Web Hosting" {{ old('kelompok_layanan') == 'Web Hosting' ? 'selected' : '' }}>Web Hosting</option>
                                        <option value="Colocation" {{ old('kelompok_layanan') == 'Colocation' ? 'selected' : '' }}>Colocation</option>
                                    </select>
                                    @error('kelompok_layanan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Layanan Induk</label>
                                    <select name="layanan_induk_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('layanan_induk_id') border-red-500 @enderror">
                                        <option value="">Tidak ada</option>
                                        @foreach($layananInduks as $li)
                                        <option value="{{ $li->id }}" {{ old('layanan_induk_id') == $li->id ? 'selected' : '' }}>{{ $li->nama_layanan_induk }}</option>
                                        @endforeach
                                    </select>
                                    @error('layanan_induk_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kolom Checkbox SPK --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SPK Checkboxes</label>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex items-center">
                                            <input type="hidden" name="spk_osp_instalasi" value="0">
                                            <input type="checkbox" name="spk_osp_instalasi" value="1" {{ old('spk_osp_instalasi') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK OSP Instalasi</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="hidden" name="spk_isp_instalasi" value="0">
                                            <input type="checkbox" name="spk_isp_instalasi" value="1" {{ old('spk_isp_instalasi') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK ISP Instalasi</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="hidden" name="spk_osp_aktif_kembali" value="0">
                                            <input type="checkbox" name="spk_osp_aktif_kembali" value="1" {{ old('spk_osp_aktif_kembali') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK OSP Aktif Kembali</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="hidden" name="spk_isp_aktif_kembali" value="0">
                                            <input type="checkbox" name="spk_isp_aktif_kembali" value="1" {{ old('spk_isp_aktif_kembali') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK ISP Aktif Kembali</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bidang Lainnya --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Layanan SPK</label>
                                    <select name="tipe_layanan_spk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Pilih Tipe</option>
                                        <option value="TV" {{ old('tipe_layanan_spk') == 'TV' ? 'selected' : '' }}>TV</option>
                                        <option value="Internet HFC" {{ old('tipe_layanan_spk') == 'Internet HFC' ? 'selected' : '' }}>Internet HFC</option>
                                        <option value="Internet Wireless" {{ old('tipe_layanan_spk') == 'Internet Wireless' ? 'selected' : '' }}>Internet Wireless</option>
                                        <option value="Wi TV" {{ old('tipe_layanan_spk') == 'Wi TV' ? 'selected' : '' }}>Wi TV</option>
                                        <option value="TV-DTH" {{ old('tipe_layanan_spk') == 'TV-DTH' ? 'selected' : '' }}>TV-DTH</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Utilisasi Bandwidth (kbps)</label>
                                    <input type="number" name="utilisasi_bandwidth" value="{{ old('utilisasi_bandwidth') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Setup</label>
                                    <input type="number" step="0.01" name="biaya_setup" value="{{ old('biaya_setup') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('biaya_setup') border-red-500 @enderror">
                                    @error('biaya_setup')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 1 Bulan</label>
                                    <input type="number" step="0.01" name="biaya_reguler_1_bulan" value="{{ old('biaya_reguler_1_bulan') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('biaya_reguler_1_bulan') border-red-500 @enderror">
                                    @error('biaya_reguler_1_bulan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 3 Bulan</label>
                                    <input type="number" step="0.01" name="biaya_reguler_3_bulan" value="{{ old('biaya_reguler_3_bulan') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('biaya_reguler_3_bulan') border-red-500 @enderror">
                                    @error('biaya_reguler_3_bulan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus Reguler 3 Bulan</label>
                                    <select name="bonus_reguler_3_bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Tidak ada</option>
                                        @foreach(['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'] as $bonus)
                                        <option value="{{ $bonus }}" {{ old('bonus_reguler_3_bulan') == $bonus ? 'selected' : '' }}>{{ $bonus }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 6 Bulan</label>
                                    <input type="number" step="0.01" name="biaya_reguler_6_bulan" value="{{ old('biaya_reguler_6_bulan') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('biaya_reguler_6_bulan') border-red-500 @enderror">
                                    @error('biaya_reguler_6_bulan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus Reguler 6 Bulan</label>
                                    <select name="bonus_reguler_6_bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Tidak ada</option>
                                        @foreach(['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'] as $bonus)
                                        <option value="{{ $bonus }}" {{ old('bonus_reguler_6_bulan') == $bonus ? 'selected' : '' }}>{{ $bonus }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 12 Bulan</label>
                                    <input type="number" step="0.01" name="biaya_reguler_12_bulan" value="{{ old('biaya_reguler_12_bulan') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('biaya_reguler_12_bulan') border-red-500 @enderror">
                                    @error('biaya_reguler_12_bulan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus Reguler 12 Bulan</label>
                                    <select name="bonus_reguler_12_bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Tidak ada</option>
                                        @foreach(['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'] as $bonus)
                                        <option value="{{ $bonus }}" {{ old('bonus_reguler_12_bulan') == $bonus ? 'selected' : '' }}>{{ $bonus }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Koneksi TV Kabel</label>
                                    <select name="koneksi_tv_kabel" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Tidak ada</option>
                                        <option value="Corporate TV" {{ old('koneksi_tv_kabel') == 'Corporate TV' ? 'selected' : '' }}>Corporate TV</option>
                                        <option value="Layanan Lain" {{ old('koneksi_tv_kabel') == 'Layanan Lain' ? 'selected' : '' }}>Layanan Lain</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kompensasi Diskoneksi</label>
                                    <select name="kompensasi_diskoneksi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Tidak ada</option>
                                        <option value="Terima Kompensasi" {{ old('kompensasi_diskoneksi') == 'Terima Kompensasi' ? 'selected' : '' }}>Terima Kompensasi</option>
                                        <option value="Tidak Terima Kompensasi" {{ old('kompensasi_diskoneksi') == 'Tidak Terima Kompensasi' ? 'selected' : '' }}>Tidak Terima Kompensasi</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfigurasi DHCP</label>
                                    <textarea name="konfigurasi_dhcp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('konfigurasi_dhcp') }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Redaksional Invoice</label>
                                    <input type="text" name="redaksional_invoice" value="{{ old('redaksional_invoice') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Redaksional Invoice 2 (khusus Hosting)</label>
                                    <input type="text" name="redaksional_invoice_2" value="{{ old('redaksional_invoice_2') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account MYOB 1</label>
                                    <input type="text" name="account_myob_1" value="{{ old('account_myob_1') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account MYOB 2</label>
                                    <input type="text" name="account_myob_2" value="{{ old('account_myob_2') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Milis</label>
                                    <input type="text" name="nama_milis" value="{{ old('nama_milis') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                    <textarea name="deskripsi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('deskripsi') }}</textarea>
                                </div>
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

        @if (session('success') && session('modal_open') !== 'add_layanan_entry_error')
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

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('admin.layanan.entry.index') }}">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Cari kode atau nama paket..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('admin.layanan.entry.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Reset</a>
                    <a href="{{ route('admin.layanan.entry.export', request()->query()) }}" class="btn btn-success inline-flex items-center gap-2">
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
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">Nama Paket</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Kelompok Layanan</th>
                                <th class="px-4 py-3">Layanan Induk</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($layananEntries as $entry)
                                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.layanan.entry.show', $entry->id) }}" class="text-blue-600 hover:underline font-medium">
                                            {{ $entry->kode }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $entry->nama_paket }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ strtolower($entry->status) == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                            {{ ucfirst($entry->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $entry->tipe }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $entry->kelompok_layanan }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $entry->layananInduk->nama_layanan_induk ?? '-' }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.layanan.entry.edit', $entry->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span class="sr-only">Edit</span>
                                        </a>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.layanan.entry.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Hapus</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty

                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada data layanan.
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

@endsection