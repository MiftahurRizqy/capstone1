<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori_pelanggan', function (Blueprint $table) {
            // Menyimpan konfigurasi field yang digunakan per kategori
            $table->json('personal_fields')->nullable()->after('nama');
            $table->json('perusahaan_fields')->nullable()->after('personal_fields');
        });
    }

    public function down(): void
    {
        Schema::table('kategori_pelanggan', function (Blueprint $table) {
            $table->dropColumn(['personal_fields', 'perusahaan_fields']);
        });
    }
};
