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
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    style="display: none;">
                    <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md shadow-lg p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Keluhan Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
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
                                <select name="pelanggan_id" id="pelanggan_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('pelanggan_id') border-red-500 @enderror">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($pelanggan as $p)
                                        <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                            {{ ($p->tipe == 'personal') ? ($p->nama_lengkap ?? '-') : ($p->nama_perusahaan ?? '-') }}
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

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white/80">
                            <tr>
                                <th class="px-4 py-3">ID</th>
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
                            @forelse($keluhan as $k)
                            <tr>
                                <td class="px-4 py-3">{{ $k->id_keluhan }}</td>
                                <td class="px-4 py-3">{{ $k->layananInduk->nama_layanan_induk ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($k->pelanggan->tipe == 'personal')
                                        {{ $k->pelanggan->nama_lengkap ?? '-' }}
                                    @else
                                        {{ $k->pelanggan->nama_perusahaan ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $k->jenis_spk }}</td>
                                <td class="px-4 py-3">{{ $k->tujuan }}</td>
                                <td class="px-4 py-3">{{ ucfirst($k->prioritas) }}</td>
                                <td class="px-4 py-3">{{ $k->keluhan1 }}</td>
                                <td class="px-4 py-3">{{ $k->keluhan2 }}</td>
                                <td class="px-4 py-3">{{ $k->via }}</td>
                                <td class="px-4 py-3">{{ $k->tanggal_input->format('d-m-Y H:i') }}</td>
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
@endsection