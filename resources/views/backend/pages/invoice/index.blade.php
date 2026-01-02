@extends('backend.layouts.app')

@section('title', 'Daftar Invoice')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Daftar Invoice</h1>
            <div x-data="{ open: {{ ($errors->any() || session('error')) ? 'true' : 'false' }} }">
                @can('invoice.create')
                <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600">
                    <i class="fas fa-plus"></i>
                    <span>Buat Invoice</span>
                </button>
                @endcan

                {{-- Modal --}}
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
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Buat Invoice Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        {{-- Tampilan error validasi di dalam modal --}}
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

                        <form action="{{ route('admin.invoice.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="pelanggan_id" class="block text-sm text-gray-700 dark:text-gray-300">Pelanggan</label>
                                    <select name="pelanggan_id" id="pelanggan_id" class="select2-pelanggan w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('pelanggan_id') border-red-500 @enderror">
                                        <option value="">Pilih Pelanggan</option>
                                        @foreach($pelanggan as $p)
                                            <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_lengkap ?: $p->nama_perusahaan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pelanggan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="layanan_id" class="block text-sm text-gray-700 dark:text-gray-300">Layanan</label>
                                    <select name="layanan_id" id="layanan_id" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('layanan_id') border-red-500 @enderror">
                                        <option value="">Pilih Pelanggan Dulu</option>
                                    </select>
                                    @error('layanan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="tipe" class="block text-sm text-gray-700 dark:text-gray-300">Tipe Invoice</label>
                                    <select name="tipe" id="tipe" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('tipe') border-red-500 @enderror">
                                        <option value="Reguler" {{ old('tipe') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                        <option value="Instalasi" {{ old('tipe') == 'Instalasi' ? 'selected' : '' }}>Instalasi</option>
                                        <option value="Deposit" {{ old('tipe') == 'Deposit' ? 'selected' : '' }}>Deposit</option>
                                        <option value="Droping" {{ old('tipe') == 'Droping' ? 'selected' : '' }}>Droping</option>
                                        <option value="lain-lain" {{ old('tipe') == 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                    </select>
                                    @error('tipe') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="jatuh_tempo" class="block text-sm text-gray-700 dark:text-gray-300">Jatuh Tempo</label>
                                    <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jatuh_tempo') border-red-500 @enderror" value="{{ old('jatuh_tempo') }}">
                                    @error('jatuh_tempo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="total_biaya" class="block text-sm text-gray-700 dark:text-gray-300">Total Biaya</label>
                                    <input type="number" step="0.01" name="total_biaya" id="total_biaya" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('total_biaya') border-red-500 @enderror" value="{{ old('total_biaya') }}">
                                    @error('total_biaya') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="status" class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                                    <select name="status" id="status" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('status') border-red-500 @enderror">
                                        <option value="belum bayar" {{ old('status') == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    </select>
                                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="metode_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('metode_pembayaran') border-red-500 @enderror">
                                        <option value="">Pilih Metode</option>
                                        <option value="bank_transfer" {{ old('metode_pembayaran') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="kartu_kredit" {{ old('metode_pembayaran') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                                        <option value="Cash" {{ old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="E-Wallet" {{ old('metode_pembayaran') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                    </select>
                                    @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="tanggal_bayar" class="block text-sm text-gray-700 dark:text-gray-300">Tanggal Bayar</label>
                                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('tanggal_bayar') border-red-500 @enderror" value="{{ old('tanggal_bayar') }}">
                                    @error('tanggal_bayar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="keterangan" class="block text-sm text-gray-700 dark:text-gray-300">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                                    @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
            <form action="{{ route('admin.invoice.index') }}" method="GET">
                <div class="flex flex-wrap items-center gap-3">
                    <select name="status" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white min-w-[200px]">
                        <option value="">-- Semua Status --</option>
                        <option value="belum bayar" {{ request('status') == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <input type="text" name="search" id="search" placeholder="Cari nomor invoice, nama/nomor pelanggan..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                    <a href="{{ route('admin.invoice.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">
                        <span>Reset</span>
                    </a>
                    <a href="{{ route('admin.invoice.export', request()->query()) }}" class="btn btn-success inline-flex items-center gap-2">
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
                                <th class="px-4 py-3">No.</th>
                                <th class="px-4 py-3">Nomor Invoice</th>
                                <th class="px-4 py-3">Nama Pelanggan</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Jatuh Tempo</th>
                                <th class="px-4 py-3">Total Biaya</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($invoices as $index => $invoice)
                                <tr>
                                    <td class="px-4 py-3">{{ $invoices->firstItem() + $index }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.invoice.show', $invoice->id) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-600 font-medium">
                                            {{ $invoice->nomor_invoice }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $invoice->pelanggan->nama_lengkap ?? $invoice->pelanggan->nama_perusahaan ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $invoice->tipe }}</td>
                                    <td class="px-4 py-3">{{ $invoice->jatuh_tempo->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ number_format($invoice->total_biaya, 2, ',', '.') }} {{ $invoice->mata_uang ?? 'IDR' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($invoice->status === 'lunas') bg-green-200 text-green-800 @else bg-yellow-200 text-yellow-800 @endif">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <a href="{{ route('admin.invoice.show', $invoice->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-md hover:bg-green-600" title="Cek Detail"><i class="fas fa-eye"></i></a>
                                        @can('invoice.edit')
                                        <a href="{{ route('admin.invoice.edit', $invoice->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600" title="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('invoice.delete')
                                        <form action="{{ route('admin.invoice.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada invoice yang cocok dengan pencarian Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer p-4">
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')


{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Select2 Container Styling */
    .select2-container .select2-selection--single {
        height: 42px !important;
        margin-top: 0.25rem !important; /* mt-1 matches Tailwind */
        padding: 8px 12px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        background-color: white !important;
        transition: all 0.2s;
        display: flex !important;
        align-items: center !important;
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
        height: 100% !important;
        top: 0 !important;
        right: 10px !important;
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

{{-- jQuery and Select2 JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for pelanggan dropdown
        $('.select2-pelanggan').select2({
            placeholder: 'Pilih atau ketik nama pelanggan',
            allowClear: true,
            width: '100%',
            dropdownParent: $('.select2-pelanggan').parent()
        });
        
        // Add placeholder to search field when dropdown opens
        $('.select2-pelanggan').on('select2:open', function() {
            setTimeout(function() {
                $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Ketik nama pelanggan...');
            }, 50);
        });

        // Event listener for Select2 change
        $('.select2-pelanggan').on('change', function() {
            const pelangganId = $(this).val();
            const layananSelect = $('#layanan_id');
            
            layananSelect.html('<option value="">Memuat layanan...</option>');

            if (pelangganId) {
                $.ajax({
                    url: `{{ url('admin/layanan-by-pelanggan') }}/${pelangganId}`,
                    type: 'GET',
                    success: function(data) {
                        layananSelect.html('<option value="">Pilih Layanan</option>');
                        if (data.length > 0) {
                            data.forEach(layanan => {
                                const namaPaket = layanan.layanan_entry ? layanan.layanan_entry.nama_paket : 'N/A';
                                const option = new Option(namaPaket, layanan.id);
                                layananSelect.append(option);
                            });
                            // Auto select the first service
                            if(data.length > 0) {
                                layananSelect.val(data[0].id);
                            }
                        } else {
                            layananSelect.html('<option value="">Tidak ada layanan</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching layanan:', error);
                        layananSelect.html('<option value="">Gagal memuat layanan</option>');
                    }
                });
            } else {
                layananSelect.html('<option value="">Pilih Pelanggan Dulu</option>');
            }
        });
    });
</script>
@endpush    
@endsection