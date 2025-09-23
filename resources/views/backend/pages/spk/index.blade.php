@extends('backend.layouts.app')

@section('title', 'Manajemen SPK')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
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
                                    @if ($s->pelanggan->tipe == 'personal')
                                        {{ $s->pelanggan->nama_lengkap ?? '-' }}
                                    @else
                                        {{ $s->pelanggan->nama_perusahaan ?? '-' }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $s->layananInduk->nama_layanan_induk ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $s->tipe }}</td>
                                <td class="px-4 py-3">{{ ucfirst($s->status) }}</td>
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
        </div>
    </div>
</div>
@endsection