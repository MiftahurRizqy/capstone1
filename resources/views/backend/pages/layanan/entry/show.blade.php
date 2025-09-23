@extends('backend.layouts.app')

@section('title', 'Detail Layanan: ' . $layananEntry->kode)

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Layanan: {{ $layananEntry->kode }}</h1>
            <a href="{{ route('admin.layanan.entry.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg shadow dark:bg-gray-500 dark:hover:bg-gray-600 transition-colors duration-200">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Informasi Dasar</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li><strong>Nama Paket:</strong> {{ $layananEntry->nama_paket }}</li>
                        <li><strong>Status:</strong> {{ ucfirst($layananEntry->status) }}</li>
                        <li><strong>Tipe:</strong> {{ $layananEntry->tipe }}</li>
                        <li><strong>Kelompok Layanan:</strong> {{ $layananEntry->kelompok_layanan }}</li>
                        <li><strong>Layanan Induk:</strong> {{ $layananEntry->layananInduk->nama_layanan_induk ?? '-' }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Informasi SPK</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li><strong>SPK OSP Instalasi:</strong> {{ $layananEntry->spk_osp_instalasi ? 'Ya' : 'Tidak' }}</li>
                        <li><strong>SPK ISP Instalasi:</strong> {{ $layananEntry->spk_isp_instalasi ? 'Ya' : 'Tidak' }}</li>
                        <li><strong>SPK OSP Aktif Kembali:</strong> {{ $layananEntry->spk_osp_aktif_kembali ? 'Ya' : 'Tidak' }}</li>
                        <li><strong>SPK ISP Aktif Kembali:</strong> {{ $layananEntry->spk_isp_aktif_kembali ? 'Ya' : 'Tidak' }}</li>
                        <li><strong>Tipe Layanan SPK:</strong> {{ $layananEntry->tipe_layanan_spk ?? '-' }}</li>
                        <li><strong>Konfigurasi DHCP:</strong> {{ $layananEntry->konfigurasi_dhcp ?? '-' }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Informasi Biaya</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li><strong>Biaya Setup:</strong> Rp {{ number_format($layananEntry->biaya_setup, 0, ',', '.') }}</li>
                        <li><strong>Biaya Reguler 1 Bulan:</strong> Rp {{ number_format($layananEntry->biaya_reguler_1_bulan, 0, ',', '.') }}</li>
                        <li><strong>Biaya Reguler 3 Bulan:</strong> Rp {{ number_format($layananEntry->biaya_reguler_3_bulan, 0, ',', '.') }}</li>
                        <li><strong>Bonus 3 Bulan:</strong> {{ $layananEntry->bonus_reguler_3_bulan ?? '-' }}</li>
                        <li><strong>Biaya Reguler 6 Bulan:</strong> Rp {{ number_format($layananEntry->biaya_reguler_6_bulan, 0, ',', '.') }}</li>
                        <li><strong>Bonus 6 Bulan:</strong> {{ $layananEntry->bonus_reguler_6_bulan ?? '-' }}</li>
                        <li><strong>Biaya Reguler 12 Bulan:</strong> Rp {{ number_format($layananEntry->biaya_reguler_12_bulan, 0, ',', '.') }}</li>
                        <li><strong>Bonus 12 Bulan:</strong> {{ $layananEntry->bonus_reguler_12_bulan ?? '-' }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Informasi Tambahan</h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li><strong>Utilisasi Bandwidth:</strong> {{ $layananEntry->utilisasi_bandwidth ?? '-' }} kbps/pelanggan</li>
                        <li><strong>Koneksi TV Kabel:</strong> {{ $layananEntry->koneksi_tv_kabel ?? '-' }}</li>
                        <li><strong>Kompensasi Diskoneksi:</strong> {{ $layananEntry->kompensasi_diskoneksi ?? '-' }}</li>
                        <li><strong>Redaksional Invoice:</strong> {{ $layananEntry->redaksional_invoice ?? '-' }}</li>
                        <li><strong>Redaksional Invoice 2:</strong> {{ $layananEntry->redaksional_invoice_2 ?? '-' }}</li>
                        <li><strong>Account MYOB 1:</strong> {{ $layananEntry->account_myob_1 ?? '-' }} (Rp {{ number_format($layananEntry->biaya_reguler_1, 0, ',', '.') }})</li>
                        <li><strong>Account MYOB 2:</strong> {{ $layananEntry->account_myob_2 ?? '-' }} (Rp {{ number_format($layananEntry->biaya_reguler_2, 0, ',', '.') }})</li>
                        <li><strong>Nama Milis:</strong> {{ $layananEntry->nama_milis ?? '-' }}</li>
                    </ul>
                </div>
                <div class="lg:col-span-3">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Deskripsi</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $layananEntry->deskripsi ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection