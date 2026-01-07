@extends('backend.layouts.app')

@section('title', 'Manajemen Layanan Induk')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Manajemen Layanan Induk</h1>
            <div x-data="{ open: false }">
                <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Layanan Induk</span>
                </button>

                {{-- Modal Tambah Layanan Induk --}}
                <div x-show="open || ('{{ session('modal_open') }}' === 'add_layanan_induk_error')"
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
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Layanan Induk Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        {{-- Display validation errors for modal --}}
                        @if ($errors->any() && session('modal_open') === 'add_layanan_induk_error')
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
                        <form action="{{ route('admin.layanan.induk.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="nama_layanan_induk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Layanan Induk</label>
                                <input type="text" name="nama_layanan_induk" id="nama_layanan_induk" value="{{ old('nama_layanan_induk') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nama_layanan_induk') border-red-500 @enderror">
                                @error('nama_layanan_induk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

        {{-- Pesan Status --}}
        @if (session('success') && session('modal_open') !== 'add_layanan_induk_error')
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

        {{-- Form Pencarian --}}
        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('admin.layanan.induk.index') }}">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Cari layanan induk..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('admin.layanan.induk.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Reset</a>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white/80">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Nama Layanan Induk</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($layananInduks as $layananInduk)
                                <tr>
                                    <td class="px-4 py-3">{{ $layananInduk->id }}</td>
                                    <td class="px-4 py-3">{{ $layananInduk->nama_layanan_induk }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.layanan.induk.edit', $layananInduk->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span class="sr-only">Edit</span>
                                        </a>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.layanan.induk.destroy', $layananInduk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Hapus</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada data Layanan Induk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- Pagination --}}
        <div class="mt-4">
            {{ $layananInduks->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@endsection