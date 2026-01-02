<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('layanan_entry_id')->constrained('layanan_entry')->onDelete('cascade');
            $table->string('homepass')->nullable();
            $table->date('mulai_kontrak')->nullable();
            $table->date('selesai_kontrak')->nullable();
            $table->boolean('perjanjian_trial')->default(false);
            $table->string('email_alternatif_1')->nullable();
            $table->string('email_alternatif_2')->nullable();
            $table->boolean('pembelian_modem')->default(false);
            $table->integer('jumlah_tv_kabel')->default(0);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('layanan');
    }
};