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
            $table->string('kontak_penagihan');
            $table->text('alamat_penagihan');
            $table->string('kode_pos_penagihan');
            $table->string('kabupaten_penagihan');
            $table->string('kota_penagihan');
            $table->string('no_hp_penagihan');
            $table->string('telepon_penagihan')->nullable();
            $table->string('fax_penagihan')->nullable();
            $table->string('email_penagihan')->nullable();
            
            // Info pembayaran
            $table->string('cara_pembayaran');
            $table->string('waktu_pembayaran');
            $table->string('invoice_instalasi')->nullable();
            $table->string('invoice_reguler');
            $table->enum('mata_uang', ['IDR'])->default('IDR');
            $table->decimal('biaya_reguler', 12, 2);
            $table->boolean('kenakan_ppn')->default(false);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penagihan');
    }
};