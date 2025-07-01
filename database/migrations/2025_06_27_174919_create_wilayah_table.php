<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id(); // ID auto-increment untuk semua entri (provinsi, kabupaten, kecamatan, kelurahan, bagian)

            // KOLOM parent_id untuk membangun hirarki internal
            $table->foreignId('parent_id')->nullable()->constrained('wilayah')->onDelete('cascade');

            $table->string('nama'); // Nama Wilayah (Provinsi, Kabupaten, Kecamatan, Kelurahan, Bagian)

            // Tipe wilayah (semua tingkatan, termasuk 'bagian')
            $table->enum('tipe', ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'bagian']);
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            // Kolom-kolom denormalisasi untuk menyimpan detail lokasi dari API eksternal (jika digunakan)
            // Ini akan diisi HANYA untuk tipe 'bagian' yang merupakan entri paling bawah
            $table->string('provinsi_nama')->nullable();
            $table->string('kabupaten_nama')->nullable();
            $table->string('kecamatan_nama')->nullable();
            $table->string('kelurahan_nama')->nullable();

            // ID eksternal untuk referensi ke API emsifa.com (ini juga HANYA diisi untuk tipe 'bagian')
            $table->string('external_provinsi_id')->nullable();
            $table->string('external_kabupaten_id')->nullable();
            $table->string('external_kecamatan_id')->nullable();
            $table->string('external_kelurahan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};