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
        Schema::create('spk', function (Blueprint $table) {
            $table->string('nomor_spk')->primary(); // Primary Key (pk)

            // Relasi utama ke tabel keluhan
            $table->foreignId('keluhan_id')->constrained('keluhan', 'id_keluhan')->onDelete('cascade');

            // Relasi dan data yang diambil dari tabel pelanggan
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade'); // Menambahkan relasi pelanggan_id
            $table->string('nomor_pelanggan'); // Diambil dari data pelanggan
            $table->string('nama_lengkap')->nullable(); // Diambil dari data pelanggan
            $table->string('nama_perusahaan')->nullable(); // Diambil dari data pelanggan
            
            // Relasi ke layanan induk
            $table->foreignId('layanan_induk_id')->constrained('layanan_induk')->onDelete('cascade');
            
            // Relasi ke POP
            $table->foreignId('pop_id')->constrained('pop')->onDelete('cascade');
            $table->text('alamat'); // Diambil dari data pelanggan
            
            $table->text('kelengkapan_kerja')->nullable();
            $table->text('keterangan');
            $table->enum('tipe', ['instalasi', 'migrasi', 'survey', 'dismantle', 'lain-lain']);
            $table->string('status');
            $table->string('pelaksana_1');
            $table->string('pelaksana_2')->nullable();
            $table->string('koordinator')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamp('rencana_pengerjaan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();

            // Tambahkan index untuk performa
            $table->index('keluhan_id');
            $table->index('pelanggan_id');
            $table->index('layanan_induk_id');
            $table->index('pop_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk');
    }
};