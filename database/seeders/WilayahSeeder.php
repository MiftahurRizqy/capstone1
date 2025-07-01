<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wilayah;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Hapus semua data yang ada di tabel wilayah untuk memastikan bersih sebelum seeding
        // HATI-HATI: Ini akan menghapus SEMUA data di tabel 'wilayah'
        Wilayah::truncate();

        // Contoh data: DIY, Sleman, Depok, Caturtunggal (mengikuti hierarki administrasi umum)
        $provinsiDIY = Wilayah::firstOrCreate(
            ['nama' => 'Daerah Istimewa Yogyakarta', 'tipe' => 'provinsi'],
            ['parent_id' => null, 'deskripsi' => 'Provinsi Daerah Istimewa Yogyakarta']
        );

        $kabupatenSleman = Wilayah::firstOrCreate(
            ['nama' => 'Sleman', 'tipe' => 'kabupaten'],
            ['parent_id' => $provinsiDIY->id, 'deskripsi' => 'Kabupaten Sleman di DIY']
        );

        $kecamatanDepok = Wilayah::firstOrCreate(
            ['nama' => 'Depok', 'tipe' => 'kecamatan'],
            ['parent_id' => $kabupatenSleman->id, 'deskripsi' => 'Kecamatan Depok di Sleman']
        );

        $kelurahanCaturtunggal = Wilayah::firstOrCreate(
            ['nama' => 'Caturtunggal', 'tipe' => 'kelurahan'],
            ['parent_id' => $kecamatanDepok->id, 'deskripsi' => 'Kelurahan Caturtunggal di Kec. Depok']
        );

        // Contoh data 'bagian' yang terhubung ke hirarki di atas
        Wilayah::firstOrCreate([
            'nama' => 'Babarsari Network',
            'tipe' => 'bagian',
            'parent_id' => $kelurahanCaturtunggal->id, // parent_id menunjuk ke ID Kelurahan dari seeder
            'deskripsi' => 'Jaringan di area Babarsari, Caturtunggal, Depok',
            // Kolom denormalisasi ini bisa diisi dari data seeder juga, atau dibiarkan null
            'provinsi_nama' => $provinsiDIY->nama,
            'kabupaten_nama' => $kabupatenSleman->nama,
            'kecamatan_nama' => $kecamatanDepok->nama,
            'kelurahan_nama' => $kelurahanCaturtunggal->nama,
            'external_provinsi_id' => '34', // Contoh ID Emsifa jika tahu
            'external_kabupaten_id' => '34.04',
            'external_kecamatan_id' => '34.04.05', // ID Depok
            'external_kelurahan_id' => '34.04.05.2001', // ID Caturtunggal (dari Emsifa)
        ]);

        // Contoh data lain: Jawa Barat, Kota Bandung, Cibiru, Cisaranten Kulon
        $provinsiJabar = Wilayah::firstOrCreate(
            ['nama' => 'Jawa Barat', 'tipe' => 'provinsi'],
            ['parent_id' => null, 'deskripsi' => 'Provinsi Jawa Barat']
        );

        $kabupatenBandung = Wilayah::firstOrCreate(
            ['nama' => 'Kota Bandung', 'tipe' => 'kabupaten'],
            ['parent_id' => $provinsiJabar->id, 'deskripsi' => 'Kota Bandung di Jawa Barat']
        );

        $kecamatanCibiru = Wilayah::firstOrCreate(
            ['nama' => 'Cibiru', 'tipe' => 'kecamatan'],
            ['parent_id' => $kabupatenBandung->id, 'deskripsi' => 'Kecamatan Cibiru di Kota Bandung']
        );

        $kelurahanCisarantenKulon = Wilayah::firstOrCreate(
            ['nama' => 'Cisaranten Kulon', 'tipe' => 'kelurahan'],
            ['parent_id' => $kecamatanCibiru->id, 'deskripsi' => 'Kelurahan Cisaranten Kulon di Kec. Cibiru']
        );

        Wilayah::firstOrCreate([
            'nama' => 'Perumahan Indah Network',
            'tipe' => 'bagian',
            'parent_id' => $kelurahanCisarantenKulon->id,
            'deskripsi' => 'Jaringan Perumahan Indah di Cibiru, Cisaranten Kulon',
            'provinsi_nama' => $provinsiJabar->nama,
            'kabupaten_nama' => $kabupatenBandung->nama,
            'kecamatan_nama' => $kecamatanCibiru->nama,
            'kelurahan_nama' => $kelurahanCisarantenKulon->nama,
            'external_provinsi_id' => '32',
            'external_kabupaten_id' => '32.73',
            'external_kecamatan_id' => '32.73.23', // ID Cibiru
            'external_kelurahan_id' => '32.73.23.1001', // ID Cisaranten Kulon
        ]);

        $this->command->info('Initial Wilayah data seeded successfully!');
    }
}