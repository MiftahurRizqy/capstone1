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
            $table->date('jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->enum('tipe', ['bulanan', 'layanan_baru', 'lain-lain']);
            $table->string('mata_uang')->default('IDR');
            $table->text('keterangan')->nullable();
            $table->enum('metode_pembayaran', ['bank_transfer', 'tunai', 'kartu_kredit'])->nullable();
            $table->timestamps();

            // Tambahkan index jika diperlukan
            $table->index('pelanggan_id');
            $table->index('layanan_id');
            $table->index('nomor_invoice');
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
