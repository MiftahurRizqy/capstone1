@extends('backend.layouts.app')

@section('title', 'Edit Invoice')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90 mb-4">Edit Invoice {{ $invoice->nomor_invoice }}</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sukses!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any() || session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">{{ session('error') ?? 'Ada beberapa masalah dengan input Anda.' }}</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-white shadow rounded-lg p-6 dark:bg-gray-800 dark:border dark:border-gray-700">
            <form action="{{ route('admin.invoice.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nomor_invoice" class="block text-sm text-gray-700 dark:text-gray-300">Nomor Invoice</label>
                        <input type="text" name="nomor_invoice" id="nomor_invoice" class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white cursor-not-allowed" value="{{ old('nomor_invoice', $invoice->nomor_invoice) }}" readonly>
                        @error('nomor_invoice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="pelanggan_id" class="block text-sm text-gray-700 dark:text-gray-300">Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('pelanggan_id') border-red-500 @enderror">
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id', $invoice->pelanggan_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap ?? $p->nama_perusahaan }} ({{ $p->nomor_pelanggan }})
                                </option>
                            @endforeach
                        </select>
                        @error('pelanggan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="layanan_id" class="block text-sm text-gray-700 dark:text-gray-300">Layanan</label>
                        <select name="layanan_id" id="layanan_id" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('layanan_id') border-red-500 @enderror">
                            <option value="">Pilih Layanan</option>
                            @foreach($layanan as $l)
                                <option value="{{ $l->id }}" {{ old('layanan_id', $invoice->layanan_id) == $l->id ? 'selected' : '' }}>
                                    {{ $l->layananEntry->nama_paket ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('layanan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tipe" class="block text-sm text-gray-700 dark:text-gray-300">Tipe Invoice</label>
                        <select name="tipe" id="tipe" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('tipe') border-red-500 @enderror">
                            <option value="Instalasi" {{ old('tipe', $invoice->tipe) == 'Instalasi' ? 'selected' : '' }}>Instalasi</option>
                            <option value="Reguler" {{ old('tipe', $invoice->tipe) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Deposit" {{ old('tipe', $invoice->tipe) == 'Deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="Droping" {{ old('tipe', $invoice->tipe) == 'Droping' ? 'selected' : '' }}>Droping</option>
                            <option value="lain-lain" {{ old('tipe', $invoice->tipe) == 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        @error('tipe') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jatuh_tempo" class="block text-sm text-gray-700 dark:text-gray-300">Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('jatuh_tempo') border-red-500 @enderror" value="{{ old('jatuh_tempo', $invoice->jatuh_tempo->format('Y-m-d')) }}">
                        @error('jatuh_tempo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="total_biaya" class="block text-sm text-gray-700 dark:text-gray-300">Total Biaya</label>
                        <input type="number" step="0.01" name="total_biaya" id="total_biaya" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('total_biaya') border-red-500 @enderror" value="{{ old('total_biaya', $invoice->total_biaya) }}">
                        @error('total_biaya') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('status') border-red-500 @enderror">
                            <option value="belum bayar" {{ old('status', $invoice->status) == 'belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ old('status', $invoice->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="metode_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('metode_pembayaran') border-red-500 @enderror">
                            <option value="">Pilih Metode</option>
                            <option value="bank_transfer" {{ old('metode_pembayaran', $invoice->metode_pembayaran) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="kartu_kredit" {{ old('metode_pembayaran', $invoice->metode_pembayaran) == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                            <option value="Cash" {{ old('metode_pembayaran', $invoice->metode_pembayaran) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="E-Wallet" {{ old('metode_pembayaran', $invoice->metode_pembayaran) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                        @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tanggal_bayar" class="block text-sm text-gray-700 dark:text-gray-300">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('tanggal_bayar') border-red-500 @enderror" value="{{ old('tanggal_bayar', $invoice->tanggal_bayar ? $invoice->tanggal_bayar->format('Y-m-d') : '') }}">
                        @error('tanggal_bayar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm text-gray-700 dark:text-gray-300">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $invoice->keterangan) }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('admin.invoice.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pelangganSelect = document.getElementById('pelanggan_id');
        const layananSelect = document.getElementById('layanan_id');

        // Fungsi untuk memuat layanan
        function fetchLayanan(pelangganId, selectedLayananId = null) {
            layananSelect.innerHTML = '<option value="">Memuat layanan...</option>';

            if (pelangganId) {
                fetch(`{{ url('admin/layanan-by-pelanggan') }}/${pelangganId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        layananSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                        if (data.length > 0) {
                            data.forEach(layanan => {
                                const namaPaket = layanan.layanan_entry ? layanan.layanan_entry.nama_paket : 'N/A';
                                const option = document.createElement('option');
                                option.value = layanan.id;
                                option.textContent = `${namaPaket}`;
                                if (selectedLayananId && layanan.id == selectedLayananId) {
                                    option.selected = true;
                                }
                                layananSelect.appendChild(option);
                            });
                        } else {
                            layananSelect.innerHTML = '<option value="">Tidak ada layanan</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching layanan:', error);
                        layananSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
                    });
            } else {
                layananSelect.innerHTML = '<option value="">Pilih Pelanggan Dulu</option>';
            }
        }

        // Panggil fungsi saat halaman dimuat untuk mengisi dropdown layanan pertama kali
        const initialPelangganId = pelangganSelect.value;
        const initialLayananId = {{ $invoice->layanan_id ?? 'null' }};
        if (initialPelangganId) {
            fetchLayanan(initialPelangganId, initialLayananId);
        }

        // Panggil fungsi saat pilihan pelanggan berubah
        pelangganSelect.addEventListener('change', function () {
            fetchLayanan(this.value);
        });
    });
</script>
@endpush
@endsection