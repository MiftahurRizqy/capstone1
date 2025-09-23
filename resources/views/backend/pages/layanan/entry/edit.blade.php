@extends('backend.layouts.app')

@section('title', 'Edit Layanan')

@section('admin-content')

<div class="container mx-auto px-4 py-6">
    <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
        <div class="card-body p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Layanan</h2>

            <form action="{{ route('admin.layanan.entry.update', $layananEntry->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode', $layananEntry->kode) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Paket</label>
                        <input type="text" name="nama_paket" value="{{ old('nama_paket', $layananEntry->nama_paket) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="aktif" {{ old('status', $layananEntry->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak aktif" {{ old('status', $layananEntry->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                        <select name="tipe" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="TV" {{ old('tipe', $layananEntry->tipe) == 'TV' ? 'selected' : '' }}>TV</option>
                            <option value="Internet" {{ old('tipe', $layananEntry->tipe) == 'Internet' ? 'selected' : '' }}>Internet</option>
                            <option value="Lain-Lain" {{ old('tipe', $layananEntry->tipe) == 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelompok Layanan</label>
                        <select name="kelompok_layanan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Layanan Dasar" {{ old('kelompok_layanan', $layananEntry->kelompok_layanan) == 'Layanan Dasar' ? 'selected' : '' }}>Layanan Dasar</option>
                            <option value="Web Hosting" {{ old('kelompok_layanan', $layananEntry->kelompok_layanan) == 'Web Hosting' ? 'selected' : '' }}>Web Hosting</option>
                            <option value="Colocation" {{ old('kelompok_layanan', $layananEntry->kelompok_layanan) == 'Colocation' ? 'selected' : '' }}>Colocation</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Layanan Induk</label>
                        <select name="layanan_induk_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Tidak ada</option>
                            @foreach($layananInduks as $li)
                            <option value="{{ $li->id }}" {{ old('layanan_induk_id', $layananEntry->layanan_induk_id) == $li->id ? 'selected' : '' }}>{{ $li->nama_layanan_induk }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kolom Checkbox SPK --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SPK Checkboxes</label>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center">
                                <input type="hidden" name="spk_osp_instalasi" value="0">
                                <input type="checkbox" name="spk_osp_instalasi" value="1" {{ old('spk_osp_instalasi', $layananEntry->spk_osp_instalasi) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK OSP Instalasi</label>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="spk_isp_instalasi" value="0">
                                <input type="checkbox" name="spk_isp_instalasi" value="1" {{ old('spk_isp_instalasi', $layananEntry->spk_isp_instalasi) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK ISP Instalasi</label>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="spk_osp_aktif_kembali" value="0">
                                <input type="checkbox" name="spk_osp_aktif_kembali" value="1" {{ old('spk_osp_aktif_kembali', $layananEntry->spk_osp_aktif_kembali) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK OSP Aktif Kembali</label>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="spk_isp_aktif_kembali" value="0">
                                <input type="checkbox" name="spk_isp_aktif_kembali" value="1" {{ old('spk_isp_aktif_kembali', $layananEntry->spk_isp_aktif_kembali) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">SPK ISP Aktif Kembali</label>
                            </div>
                        </div>
                    </div>

                    {{-- Bidang Lainnya --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Layanan SPK</label>
                        <select name="tipe_layanan_spk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Tipe</option>
                            <option value="TV" {{ old('tipe_layanan_spk', $layananEntry->tipe_layanan_spk) == 'TV' ? 'selected' : '' }}>TV</option>
                            <option value="Internet HFC" {{ old('tipe_layanan_spk', $layananEntry->tipe_layanan_spk) == 'Internet HFC' ? 'selected' : '' }}>Internet HFC</option>
                            <option value="Internet Wireless" {{ old('tipe_layanan_spk', $layananEntry->tipe_layanan_spk) == 'Internet Wireless' ? 'selected' : '' }}>Internet Wireless</option>
                            <option value="Wi TV" {{ old('tipe_layanan_spk', $layananEntry->tipe_layanan_spk) == 'Wi TV' ? 'selected' : '' }}>Wi TV</option>
                            <option value="TV-DTH" {{ old('tipe_layanan_spk', $layananEntry->tipe_layanan_spk) == 'TV-DTH' ? 'selected' : '' }}>TV-DTH</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Utilisasi Bandwidth (kbps)</label>
                        <input type="number" name="utilisasi_bandwidth" value="{{ old('utilisasi_bandwidth', $layananEntry->utilisasi_bandwidth) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Setup</label>
                        <input type="number" step="0.01" name="biaya_setup" value="{{ old('biaya_setup', $layananEntry->biaya_setup) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 1 Bulan</label>
                        <input type="number" step="0.01" name="biaya_reguler_1_bulan" value="{{ old('biaya_reguler_1_bulan', $layananEntry->biaya_reguler_1_bulan) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 3 Bulan</label>
                        <input type="number" step="0.01" name="biaya_reguler_3_bulan" value="{{ old('biaya_reguler_3_bulan', $layananEntry->biaya_reguler_3_bulan) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus Reguler 3 Bulan</label>
                        <select name="bonus_reguler_3_bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Tidak ada</option>
                            @foreach(['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'] as $bonus)
                            <option value="{{ $bonus }}" {{ old('bonus_reguler_3_bulan', $layananEntry->bonus_reguler_3_bulan) == $bonus ? 'selected' : '' }}>{{ $bonus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 6 Bulan</label>
                        <input type="number" step="0.01" name="biaya_reguler_6_bulan" value="{{ old('biaya_reguler_6_bulan', $layananEntry->biaya_reguler_6_bulan) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus Reguler 6 Bulan</label>
                        <select name="bonus_reguler_6_bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Tidak ada</option>
                            @foreach(['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'] as $bonus)
                            <option value="{{ $bonus }}" {{ old('bonus_reguler_6_bulan', $layananEntry->bonus_reguler_6_bulan) == $bonus ? 'selected' : '' }}>{{ $bonus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 12 Bulan</label>
                        <input type="number" step="0.01" name="biaya_reguler_12_bulan" value="{{ old('biaya_reguler_12_bulan', $layananEntry->biaya_reguler_12_bulan) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bonus Reguler 12 Bulan</label>
                        <select name="bonus_reguler_12_bulan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Tidak ada</option>
                            @foreach(['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'] as $bonus)
                            <option value="{{ $bonus }}" {{ old('bonus_reguler_12_bulan', $layananEntry->bonus_reguler_12_bulan) == $bonus ? 'selected' : '' }}>{{ $bonus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Koneksi TV Kabel</label>
                        <select name="koneksi_tv_kabel" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Tidak ada</option>
                            <option value="Corporate TV" {{ old('koneksi_tv_kabel', $layananEntry->koneksi_tv_kabel) == 'Corporate TV' ? 'selected' : '' }}>Corporate TV</option>
                            <option value="Layanan Lain" {{ old('koneksi_tv_kabel', $layananEntry->koneksi_tv_kabel) == 'Layanan Lain' ? 'selected' : '' }}>Layanan Lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kompensasi Diskoneksi</label>
                        <select name="kompensasi_diskoneksi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Tidak ada</option>
                            <option value="Terima Kompensasi" {{ old('kompensasi_diskoneksi', $layananEntry->kompensasi_diskoneksi) == 'Terima Kompensasi' ? 'selected' : '' }}>Terima Kompensasi</option>
                            <option value="Tidak Terima Kompensasi" {{ old('kompensasi_diskoneksi', $layananEntry->kompensasi_diskoneksi) == 'Tidak Terima Kompensasi' ? 'selected' : '' }}>Tidak Terima Kompensasi</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfigurasi DHCP</label>
                        <textarea name="konfigurasi_dhcp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('konfigurasi_dhcp', $layananEntry->konfigurasi_dhcp) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Redaksional Invoice</label>
                        <input type="text" name="redaksional_invoice" value="{{ old('redaksional_invoice', $layananEntry->redaksional_invoice) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Redaksional Invoice 2 (khusus Hosting)</label>
                        <input type="text" name="redaksional_invoice_2" value="{{ old('redaksional_invoice_2', $layananEntry->redaksional_invoice_2) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account MYOB 1</label>
                        <input type="text" name="account_myob_1" value="{{ old('account_myob_1', $layananEntry->account_myob_1) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 1</label>
                        <input type="number" step="0.01" name="biaya_reguler_1" value="{{ old('biaya_reguler_1', $layananEntry->biaya_reguler_1) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account MYOB 2</label>
                        <input type="text" name="account_myob_2" value="{{ old('account_myob_2', $layananEntry->account_myob_2) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biaya Reguler 2</label>
                        <input type="number" step="0.01" name="biaya_reguler_2" value="{{ old('biaya_reguler_2', $layananEntry->biaya_reguler_2) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Milis</label>
                        <input type="text" name="nama_milis" value="{{ old('nama_milis', $layananEntry->nama_milis) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="deskripsi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('deskripsi', $layananEntry->deskripsi) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('admin.layanan.entry.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition-colors duration-200">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection