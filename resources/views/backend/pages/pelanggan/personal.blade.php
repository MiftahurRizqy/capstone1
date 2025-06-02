@extends('backend.layouts.app')

@section('title', 'Daftar Pelanggan Personal')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white/90">Daftar Pelanggan Personal</h1>
            <div x-data="{ open: false }">
                <button @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow dark:bg-blue-500 dark:hover:bg-blue-600">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Pelanggan</span>
                </button>

                <div x-show="open"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                     style="display: none;"> {{-- Penting: display: none; untuk menghindari flash konten --}}
                    <div @click.away="open = false" class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-2xl shadow-lg p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Pelanggan Baru</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.pelanggan.store') }}" method="POST" class="p-0">
                            @csrf
                            <div x-data="{ activeTab: 'informasi_pelanggan' }">
                                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                        <button type="button" @click="activeTab = 'informasi_pelanggan'"
                                                :class="activeTab === 'informasi_pelanggan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Informasi Pelanggan
                                        </button>
                                        <button type="button" @click="activeTab = 'layanan'"
                                                :class="activeTab === 'layanan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Layanan
                                        </button>
                                        <button type="button" @click="activeTab = 'penagihan'"
                                                :class="activeTab === 'penagihan' ? 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            Penagihan
                                        </button>
                                    </nav>
                                </div>

                                <div class="space-y-6">
                                    {{-- TAB 1: Informasi Pelanggan --}}
                                    <div x-show="activeTab === 'informasi_pelanggan'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nama_lengkap" class="block text-sm text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" required>
                                        </div>
                                        <div>
                                            <label for="member_card" class="block text-sm text-gray-700 dark:text-gray-300">No. Member</label>
                                            <input type="text" name="member_card" id="member_card" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="no_hp" class="block text-sm text-gray-700 dark:text-gray-300">No. HP</label>
                                            <input type="text" name="no_hp" id="no_hp" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" required>
                                        </div>
                                        <div>
                                            <label for="nama_kontak" class="block text-sm text-gray-700 dark:text-gray-300">Nama Kontak Lain</label>
                                            <input type="text" name="nama_kontak" id="nama_kontak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="alamat" class="block text-sm text-gray-700 dark:text-gray-300">Alamat</label>
                                            <textarea name="alamat" id="alamat" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" rows="2" required></textarea>
                                        </div>
                                        <div>
                                            <label for="kode_pos" class="block text-sm text-gray-700 dark:text-gray-300">Kode Pos</label>
                                            <input type="text" name="kode_pos" id="kode_pos" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="kabupaten" class="block text-sm text-gray-700 dark:text-gray-300">Kabupaten</label>
                                            <input type="text" name="kabupaten" id="kabupaten" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="kota" class="block text-sm text-gray-700 dark:text-gray-300">Kota</label>
                                            <input type="text" name="kota" id="kota" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="wilayah" class="block text-sm text-gray-700 dark:text-gray-300">Wilayah</label>
                                            <input type="text" name="wilayah" id="wilayah" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="pop" class="block text-sm text-gray-700 dark:text-gray-300">POP</label>
                                            <select name="pop" id="pop" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                                <option value="">Pilih POP</option>
                                                {{-- Melakukan loop untuk setiap data pop --}}
                                                @foreach($pops as $pop)
                                                    <option value="{{ $pop->nama_pop }}">{{ $pop->nama_pop }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="tipe_identitas" class="block text-sm text-gray-700 dark:text-gray-300">Tipe Identitas</label>
                                            <select name="tipe_identitas" id="tipe_identitas" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                                <option value="">Pilih Tipe Identitas</option>
                                                <option value="KTP">KTP</option>
                                                <option value="SIM">SIM</option>
                                                <option value="Paspor">Paspor</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="nomor_identitas" class="block text-sm text-gray-700 dark:text-gray-300">Nomor Identitas</label>
                                            <input type="text" name="nomor_identitas" id="nomor_identitas" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="tanggal_lahir" class="block text-sm text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="jenis_kelamin" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" id="jenis_kelamin" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pekerjaan" class="block text-sm text-gray-700 dark:text-gray-300">Pekerjaan</label>
                                            <input type="text" name="pekerjaan" id="pekerjaan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm text-gray-700 dark:text-gray-300">Email Utama</label>
                                            <input type="email" name="email" id="email" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="reseller" class="block text-sm text-gray-700 dark:text-gray-300">Reseller</label>
                                            <select name="reseller" id="reseller" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                                <option value="">Pilih Reseller</option>
                                                <option value="Ya">Ya</option>
                                                <option value="Tidak">Tidak</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- TAB 2: Layanan --}}
                                    <div x-show="activeTab === 'layanan'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Note: pelanggan_id akan otomatis terisi setelah pelanggan utama tersimpan, atau bisa disembunyikan --}}
                                        <div>
                                            <label for="homepass" class="block text-sm text-gray-700 dark:text-gray-300">Homepass</label>
                                            <input type="text" name="homepass" id="homepass" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="jenis_layanan" class="block text-sm text-gray-700 dark:text-gray-300">Jenis Layanan</label>
                                            <input type="text" name="jenis_layanan" id="jenis_layanan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="mulai_kontrak" class="block text-sm text-gray-700 dark:text-gray-300">Mulai Kontrak</label>
                                            <input type="date" name="mulai_kontrak" id="mulai_kontrak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="selesai_kontrak" class="block text-sm text-gray-700 dark:text-gray-300">Selesai Kontrak</label>
                                            <input type="date" name="selesai_kontrak" id="selesai_kontrak" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="perjanjian_trial" id="perjanjian_trial" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                            <label for="perjanjian_trial" class="text-sm text-gray-700 dark:text-gray-300">Perjanjian Trial</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="pembelian_modem" id="pembelian_modem" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                            <label for="pembelian_modem" class="text-sm text-gray-700 dark:text-gray-300">Pembelian Modem</label>
                                        </div>
                                        <div>
                                            <label for="email_alternatif_1" class="block text-sm text-gray-700 dark:text-gray-300">Email Alternatif 1</label>
                                            <input type="email" name="email_alternatif_1" id="email_alternatif_1" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="email_alternatif_2" class="block text-sm text-gray-700 dark:text-gray-300">Email Alternatif 2</label>
                                            <input type="email" name="email_alternatif_2" id="email_alternatif_2" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="jumlah_tv_kabel" class="block text-sm text-gray-700 dark:text-gray-300">Jumlah TV Kabel</label>
                                            <input type="number" name="jumlah_tv_kabel" id="jumlah_tv_kabel" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" min="0">
                                        </div>
                                    </div>

                                    {{-- TAB 3: Penagihan --}}
                                    <div x-show="activeTab === 'penagihan'" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Note: pelanggan_id akan otomatis terisi --}}
                                        <div>
                                            <label for="kontak_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kontak Penagihan</label>
                                            <input type="text" name="kontak_penagihan" id="kontak_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="alamat_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Alamat Penagihan</label>
                                            <textarea name="alamat_penagihan" id="alamat_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" rows="2"></textarea>
                                        </div>
                                        <div>
                                            <label for="kode_pos_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kode Pos Penagihan</label>
                                            <input type="text" name="kode_pos_penagihan" id="kode_pos_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="kabupaten_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kabupaten Penagihan</label>
                                            <input type="text" name="kabupaten_penagihan" id="kabupaten_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="kota_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Kota Penagihan</label>
                                            <input type="text" name="kota_penagihan" id="kota_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="no_hp_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">No. HP Penagihan</label>
                                            <input type="text" name="no_hp_penagihan" id="no_hp_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="telepon_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Telepon Penagihan</label>
                                            <input type="text" name="telepon_penagihan" id="telepon_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="fax_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Fax Penagihan</label>
                                            <input type="text" name="fax_penagihan" id="fax_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="email_penagihan" class="block text-sm text-gray-700 dark:text-gray-300">Email Penagihan</label>
                                            <input type="email" name="email_penagihan" id="email_penagihan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="cara_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Cara Pembayaran</label>
                                            <input type="text" name="cara_pembayaran" id="cara_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="waktu_pembayaran" class="block text-sm text-gray-700 dark:text-gray-300">Waktu Pembayaran</label>
                                            <input type="text" name="waktu_pembayaran" id="waktu_pembayaran" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" placeholder="Misal: Setiap tanggal 5">
                                        </div>
                                        <div>
                                            <label for="mata_uang" class="block text-sm text-gray-700 dark:text-gray-300">Mata Uang</label>
                                            <input type="text" name="mata_uang" id="mata_uang" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div>
                                            <label for="biaya_reguler" class="block text-sm text-gray-700 dark:text-gray-300">Biaya Reguler</label>
                                            <input type="number" step="0.01" name="biaya_reguler" id="biaya_reguler" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="invoice_instalasi" id="invoice_instalasi" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                            <label for="invoice_instalasi" class="text-sm text-gray-700 dark:text-gray-300">Invoice Instalasi</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="invoice_reguler" id="invoice_reguler" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                            <label for="invoice_reguler" class="text-sm text-gray-700 dark:text-gray-300">Invoice Reguler</label>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="kenakan_ppn" id="kenakan_ppn" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                            <label for="kenakan_ppn" class="text-sm text-gray-700 dark:text-gray-300">Kenakan PPN</label>
                                        </div>
                                        <div class="md:col-span-2"> {{-- Memastikan textarea mengambil 2 kolom --}}
                                            <label for="keterangan" class="block text-sm text-gray-700 dark:text-gray-300">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="w-full mt-1 px-3 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white" rows="2"></textarea>
                                        </div>
                                    </div>
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

        <div class="card bg-white shadow rounded-lg dark:bg-white/[0.03] dark:border dark:border-gray-700">
            <div class="card-body p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white/80">
                            <tr>
                                <th class="px-4 py-3">No. Member</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">No. HP</th>
                                <th class="px-4 py-3">Alamat</th>
                                <th class="px-4 py-3">Layanan</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Belum ada data pelanggan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Pastikan Alpine.js dimuat, bisa di layout utama atau di sini --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush