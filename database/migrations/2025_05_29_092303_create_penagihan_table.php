<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');            
            // Kontak penagihan
            $table->string('kontak_penagihan')->nullable();
            $table->text('alamat_penagihan')->nullable();
            $table->string('kode_pos_penagihan')->nullable();
            $table->string('kabupaten_penagihan')->nullable();
            $table->string('kota_penagihan')->nullable();
            $table->string('no_hp_penagihan')->nullable();
            $table->string('telepon_penagihan')->nullable();
            $table->string('fax_penagihan')->nullable();
            $table->string('email_penagihan')->nullable();
            
            // Info pembayaran
            $table->string('cara_pembayaran')->nullable();
            $table->string('waktu_pembayaran')->nullable();
            $table->string('invoice_instalasi')->nullable();
            $table->string('invoice_reguler')->nullable();
            $table->enum('mata_uang', ['IDR'])->default('IDR')->nullable();
            $table->decimal('biaya_reguler', 12, 2)->nullable();
            $table->boolean('kenakan_ppn')->default(false)->nullable();
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penagihan');
    }
};