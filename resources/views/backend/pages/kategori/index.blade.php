@extends('backend.layouts.app')

@section('title')
    {{ __('Kategori Pelanggan') }} | {{ config('app.name') }}
@endsection

@section('admin-content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div x-data="{ pageName: '{{ __('Kategori Pelanggan') }}' }">
        <!-- Page Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">{{ __('Kategori Pelanggan') }}</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400" href="{{ route('admin.dashboard') }}">
                            {{ __('Home') }}
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName">{{ __('Kategori Pelanggan') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Form Tambah Kategori -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Tambah Kategori Pelanggan') }}</h3>
            </div>

            <div class="px-5 py-4 sm:px-6 sm:py-5 space-y-4">
                @include('backend.layouts.partials.messages')

                <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-1">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Personal</span>
                            <div class="max-h-40 overflow-y-auto border rounded-lg p-2 dark:border-gray-700">
                                @foreach($availableFields['personal'] as $key => $label)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 mb-1">
                                        <input type="checkbox" name="personal_fields[]" value="{{ $key }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               @if(collect(old('personal_fields', []))->contains($key)) checked @endif>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Perusahaan</span>
                            <div class="max-h-40 overflow-y-auto border rounded-lg p-2 dark:border-gray-700">
                                @foreach($availableFields['perusahaan'] as $key => $label)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 mb-1">
                                        <input type="checkbox" name="perusahaan_fields[]" value="{{ $key }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               @if(collect(old('perusahaan_fields', []))->contains($key)) checked @endif>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="reset" class="btn-default">{{ __('Reset') }}</button>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-save mr-1"></i>
                            <span>{{ __('Simpan') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Kategori -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Daftar Kategori Pelanggan') }}</h3>

                <form action="{{ route('admin.kategori.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama kategori..."
                           class="w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white text-sm">
                    <button type="submit" class="btn-default text-sm">{{ __('Cari') }}</button>
                </form>
            </div>

            <div class="space-y-3 border-t border-gray-100 dark:border-gray-800 overflow-x-auto">
                <table class="w-full text-sm dark:text-gray-300">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('No') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nama Kategori') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Field Personal') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Field Perusahaan') }}</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $item)
                            <tr class="{{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-800' : '' }}">
                                <td class="px-5 py-3">{{ $kategori->firstItem() + $index }}</td>
                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $item->nama }}</td>
                                <td class="px-5 py-3">
                                    @php $pf = (array) ($item->personal_fields ?? []); @endphp
                                    @if(count($pf))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($pf as $field)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ $availableFields['personal'][$field] ?? $field }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @php $pf2 = (array) ($item->perusahaan_fields ?? []); @endphp
                                    @if(count($pf2))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($pf2 as $field)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $availableFields['perusahaan'][$field] ?? $field }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-center items-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.kategori.edit', $item->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span class="sr-only">Edit</span>
                                        </a>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                                <span class="sr-only">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('Belum ada data kategori.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-5 py-4">
                    {{ $kategori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
