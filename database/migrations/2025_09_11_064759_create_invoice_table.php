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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->enum('tipe', ['Instalasi', 'Reguler', 'Deposit', 'Droping', 'lain-lain']);
            $table->date('jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['belum bayar', 'lunas'])->default('belum bayar');
            $table->decimal('total_biaya', 12, 2)->nullable(); // Menambahkan kolom untuk total biaya
            $table->string('mata_uang')->default('IDR');
            $table->text('keterangan')->nullable();
            $table->enum('metode_pembayaran', ['bank_transfer', 'kartu_kredit', 'Cash', 'E-Wallet'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};