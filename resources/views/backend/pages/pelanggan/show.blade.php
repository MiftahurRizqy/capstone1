@extends('backend.layouts.app')

@section('title', 'Detail Data Pelanggan')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Detail Data Pelanggan: {{ $pelanggan->tipe === 'personal' ? $pelanggan->nama_lengkap : $pelanggan->nama_perusahaan }}</h1>
            @php
                // Tentukan rute kembali berdasarkan tipe pelanggan
                $backRoute = $pelanggan->tipe === 'personal' ? 'admin.pelanggan.personal' : 'admin.pelanggan.perusahaan';
            @endphp
            <a href="{{ route($backRoute) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg shadow dark:bg-gray-500 dark:hover:bg-gray-600 transition-all duration-200">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- Main content card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 gap-8"> {{-- Increased gap for better spacing between sections --}}

                {{-- Informasi Pelanggan Section --}}
                <div class="pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4 border-l-4 border-blue-500 pl-3">Informasi Pelanggan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-300">
                        <div><strong>No. Member:</strong> <span class="font-medium">{{ $pelanggan->member_card }}</span></div>
                        <div><strong>Tipe:</strong> <span class="font-medium">{{ ucfirst($pelanggan->tipe) }}</span></div>
                        @if($pelanggan->tipe === 'personal')
                            <div><strong>Nama Lengkap:</strong> <span class="font-medium">{{ $pelanggan->nama_lengkap }}</span></div>
                            <div><strong>Tanggal Lahir:</strong> <span class="font-medium">{{ $pelanggan->tanggal_lahir ? $pelanggan->tanggal_lahir->format('d M Y') : '-' }}</span></div>
                            <div><strong>Jenis Kelamin:</strong> <span class="font-medium">{{ $pelanggan->jenis_kelamin === 'L' ? 'Laki-laki' : ($pelanggan->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</span></div>
                            <div><strong>Pekerjaan:</strong> <span class="font-medium">{{ $pelanggan->pekerjaan ?? '-' }}</span></div>
                            <div><strong>Email Utama:</strong> <span class="font-medium">{{ $pelanggan->email ?? '-' }}</span></div>
                            <div><strong>Tipe Identitas:</strong> <span class="font-medium">{{ $pelanggan->tipe_identitas ?? '-' }}</span></div>
                            <div><strong>Nomor Identitas:</strong> <span class="font-medium">{{ $pelanggan->nomor_identitas ?? '-' }}</span></div>
                        @else
                            <div><strong>Nama Perusahaan:</strong> <span class="font-medium">{{ $pelanggan->nama_perusahaan }}</span></div>
                            <div><strong>Jenis Usaha:</strong> <span class="font-medium">{{ $pelanggan->jenis_usaha ?? '-' }}</span></div>
                            <div><strong>Account Manager:</strong> <span class="font-medium">{{ $pelanggan->account_manager ?? '-' }}</span></div>
                            <div><strong>Telepon Perusahaan:</strong> <span class="font-medium">{{ $pelanggan->telepon_perusahaan ?? '-' }}</span></div>
                            <div><strong>Fax:</strong> <span class="font-medium">{{ $pelanggan->fax ?? '-' }}</span></div>
                            <div><strong>Email Perusahaan:</strong> <span class="font-medium">{{ $pelanggan->email ?? '-' }}</span></div>
                            <div><strong>NPWP:</strong> <span class="font-medium">{{ $pelanggan->npwp ?? '-' }}</span></div>
                        @endif
                        <div><strong>No. HP:</strong> <span class="font-medium">{{ $pelanggan->no_hp }}</span></div>
                        <div><strong>Nama Kontak Lain:</strong> <span class="font-medium">{{ $pelanggan->nama_kontak ?? '-' }}</span></div>
                        <div class="sm:col-span-2 lg:col-span-3"><strong>Alamat:</strong> <span class="font-medium">{{ $pelanggan->alamat }}</span></div>
                        <div><strong>Kode Pos:</strong> <span class="font-medium">{{ $pelanggan->kode_pos }}</span></div>
                        <div><strong>Kabupaten:</strong> <span class="font-medium">{{ $pelanggan->kabupaten }}</span></div>
                        <div><strong>Kota:</strong> <span class="font-medium">{{ $pelanggan->kota }}</span></div>
                        <div><strong>Wilayah:</strong> <span class="font-medium">{{ $pelanggan->wilayah }}</span></div>
                        <div><strong>POP:</strong> <span class="font-medium">{{ $pelanggan->pop->nama_pop ?? 'N/A' }}</span></div>
                        <div><strong>Reseller:</strong> <span class="font-medium">{{ $pelanggan->reseller ? 'Ya' : 'Tidak' }}</span></div>
                    </div>
                </div>

                {{-- Informasi Layanan Section --}}
                <div class="pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4 border-l-4 border-blue-500 pl-3">Informasi Layanan</h2>
                    @if($pelanggan->layanan->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-300">
                            <div><strong>Homepass:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->homepass ?? '-' }}</span></div>
                            <div><strong>Jenis Layanan:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->jenis_layanan ?? '-' }}</span></div>
                            <div><strong>Mulai Kontrak:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->mulai_kontrak ? $pelanggan->layanan->first()->mulai_kontrak->format('d M Y') : '-' }}</span></div>
                            <div><strong>Selesai Kontrak:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->selesai_kontrak ? $pelanggan->layanan->first()->selesai_kontrak->format('d M Y') : '-' }}</span></div>
                            <div><strong>Perjanjian Trial:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->perjanjian_trial ? 'Ya' : 'Tidak' }}</span></div>
                            <div><strong>Pembelian Modem:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->pembelian_modem ? 'Ya' : 'Tidak' }}</span></div>
                            <div><strong>Email Alternatif 1:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->email_alternatif_1 ?? '-' }}</span></div>
                            <div><strong>Email Alternatif 2:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->email_alternatif_2 ?? '-' }}</span></div>
                            <div><strong>Jumlah TV Kabel:</strong> <span class="font-medium">{{ $pelanggan->layanan->first()->jumlah_tv_kabel ?? 0 }}</span></div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">Belum ada data layanan.</p>
                    @endif
                </div>

                {{-- Informasi Penagihan Section --}}
                <div> {{-- No bottom border for the last section --}}
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4 border-l-4 border-blue-500 pl-3">Informasi Penagihan</h2>
                    @if($pelanggan->penagihan)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-300">
                            <div><strong>Kontak Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->kontak_penagihan ?? '-' }}</span></div>
                            <div><strong>Alamat Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->alamat_penagihan ?? '-' }}</span></div>
                            <div><strong>Kode Pos Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->kode_pos_penagihan ?? '-' }}</span></div>
                            <div><strong>Kabupaten Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->kabupaten_penagihan ?? '-' }}</span></div>
                            <div><strong>Kota Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->kota_penagihan ?? '-' }}</span></div>
                            <div><strong>No. HP Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->no_hp_penagihan ?? '-' }}</span></div>
                            <div><strong>Telepon Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->telepon_penagihan ?? '-' }}</span></div>
                            <div><strong>Fax Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->fax_penagihan ?? '-' }}</span></div>
                            <div><strong>Email Penagihan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->email_penagihan ?? '-' }}</span></div>
                            <div><strong>Cara Pembayaran:</strong> <span class="font-medium">{{ $pelanggan->penagihan->cara_pembayaran ?? '-' }}</span></div>
                            <div><strong>Waktu Pembayaran:</strong> <span class="font-medium">{{ $pelanggan->penagihan->waktu_pembayaran ?? '-' }}</span></div>
                            <div><strong>Invoice Instalasi:</strong> <span class="font-medium">{{ $pelanggan->penagihan->invoice_instalasi ?? '-' }}</span></div>
                            <div><strong>Invoice Reguler:</strong> <span class="font-medium">{{ $pelanggan->penagihan->invoice_reguler ?? '-' }}</span></div>
                            <div><strong>Mata Uang:</strong> <span class="font-medium">{{ $pelanggan->penagihan->mata_uang ?? '-' }}</span></div>
                            <div><strong>Biaya Reguler:</strong> <span class="font-medium">{{ number_format($pelanggan->penagihan->biaya_reguler ?? 0, 2, ',', '.') }}</span></div>
                            <div><strong>Kenakan PPN:</strong> <span class="font-medium">{{ $pelanggan->penagihan->kenakan_ppn ? 'Ya' : 'Tidak' }}</span></div>
                            <div class="col-span-full"><strong>Keterangan:</strong> <span class="font-medium">{{ $pelanggan->penagihan->keterangan ?? '-' }}</span></div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">Belum ada data penagihan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
