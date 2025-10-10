<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pelanggan')->unique();
            $table->string('member_card')->unique();
            $table->foreignId('kategori_pelanggan_id')->constrained('kategori_pelanggan') ->onDelete('restrict');

            // Field umum
            $table->foreignId('pop_id')->constrained('pop')->onDelete('cascade');
            $table->text('alamat');
            $table->string('kode_pos');
            $table->string('kabupaten');
            $table->string('kota');
            $table->string('wilayah');
            $table->string('no_hp');
            $table->string('nama_kontak');
            $table->string('tipe_identitas')->nullable();
            $table->string('nomor_identitas')->nullable();
            $table->boolean('reseller')->default(false);

            // Field pelanggan personal
            $table->string('nama_lengkap')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('pekerjaan')->nullable();

            // Field pelanggan perusahaan
            $table->string('nama_perusahaan')->nullable();
            $table->string('jenis_usaha')->nullable();
            $table->string('account_manager')->nullable();
            $table->string('telepon_perusahaan')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
};
