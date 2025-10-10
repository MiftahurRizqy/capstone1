<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kategori_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique(); 
            $table->timestamps();
        });

        // OPTIONAL: Tambahkan data awal
        DB::table('kategori_pelanggan')->insert([
            ['nama' => 'Personal'],
            ['nama' => 'Perusahaan'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_pelanggan');
    }
};
