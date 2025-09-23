@extends('backend.layouts.app')

@section('title', 'Edit Keluhan')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90 mb-4">Edit Keluhan</h2>

        <form action="{{ route('admin.keluhan.update', $keluhan->id_keluhan) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="layanan_induk_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Layanan</label>
                <select name="layanan_induk_id" id="layanan_induk_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($layananInduks as $l)
                        <option value="{{ $l->id }}" {{ $keluhan->layanan_induk_id == $l->id ? 'selected' : '' }}>
                            {{ $l->nama_layanan_induk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="pelanggan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelanggan</label>
                <select name="pelanggan_id" id="pelanggan_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}" {{ $keluhan->pelanggan_id == $p->id ? 'selected' : '' }}>
                            {{ ($p->tipe == 'personal') ? ($p->nama_lengkap ?? '-') : ($p->nama_perusahaan ?? '-') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Tambahan input jenis_spk --}}
            <div>
                <label for="jenis_spk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buat SPK ?</label>
                <select name="jenis_spk" id="jenis_spk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="Tidak" {{ $keluhan->jenis_spk == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    <option value="SPK OSP" {{ $keluhan->jenis_spk == 'SPK OSP' ? 'selected' : '' }}>SPK OSP</option>
                    <option value="SPK VOIP" {{ $keluhan->jenis_spk == 'SPK VOIP' ? 'selected' : '' }}>SPK VOIP</option>
                    <option value="SPK TS" {{ $keluhan->jenis_spk == 'SPK TS' ? 'selected' : '' }}>SPK TS</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tujuan</label>
                <select name="tujuan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach(['Technical Support','Maintenance Cable','Maintenance Wireless','E-Gov Kota','E-Gov Propinsi','NOC','TV Kabel','Helpdesk','Admin CLEON','Support CLEON','SYS Admin CLEON'] as $t)
                        <option value="{{ $t }}" {{ $keluhan->tujuan == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioritas</label>
                <select name="prioritas" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="low" {{ $keluhan->prioritas == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ $keluhan->prioritas == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ $keluhan->prioritas == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keluhan 1</label>
                <input type="text" name="keluhan1" value="{{ $keluhan->keluhan1 }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keluhan 2</label>
                <input type="text" name="keluhan2" value="{{ $keluhan->keluhan2 }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Input</label>
                <input type="date" name="tanggal_input" value="{{ $keluhan->tanggal_input->format('Y-m-d') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Via</label>
                <select name="via" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach(['Datang','Telpon/Fax','Email','SMS/WA/BBM/LINE'] as $v)
                        <option value="{{ $v }}" {{ $keluhan->via == $v ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                <textarea name="deskripsi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $keluhan->deskripsi }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penyelesaian</label>
                <textarea name="penyelesaian" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $keluhan->penyelesaian }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Disampaikan Oleh</label>
                <input type="text" name="disampaikan_oleh" value="{{ $keluhan->disampaikan_oleh }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sumber</label>
                <input type="text" name="sumber" value="{{ $keluhan->sumber }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Update</button>
                <a href="{{ route('admin.keluhan.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection