@extends('backend.layouts.app')

@section('title', 'Daftar Pelanggan Perusahaan')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Daftar Pelanggan Perusahaan</h1>
            <div x-data="{ open: false }">
                <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Pelanggan</span>
                </button>

                <div x-show="open"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    style="display: none;">
                    <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-2xl shadow-lg p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Pelanggan Perusahaan Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

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

                        {{-- Form untuk menambahkan pelanggan perusahaan --}}
                        <form action="{{ route('admin.pelanggan.store') }}" method="POST" class="p-0">
                            @csrf
                            {{-- Hidden field untuk tipe pelanggan --}}
                            <input type="hidden" name="tipe" value="perusahaan">

                            <div x-data="{ activeTab: 'informasi_perusahaan' }">
                                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                        {{-- Tab Informasi Perusahaan --}}
                                        <button type="button" @click="activeTab = 'informasi_perusahaan'"
                                                :class="activeTab === 'informasi_perusahaan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Informasi Perusahaan
                                        </button>
                                        {{-- Tab Layanan --}}
                                        <button type="button" @click="activeTab = 'layanan'"
                                                :class="activeTab === 'layanan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Layanan
                                        </button>
                                        {{-- Tab Penagihan --}}
                                        <button type="button" @click="activeTab = 'penagihan'"
                                                :class="activeTab === 'penagihan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Penagihan
                                        </button>
                                    </nav>
                                </div>

                                <div class="space-y-6">
                                    {{-- TAB 1: Informasi Perusahaan --}}
                                    <div x-show="activeTab === 'informasi_perusahaan'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nama_perusahaan" class="block text-sm text-gray-700 dark:text-gray-300">Nama Perusahaan</label>
                                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('nama_perusahaan') border-red-500 @enderror" value="{{ old('nama_perusahaan') }}" required>
                                            @error('nama_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="member_card" class="block text-sm text-gray-700 dark:text-gray-300">No. Member</label>
                                            <input type="text" name="member_card" id="member_card" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('member_card') border-red-500 @enderror" value="{{ old('member_card') }}">
                                            @error('member_card') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="jenis_usaha" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Usaha</label>
                                            <input type="text" name="jenis_usaha" id="jenis_usaha" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jenis_usaha') border-red-500 @enderror" value="{{ old('jenis_usaha') }}">
                                            @error('jenis_usaha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="account_manager" class="block text-sm text-gray-700 dark:text-gray-300">Account Manager</label>
                                            <input type="text" name="account_manager" id="account_manager" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('account_manager') border-red-500 @enderror" value="{{ old('account_manager') }}">
                                            @error('account_manager') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="no_hp" class="block text-sm text-gray-700 dark:text-gray-300">No. HP Perusahaan</label>
                                            <input type="text" name="no_hp" id="no_hp" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('no_hp') border-red-500 @enderror" value="{{ old('no_hp') }}" required>
                                            @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="nama_kontak" class="block text-sm text-gray-700 dark:text-gray-300">Nama Kontak Person</label>
                                            <input type="text" name="nama_kontak" id="nama_kontak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('nama_kontak') border-red-500 @enderror" value="{{ old('nama_kontak') }}">
                                            @error('nama_kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="telepon_perusahaan" class="block text-sm text-gray-700 dark:text-gray-300">Telepon Perusahaan</label>
                                            <input type="text" name="telepon_perusahaan" id="telepon_perusahaan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('telepon_perusahaan') border-red-500 @enderror" value="{{ old('telepon_perusahaan') }}">
                                            @error('telepon_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="fax" class="block text-sm text-gray-700 dark:text-gray-300">Fax</label>
                                            <input type="text" name="fax" id="fax" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('fax') border-red-500 @enderror" value="{{ old('fax') }}">
                                            @error('fax') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm text-gray-700 dark:text-gray-300">Email Utama Perusahaan</label>
                                            <input type="email" name="email" id="email" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="npwp" class="block text-sm text-gray-700 dark:text-gray-300">NPWP</label>
                                            <input type="text" name="npwp" id="npwp" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('npwp') border-red-500 @enderror" value="{{ old('npwp') }}">
                                            @error('npwp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="alamat" class="block text-sm text-gray-700 dark:text-gray-300">Alamat</label>
                                            <textarea name="alamat" id="alamat" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('alamat') border-red-500 @enderror" rows="2" required>{{ old('alamat') }}</textarea>
                                            @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="kode_pos" class="block text-sm text-gray-700 dark:text-gray-300">Kode Pos</label>
                                            <input type="text" name="kode_pos" id="kode_pos" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kode_pos') border-red-500 @enderror" value="{{ old('kode_pos') }}">
                                            @error('kode_pos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="kabupaten" class="block text-sm text-gray-700 dark:text-gray-300">Kabupaten</label>
                                            <input type="text" name="kabupaten" id="kabupaten" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kabupaten') border-red-500 @enderror" value="{{ old('kabupaten') }}">
                                            @error('kabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="kota" class="block text-sm text-gray-700 dark:text-gray-300">Kota</label>
                                            <input type="text" name="kota" id="kota" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kota') border-red-500 @enderror" value="{{ old('kota') }}">
                                            @error('kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="wilayah" class="block text-sm text-gray-700 dark:text-gray-300">Wilayah</label>
                                            <input type="text" name="wilayah" id="wilayah" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('wilayah') border-red-500 @enderror" value="{{ old('wilayah') }}">
                                            @error('wilayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="pop_id" class="block text-sm text-gray-700 dark:text-gray-300">POP</label>
                                            <select name="pop_id" id="pop_id" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('pop_id') border-red-500 @enderror">
                                                <option value="">Pilih POP</option>
                                                {{-- Melakukan loop untuk setiap data pop --}}
                                                @foreach($pops as $pop)
                                                    <option value="{{ $pop->id }}" {{ old('pop_id') == $pop->id ? 'selected' : '' }}>{{ $pop->nama_pop }}</option>
                                                @endforeach
                                            </select>
                                            @error('pop_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="reseller" id="reseller" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('reseller') ? 'checked' : '' }}>
                                            <label for="reseller" class="text-sm text-gray-700 dark:text-gray-300">Reseller</label>
                                            @error('reseller') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    {{-- TAB 2: Layanan --}}
                                    <div x-show="activeTab === 'layanan'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="homepass" class="block text-sm text-gray-700 dark:text-gray-300">Homepass</label>
                                            <input type="text" name="homepass" id="homepass" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('homepass') border-red-500 @enderror" value="{{ old('homepass') }}">
                                            @error('homepass') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="jenis_layanan" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Layanan</label>
                                            <input type="text" name="jenis_layanan" id="jenis_layanan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jenis_layanan') border-red-500 @enderror" value="{{ old('jenis_layanan') }}">
                                            @error('jenis_layanan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="mulai_kontrak" class="block text-sm text-gray-700 dark:text-gray-300">Mulai Kontrak</label>
                                            <input type="date" name="mulai_kontrak" id="mulai_kontrak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('mulai_kontrak') border-red-500 @enderror" value="{{ old('mulai_kontrak') }}">
                                            @error('mulai_kontrak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="selesai_kontrak" class="block text-sm text-gray-700 dark:text-gray-300">Selesai Kontrak</label>
                                            <input type="date" name="selesai_kontrak" id="selesai_kontrak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('selesai_kontrak') border-red-500 @enderror" value="{{ old('selesai_kontrak') }}">
                                            @error('selesai_kontrak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="perjanjian_trial" id="perjanjian_trial" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('perjanjian_trial') ? 'checked' : '' }}>
                                            <label for="perjanjian_trial" class="text-sm text-gray-700 dark:text-gray-300">Perjanjian Trial</label>
                                            @error('perjanjian_trial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="pembelian_modem" id="pembelian_modem" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('pembelian_modem') ? 'checked' : '' }}>
                                            <label for="pembelian_modem" class="text-sm text-gray-700 dark:text-gray-300">Pembelian Modem</label>
                                            @error('pembelian_modem') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="email_alternatif_1" class="block text-sm text-gray-700 dark:text-gray-300">Email Alternatif 1</label>
                                            <input type="email" name="email_alternatif_1" id="email_alternatif_1" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email_alternatif_1') border-red-500 @enderror" value="{{ old('email_alternatif_1') }}">
                                            @error('email_alternatif_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="email_alternatif_2" class="block text-sm text-gray-700 dark:text-gray-300">Email Alternatif 2</label>
                                            <input type="email" name="email_alternatif_2" id="email_alternatif_2" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email_alternatif_2') border-red-500 @enderror" value="{{ old('email_alternatif_2') }}">
                                            @error('email_alternatif_2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="jumlah_tv_kabel" class="block text-sm text-gray-700 dark:text-gray-300">Jumlah TV Kabel</label>
                                            <input type="number" name="jumlah_tv_kabel" id="jumlah_tv_kabel" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jumlah_tv_kabel') border-red-500 @enderror" min="0" value="{{ old('jumlah_tv_kabel', 0) }}">
                                            @error('jumlah_tv_kabel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    {{-- TAB 3: Penagihan --}}
                                    <div x-show="activeTab === 'penagihan'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="kontak_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kontak Penagihan</label>
                                            <input type="text" name="kontak_penagihan" id="kontak_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kontak_penagihan') border-red-500 @enderror" value="{{ old('kontak_penagihan') }}">
                                            @error('kontak_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="alamat_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Alamat Penagihan</label>
                                            <textarea name="alamat_penagihan" id="alamat_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('alamat_penagihan') border-red-500 @enderror" rows="2">{{ old('alamat_penagihan') }}</textarea>
                                            @error('alamat_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="kode_pos_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kode Pos Penagihan</label>
                                            <input type="text" name="kode_pos_penagihan" id="kode_pos_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kode_pos_penagihan') border-red-500 @enderror" value="{{ old('kode_pos_penagihan') }}">
                                            @error('kode_pos_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="kabupaten_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kabupaten Penagihan</label>
                                            <input type="text" name="kabupaten_penagihan" id="kabupaten_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kabupaten_penagihan') border-red-500 @enderror" value="{{ old('kabupaten_penagihan') }}">
                                            @error('kabupaten_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="kota_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kota Penagihan</label>
                                            <input type="text" name="kota_penagihan" id="kota_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kota_penagihan') border-red-500 @enderror" value="{{ old('kota_penagihan') }}">
                                            @error('kota_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="no_hp_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">No. HP Penagihan</label>
                                            <input type="text" name="no_hp_penagihan" id="no_hp_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('no_hp_penagihan') border-red-500 @enderror" value="{{ old('no_hp_penagihan') }}">
                                            @error('no_hp_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="telepon_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Telepon Penagihan</label>
                                            <input type="text" name="telepon_penagihan" id="telepon_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('telepon_penagihan') border-red-500 @enderror" value="{{ old('telepon_penagihan') }}">
                                            @error('telepon_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="fax_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Fax Penagihan</label>
                                            <input type="text" name="fax_penagihan" id="fax_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('fax_penagihan') border-red-500 @enderror" value="{{ old('fax_penagihan') }}">
                                            @error('fax_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="email_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Email Penagihan</label>
                                            <input type="email" name="email_penagihan" id="email_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email_penagihan') border-red-500 @enderror" value="{{ old('email_penagihan') }}">
                                            @error('email_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="cara_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Cara Pembayaran</label>
                                            <input type="text" name="cara_pembayaran" id="cara_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('cara_pembayaran') border-red-500 @enderror" value="{{ old('cara_pembayaran') }}">
                                            @error('cara_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="waktu_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Waktu Pembayaran</label>
                                            <input type="text" name="waktu_pembayaran" id="waktu_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('waktu_pembayaran') border-red-500 @enderror" placeholder="Misal: Setiap tanggal 5" value="{{ old('waktu_pembayaran') }}">
                                            @error('waktu_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="mata_uang" class="block text-sm text-gray-700 dark:text-gray-300">Mata Uang</label>
                                            {{-- PERUBAHAN: Field mata_uang menjadi readonly input --}}
                                            <input type="text" name="mata_uang" id="mata_uang" class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white cursor-not-allowed" value="IDR" readonly>
                                            @error('mata_uang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="biaya_reguler" class="block text-sm text-gray-700 dark:text-gray-300">Biaya Reguler</label>
                                            <input type="number" step="0.01" name="biaya_reguler" id="biaya_reguler" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('biaya_reguler') border-red-500 @enderror" value="{{ old('biaya_reguler') }}">
                                            @error('biaya_reguler') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="invoice_instalasi" id="invoice_instalasi" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('invoice_instalasi') ? 'checked' : '' }}>
                                            <label for="invoice_instalasi" class="text-sm text-gray-700 dark:text-gray-300">Invoice Instalasi Dibuat</label>
                                            @error('invoice_instalasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="invoice_reguler" id="invoice_reguler" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('invoice_reguler') ? 'checked' : '' }}>
                                            <label for="invoice_reguler" class="text-sm text-gray-700 dark:text-gray-300">Invoice Reguler Dibuat</label>
                                            @error('invoice_reguler') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="kenakan_ppn" id="kenakan_ppn" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('kenakan_ppn') ? 'checked' : '' }}>
                                            <label for="kenakan_ppn" class="text-sm text-gray-700 dark:text-gray-300">Kenakan PPN</label>
                                            @error('kenakan_ppn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="keterangan" class="block text-sm text-gray-700 dark:text-gray-300">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('keterangan') border-red-500 @enderror" rows="2">{{ old('keterangan') }}</textarea>
                                            @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-2">
                                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white/80">
                            <tr>
                                <th class="px-4 py-3">No.</th>
                                <th class="px-4 py-3">No. Member</th>
                                <th class="px-4 py-3">Nama Perusahaan</th>
                                <th class="px-4 py-3">Jenis Usaha</th>
                                <th class="px-4 py-3">Account Manager</th>
                                <th class="px-4 py-3">No. HP</th>
                                <th class="px-4 py-3">Layanan</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pelanggan as $index => $p)
                                <tr>
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">{{ $p->member_card }}</td>
                                    <td class="px-4 py-3">{{ $p->nama_perusahaan }}</td>
                                    <td class="px-4 py-3">{{ $p->jenis_usaha }}</td>
                                    <td class="px-4 py-3">{{ $p->account_manager }}</td>
                                    <td class="px-4 py-3">{{ $p->no_hp }}</td>
                                    <td class="px-4 py-3">
                                        @if($p->layanan->isNotEmpty())
                                            {{ $p->layanan->first()->jenis_layanan }}
                                        @else
                                            Belum ada layanan
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 flex gap-2">
                                        {{-- Tombol Detail dengan ikon saja --}}
                                        <a href="{{ route('admin.pelanggan.show', ['id' => $p->id, 'type' => 'perusahaan']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                                           title="Cek Detail">
                                            <i class="fas fa-eye"></i>
                                            <span class="sr-only">Cek Detail</span> {{-- Untuk aksesibilitas --}}
                                        </a>
                                        {{-- Tombol Edit dengan ikon saja --}}
                                        <a href="{{ route('admin.pelanggan.edit', ['id' => $p->id, 'type' => 'perusahaan']) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span class="sr-only">Edit</span> {{-- Untuk aksesibilitas --}}
                                        </a>
                                        {{-- Tombol Hapus dengan ikon saja --}}
                                        <form action="{{ route('admin.pelanggan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Hapus</span> {{-- Untuk aksesibilitas --}}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada data pelanggan perusahaan.
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

@push('scripts')
{{-- Pastikan Alpine.js dimuat, bisa di layout utama atau di sini --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Script untuk membuka modal jika ada error validasi --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any() || session('error'))
            // Buka modal jika ada error validasi atau pesan error dari session
            document.querySelector('[x-data="{ open: false }"]')._x_dataStack[0].open = true;

            // Jika ada error pada tab Layanan atau Penagihan, pindah ke tab tersebut
            @if ($errors->hasAny(['homepass', 'jenis_layanan', 'mulai_kontrak', 'selesai_kontrak', 'perjanjian_trial', 'email_alternatif_1', 'email_alternatif_2', 'pembelian_modem', 'jumlah_tv_kabel']))
                document.querySelector('[x-data="{ activeTab: \'informasi_perusahaan\' }"]')._x_dataStack[0].activeTab = 'layanan';
            @elseif ($errors->hasAny(['kontak_penagihan', 'alamat_penagihan', 'kode_pos_penagihan', 'kabupaten_penagihan', 'kota_penagihan', 'no_hp_penagihan', 'telepon_penagihan', 'fax_penagihan', 'email_penagihan', 'cara_pembayaran', 'waktu_pembayaran', 'invoice_instalasi', 'invoice_reguler', 'mata_uang', 'biaya_reguler', 'kenakan_ppn', 'keterangan']))
                document.querySelector('[x-data="{ activeTab: \'informasi_perusahaan\' }"]')._x_dataStack[0].activeTab = 'penagihan';
            @endif
        @endif
    });
</script>
@endpush
