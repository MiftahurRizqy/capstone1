@extends('backend.layouts.app')

@section('title', 'Edit Data Pelanggan')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Edit Data Pelanggan</h1>
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg shadow dark:bg-gray-500 dark:hover:bg-gray-600 transition-all duration-200">
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

            <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST" class="p-0">
                @csrf
                @method('PUT') {{-- Penting untuk method update --}}

                {{-- Hidden field untuk tipe pelanggan (pastikan tidak berubah saat edit) --}}
                <input type="hidden" name="tipe" value="{{ $pelanggan->tipe }}">

                <div x-data="{ 
                    activeTab: 'informasi_pelanggan',
                    selectedKategoriId: '{{ old('kategori_pelanggan_id', $pelanggan->kategori_pelanggan_id) }}',
                    kategoriData: {{ $kategoriDataForAlpine->toJson() }}
                }">
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button type="button" @click="activeTab = 'informasi_pelanggan'"
                                :class="activeTab === 'informasi_pelanggan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Informasi Pelanggan
                            </button>
                            <button type="button" @click="activeTab = 'layanan'"
                                :class="activeTab === 'layanan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Layanan
                            </button>
                            <button type="button" @click="activeTab = 'penagihan'"
                                :class="activeTab === 'penagihan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Penagihan
                            </button>
                        </nav>
                    </div>

                    <div class="space-y-6">
                        {{-- TAB 1: Informasi Pelanggan --}}
                        <div x-show="activeTab === 'informasi_pelanggan'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- KATEGORI PELANGGAN --}}
                            <div class="md:col-span-2">
                                <label for="kategori_pelanggan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori Pelanggan <span class="text-red-500">*</span></label>
                                <select name="kategori_pelanggan_id" id="kategori_pelanggan_id"
                                    class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kategori_pelanggan_id') border-red-500 @enderror"
                                    x-model="selectedKategoriId" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriPelanggan as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_pelanggan_id', $pelanggan->kategori_pelanggan_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_pelanggan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- FIELD DINAMIS UNTUK PERSONAL --}}
                            <template x-if="
                                selectedKategoriId &&
                                kategoriData[selectedKategoriId] &&
                                (
                                    (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'personal') ||
                                    (kategoriData[selectedKategoriId].personal_fields && kategoriData[selectedKategoriId].personal_fields.length > 0)
                                )
                            ">
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-l-4 border-green-500 pl-4 py-2">

                                    {{-- Nama Lengkap --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'personal') ||
                                        (kategoriData[selectedKategoriId].personal_fields || []).includes('nama_lengkap')
                                    ">
                                        <div>
                                            <label for="nama_lengkap" class="block text-sm text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('nama_lengkap') border-red-500 @enderror" value="{{ old('nama_lengkap', $pelanggan->nama_lengkap) }}">
                                            @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Tanggal Lahir --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'personal') ||
                                        (kategoriData[selectedKategoriId].personal_fields || []).includes('tanggal_lahir')
                                    ">
                                        <div>
                                            <label for="tanggal_lahir" class="block text-sm text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('tanggal_lahir') border-red-500 @enderror" value="{{ old('tanggal_lahir', $pelanggan->tanggal_lahir?->format('Y-m-d')) }}">
                                            @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Jenis Kelamin --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'personal') ||
                                        (kategoriData[selectedKategoriId].personal_fields || []).includes('jenis_kelamin')
                                    ">
                                        <div>
                                            <label for="jenis_kelamin" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jenis_kelamin') border-red-500 @enderror">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ old('jenis_kelamin', $pelanggan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ old('jenis_kelamin', $pelanggan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Pekerjaan --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'personal') ||
                                        (kategoriData[selectedKategoriId].personal_fields || []).includes('pekerjaan')
                                    ">
                                        <div>
                                            <label for="pekerjaan" class="block text-sm text-gray-700 dark:text-gray-300">Pekerjaan</label>
                                            <input type="text" name="pekerjaan" id="pekerjaan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('pekerjaan') border-red-500 @enderror" value="{{ old('pekerjaan', $pelanggan->pekerjaan) }}">
                                            @error('pekerjaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                </div>
                            </template>

                            {{-- FIELD DINAMIS UNTUK PERUSAHAAN --}}
                            <template x-if="
                                selectedKategoriId &&
                                kategoriData[selectedKategoriId] &&
                                (
                                    (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                    (kategoriData[selectedKategoriId].perusahaan_fields && kategoriData[selectedKategoriId].perusahaan_fields.length > 0)
                                )
                            ">
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-l-4 border-yellow-500 pl-4 py-2">

                                    {{-- Nama Perusahaan --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                        (kategoriData[selectedKategoriId].perusahaan_fields || []).includes('nama_perusahaan')
                                    ">
                                        <div>
                                            <label for="nama_perusahaan" class="block text-sm text-gray-700 dark:text-gray-300">Nama Perusahaan <span class="text-red-500">*</span></label>
                                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('nama_perusahaan') border-red-500 @enderror" value="{{ old('nama_perusahaan', $pelanggan->nama_perusahaan) }}">
                                            @error('nama_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Jenis Usaha --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                        (kategoriData[selectedKategoriId].perusahaan_fields || []).includes('jenis_usaha')
                                    ">
                                        <div>
                                            <label for="jenis_usaha" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Usaha</label>
                                            <input type="text" name="jenis_usaha" id="jenis_usaha" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jenis_usaha') border-red-500 @enderror" value="{{ old('jenis_usaha', $pelanggan->jenis_usaha) }}">
                                            @error('jenis_usaha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Account Manager --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                        (kategoriData[selectedKategoriId].perusahaan_fields || []).includes('account_manager')
                                    ">
                                        <div>
                                            <label for="account_manager" class="block text-sm text-gray-700 dark:text-gray-300">Account Manager</label>
                                            <input type="text" name="account_manager" id="account_manager" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('account_manager') border-red-500 @enderror" value="{{ old('account_manager', $pelanggan->account_manager) }}">
                                            @error('account_manager') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Telepon Perusahaan --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                        (kategoriData[selectedKategoriId].perusahaan_fields || []).includes('telepon_perusahaan')
                                    ">
                                        <div>
                                            <label for="telepon_perusahaan" class="block text-sm text-gray-700 dark:text-gray-300">Telepon Perusahaan</label>
                                            <input type="text" name="telepon_perusahaan" id="telepon_perusahaan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('telepon_perusahaan') border-red-500 @enderror" value="{{ old('telepon_perusahaan', $pelanggan->telepon_perusahaan) }}">
                                            @error('telepon_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- Fax --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                        (kategoriData[selectedKategoriId].perusahaan_fields || []).includes('fax')
                                    ">
                                        <div>
                                            <label for="fax" class="block text-sm text-gray-700 dark:text-gray-300">Fax</label>
                                            <input type="text" name="fax" id="fax" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('fax') border-red-500 @enderror" value="{{ old('fax', $pelanggan->fax) }}">
                                            @error('fax') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                    {{-- NPWP --}}
                                    <template x-if="
                                        (kategoriData[selectedKategoriId].nama && kategoriData[selectedKategoriId].nama.toLowerCase() === 'perusahaan') ||
                                        (kategoriData[selectedKategoriId].perusahaan_fields || []).includes('npwp')
                                    ">
                                        <div>
                                            <label for="npwp" class="block text-sm text-gray-700 dark:text-gray-300">NPWP</label>
                                            <input type="text" name="npwp" id="npwp" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('npwp') border-red-500 @enderror" value="{{ old('npwp', $pelanggan->npwp) }}">
                                            @error('npwp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </template>

                                </div>
                            </template>

                            {{-- FIELD UMUM --}}
                            <div class="md:col-span-2 border-t pt-4 mt-4 border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="member_card" class="block text-sm text-gray-700 dark:text-gray-300">Member Card</label>
                                    <input type="text" name="member_card" id="member_card" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('member_card') border-red-500 @enderror" value="{{ old('member_card', $pelanggan->member_card) }}">
                                    @error('member_card') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="no_hp" class="block text-sm text-gray-700 dark:text-gray-300">No. HP <span class="text-red-500">*</span></label>
                                    <input type="text" name="no_hp" id="no_hp" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('no_hp') border-red-500 @enderror" value="{{ old('no_hp', $pelanggan->no_hp) }}" required>
                                    @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm text-gray-700 dark:text-gray-300">Email Utama</label>
                                    <input type="email" name="email" id="email" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email') border-red-500 @enderror" value="{{ old('email', $pelanggan->email) }}">
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm text-gray-700 dark:text-gray-300">Alamat <span class="text-red-500">*</span></label>
                                    <textarea name="alamat" id="alamat" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('alamat') border-red-500 @enderror" rows="2" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="kode_pos" class="block text-sm text-gray-700 dark:text-gray-300">Kode Pos</label>
                                    <input type="text" name="kode_pos" id="kode_pos" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kode_pos') border-red-500 @enderror" value="{{ old('kode_pos', $pelanggan->kode_pos) }}">
                                    @error('kode_pos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="kabupaten" class="block text-sm text-gray-700 dark:text-gray-300">Kabupaten</label>
                                    <input type="text" name="kabupaten" id="kabupaten" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kabupaten') border-red-500 @enderror" value="{{ old('kabupaten', $pelanggan->kabupaten) }}">
                                    @error('kabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="kota" class="block text-sm text-gray-700 dark:text-gray-300">Kota</label>
                                    <input type="text" name="kota" id="kota" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kota') border-red-500 @enderror" value="{{ old('kota', $pelanggan->kota) }}">
                                    @error('kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="wilayah" class="block text-sm text-gray-700 dark:text-gray-300">Wilayah</label>
                                    <input type="text" name="wilayah" id="wilayah" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('wilayah') border-red-500 @enderror" value="{{ old('wilayah', $pelanggan->wilayah) }}">
                                    @error('wilayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="pop_id" class="block text-sm text-gray-700 dark:text-gray-300">POP</label>
                                    <select name="pop_id" id="pop_id" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('pop_id') border-red-500 @enderror">
                                        <option value="">Pilih POP</option>
                                        @foreach($pops as $pop)
                                            <option value="{{ $pop->id }}" {{ old('pop_id', $pelanggan->pop_id) == $pop->id ? 'selected' : '' }}>{{ $pop->nama_pop }}</option>
                                        @endforeach
                                    </select>
                                    @error('pop_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="tipe_identitas" class="block text-sm text-gray-700 dark:text-gray-300">Tipe Identitas</label>
                                    <select name="tipe_identitas" id="tipe_identitas" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('tipe_identitas') border-red-500 @enderror">
                                        <option value="">Pilih Tipe Identitas</option>
                                        <option value="KTP" {{ old('tipe_identitas', $pelanggan->tipe_identitas) == 'KTP' ? 'selected' : '' }}>KTP</option>
                                        <option value="SIM" {{ old('tipe_identitas', $pelanggan->tipe_identitas) == 'SIM' ? 'selected' : '' }}>SIM</option>
                                        <option value="Paspor" {{ old('tipe_identitas', $pelanggan->tipe_identitas) == 'Paspor' ? 'selected' : '' }}>Paspor</option>
                                    </select>
                                    @error('tipe_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="nomor_identitas" class="block text-sm text-gray-700 dark:text-gray-300">Nomor Identitas</label>
                                    <input type="text" name="nomor_identitas" id="nomor_identitas" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('nomor_identitas') border-red-500 @enderror" value="{{ old('nomor_identitas', $pelanggan->nomor_identitas) }}">
                                    @error('nomor_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="nama_kontak" class="block text-sm text-gray-700 dark:text-gray-300">Nama Kontak Lain</label>
                                    <input type="text" name="nama_kontak" id="nama_kontak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('nama_kontak') border-red-500 @enderror" value="{{ old('nama_kontak', $pelanggan->nama_kontak) }}">
                                    @error('nama_kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="reseller" value="0">
                                    <input type="checkbox" name="reseller" id="reseller" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('reseller', $pelanggan->reseller) ? 'checked' : '' }}>
                                    <label for="reseller" class="text-sm text-gray-700 dark:text-gray-300">Reseller</label>
                                    @error('reseller') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- TAB 2: Layanan --}}
                        <div x-show="activeTab === 'layanan'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="homepass" class="block text-sm text-gray-700 dark:text-gray-300">Homepass</label>
                                <input type="text" name="homepass" id="homepass" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('homepass') border-red-500 @enderror" value="{{ old('homepass', $pelanggan->layanan->first()?->homepass ?? '') }}">
                                @error('homepass') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="layanan_entry_id" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Layanan</label>
                                <select name="layanan_entry_id" id="layanan_entry_id" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('layanan_entry_id') border-red-500 @enderror">
                                    <option value="">Pilih Layanan</option>
                                    @foreach($layananEntries as $entry)
                                        <option value="{{ $entry->id }}" {{ old('layanan_entry_id', $pelanggan->layanan->first()?->layanan_entry_id ?? '') == $entry->id ? 'selected' : '' }}>{{ $entry->nama_paket }} ({{ $entry->kode }})</option>
                                    @endforeach
                                </select>
                                @error('layanan_entry_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="mulai_kontrak" class="block text-sm text-gray-700 dark:text-gray-300">Mulai Kontrak</label>
                                <input type="date" name="mulai_kontrak" id="mulai_kontrak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('mulai_kontrak') border-red-500 @enderror" value="{{ old('mulai_kontrak', $pelanggan->layanan->first()?->mulai_kontrak?->format('Y-m-d') ?? '') }}">
                                @error('mulai_kontrak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="selesai_kontrak" class="block text-sm text-gray-700 dark:text-gray-300">Selesai Kontrak</label>
                                <input type="date" name="selesai_kontrak" id="selesai_kontrak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('selesai_kontrak') border-red-500 @enderror" value="{{ old('selesai_kontrak', $pelanggan->layanan->first()?->selesai_kontrak?->format('Y-m-d') ?? '') }}">
                                @error('selesai_kontrak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="perjanjian_trial" value="0">
                                <input type="checkbox" name="perjanjian_trial" id="perjanjian_trial" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('perjanjian_trial', $pelanggan->layanan->first()?->perjanjian_trial ?? false) ? 'checked' : '' }}>
                                <label for="perjanjian_trial" class="text-sm text-gray-700 dark:text-gray-300">Perjanjian Trial</label>
                                @error('perjanjian_trial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="pembelian_modem" value="0">
                                <input type="checkbox" name="pembelian_modem" id="pembelian_modem" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('pembelian_modem', $pelanggan->layanan->first()?->pembelian_modem ?? false) ? 'checked' : '' }}>
                                <label for="pembelian_modem" class="text-sm text-gray-700 dark:text-gray-300">Pembelian Modem</label>
                                @error('pembelian_modem') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email_alternatif_1" class="block text-sm text-gray-700 dark:text-gray-300">Email Alternatif 1</label>
                                <input type="email" name="email_alternatif_1" id="email_alternatif_1" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email_alternatif_1') border-red-500 @enderror" value="{{ old('email_alternatif_1', $pelanggan->layanan->first()?->email_alternatif_1 ?? '') }}">
                                @error('email_alternatif_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email_alternatif_2" class="block text-sm text-gray-700 dark:text-gray-300">Email Alternatif 2</label>
                                <input type="email" name="email_alternatif_2" id="email_alternatif_2" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email_alternatif_2') border-red-500 @enderror" value="{{ old('email_alternatif_2', $pelanggan->layanan->first()?->email_alternatif_2 ?? '') }}">
                                @error('email_alternatif_2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="jumlah_tv_kabel" class="block text-sm text-gray-700 dark:text-gray-300">Jumlah TV Kabel</label>
                                <input type="number" name="jumlah_tv_kabel" id="jumlah_tv_kabel" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jumlah_tv_kabel') border-red-500 @enderror" min="0" value="{{ old('jumlah_tv_kabel', $pelanggan->layanan->first()?->jumlah_tv_kabel ?? 0) }}">
                                @error('jumlah_tv_kabel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- TAB 3: Penagihan --}}
                        <div x-show="activeTab === 'penagihan'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="kontak_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kontak Penagihan</label>
                                <input type="text" name="kontak_penagihan" id="kontak_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kontak_penagihan') border-red-500 @enderror" value="{{ old('kontak_penagihan', $pelanggan->penagihan->kontak_penagihan ?? '') }}">
                                @error('kontak_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="alamat_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Alamat Penagihan</label>
                                <textarea name="alamat_penagihan" id="alamat_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('alamat_penagihan') border-red-500 @enderror" rows="2">{{ old('alamat_penagihan', $pelanggan->penagihan->alamat_penagihan ?? '') }}</textarea>
                                @error('alamat_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="kode_pos_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kode Pos Penagihan</label>
                                <input type="text" name="kode_pos_penagihan" id="kode_pos_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kode_pos_penagihan') border-red-500 @enderror" value="{{ old('kode_pos_penagihan', $pelanggan->penagihan->kode_pos_penagihan ?? '') }}">
                                @error('kode_pos_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="kabupaten_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kabupaten Penagihan</label>
                                <input type="text" name="kabupaten_penagihan" id="kabupaten_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kabupaten_penagihan') border-red-500 @enderror" value="{{ old('kabupaten_penagihan', $pelanggan->penagihan->kabupaten_penagihan ?? '') }}">
                                @error('kabupaten_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="kota_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kota Penagihan</label>
                                <input type="text" name="kota_penagihan" id="kota_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('kota_penagihan') border-red-500 @enderror" value="{{ old('kota_penagihan', $pelanggan->penagihan->kota_penagihan ?? '') }}">
                                @error('kota_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="no_hp_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">No. HP Penagihan</label>
                                <input type="text" name="no_hp_penagihan" id="no_hp_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('no_hp_penagihan') border-red-500 @enderror" value="{{ old('no_hp_penagihan', $pelanggan->penagihan->no_hp_penagihan ?? '') }}">
                                @error('no_hp_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="telepon_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Telepon Penagihan</label>
                                <input type="text" name="telepon_penagihan" id="telepon_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('telepon_penagihan') border-red-500 @enderror" value="{{ old('telepon_penagihan', $pelanggan->penagihan->telepon_penagihan ?? '') }}">
                                @error('telepon_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="fax_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Fax Penagihan</label>
                                <input type="text" name="fax_penagihan" id="fax_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('fax_penagihan') border-red-500 @enderror" value="{{ old('fax_penagihan', $pelanggan->penagihan->fax_penagihan ?? '') }}">
                                @error('fax_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Email Penagihan</label>
                                <input type="email" name="email_penagihan" id="email_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('email_penagihan') border-red-500 @enderror" value="{{ old('email_penagihan', $pelanggan->penagihan->email_penagihan ?? '') }}">
                                @error('email_penagihan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="cara_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Cara Pembayaran</label>
                                <input type="text" name="cara_pembayaran" id="cara_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('cara_pembayaran') border-red-500 @enderror" value="{{ old('cara_pembayaran', $pelanggan->penagihan->cara_pembayaran ?? '') }}">
                                @error('cara_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="waktu_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Waktu Pembayaran</label>
                                <input type="text" name="waktu_pembayaran" id="waktu_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('waktu_pembayaran') border-red-500 @enderror" placeholder="Misal: Setiap tanggal 5" value="{{ old('waktu_pembayaran', $pelanggan->penagihan->waktu_pembayaran ?? '') }}">
                                @error('waktu_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="invoice_instalasi" class="block text-sm text-gray-700 dark:text-gray-300">Invoice Instalasi</label>
                                <input type="text" name="invoice_instalasi" id="invoice_instalasi" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('invoice_instalasi') border-red-500 @enderror" value="{{ old('invoice_instalasi', $pelanggan->penagihan->invoice_instalasi ?? '') }}">
                                @error('invoice_instalasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="invoice_reguler" class="block text-sm text-gray-700 dark:text-gray-300">Invoice Reguler</label>
                                <input type="text" name="invoice_reguler" id="invoice_reguler" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('invoice_reguler') border-red-500 @enderror" value="{{ old('invoice_reguler', $pelanggan->penagihan->invoice_reguler ?? '') }}">
                                @error('invoice_reguler') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="mata_uang" class="block text-sm text-gray-700 dark:text-gray-300">Mata Uang</label>
                                <input type="text" name="mata_uang" id="mata_uang" class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white cursor-not-allowed @error('mata_uang') border-red-500 @enderror" value="IDR" readonly>
                                @error('mata_uang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="biaya_reguler" class="block text-sm text-gray-700 dark:text-gray-300">Biaya Reguler</label>
                                <input type="number" step="0.01" name="biaya_reguler" id="biaya_reguler" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('biaya_reguler') border-red-500 @enderror" value="{{ old('biaya_reguler', $pelanggan->penagihan->biaya_reguler ?? '') }}">
                                @error('biaya_reguler') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="kenakan_ppn" value="0">
                                <input type="checkbox" name="kenakan_ppn" id="kenakan_ppn" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded" {{ old('kenakan_ppn', $pelanggan->penagihan->kenakan_ppn ?? false) ? 'checked' : '' }}>
                                <label for="kenakan_ppn" class="text-sm text-gray-700 dark:text-gray-300">Kenakan PPN</label>
                                @error('kenakan_ppn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-sm text-gray-700 dark:text-gray-300">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('keterangan') border-red-500 @enderror" rows="2">{{ old('keterangan', $pelanggan->penagihan->keterangan ?? '') }}</textarea>
                                @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->any())
        // Perbaikan: Ubah kondisi pengecekan error
        @if($errors->hasAny(['homepass', 'layanan_entry_id', 'mulai_kontrak', 'selesai_kontrak', 'perjanjian_trial', 'email_alternatif_1', 'email_alternatif_2', 'pembelian_modem', 'jumlah_tv_kabel']))
            document.querySelector('[x-data]')._x_dataStack[0].activeTab = 'layanan';
        @elseif($errors->hasAny(['kontak_penagihan', 'alamat_penagihan', 'kode_pos_penagihan', 'kabupaten_penagihan', 'kota_penagihan', 'no_hp_penagihan', 'telepon_penagihan', 'fax_penagihan', 'email_penagihan', 'cara_pembayaran', 'waktu_pembayaran', 'invoice_instalasi', 'invoice_reguler', 'mata_uang', 'biaya_reguler', 'kenakan_ppn', 'keterangan']))
            document.querySelector('[x-data]')._x_dataStack[0].activeTab = 'penagihan';
        @endif
        @endif
    });
</script>
@endpush
@endpush