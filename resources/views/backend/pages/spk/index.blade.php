@extends('backend.layouts.app')

@section('title', 'Manajemen SPK')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Manajemen SPK</h1>
        </div>
        
        {{-- Pesan Status --}}
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

        {{-- Filter Form --}}
        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('admin.spk.index') }}">
                <div class="flex flex-wrap items-center gap-3">
                    <select name="status" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white min-w-[200px]">
                        <option value="">-- Semua Status --</option>
                        <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="dalam_pengerjaan" {{ request('status') == 'dalam_pengerjaan' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                        <option value="reschedule" {{ request('status') == 'reschedule' ? 'selected' : '' }}>Reschedule</option>
                        <option value="selesai_sebagian" {{ request('status') == 'selesai_sebagian' ? 'selected' : '' }}>Selesai Sebagian</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <input type="text" name="search" class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Cari nomor SPK, nama pelanggan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('admin.spk.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Reset</a>
                    <a href="{{ route('admin.spk.export', request()->query()) }}" class="btn btn-success inline-flex items-center gap-2">
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
                                <th class="px-4 py-3">Nomor SPK</th>
                                <th class="px-4 py-3">Pelanggan</th>
                                <th class="px-4 py-3">Layanan</th>
                                <th class="px-4 py-3">Tipe SPK</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Rencana Pengerjaan</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($spk as $s)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.spk.show', urlencode($s->nomor_spk)) }}" class="text-blue-600 hover:underline" title="Lihat Detail SPK">
                                        {{ $s->nomor_spk }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($s->pelanggan)
                                        {{ $s->pelanggan->nama_lengkap ?: $s->pelanggan->nama_perusahaan ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $s->layananInduk->nama_layanan_induk ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $s->tipe }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $s->status)) }}</td>
                                <td class="px-4 py-3">{{ $s->rencana_pengerjaan ? $s->rencana_pengerjaan->format('d-m-Y') : '-' }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.spk.edit', urlencode($s->nomor_spk)) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Edit</span>
                                    </a>
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.spk.destroy', urlencode($s->nomor_spk)) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SPK ini?');">
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
                                <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Belum ada data SPK.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($spk->hasPages())
                <div class="card-footer p-4">
                    {{ $spk->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
