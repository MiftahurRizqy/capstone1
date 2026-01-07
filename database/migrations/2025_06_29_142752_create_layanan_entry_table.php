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
        Schema::create('layanan_entry', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama_paket');
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->enum('tipe', ['TV', 'Internet', 'Lain-Lain']);
            $table->enum('kelompok_layanan', ['Layanan Dasar', 'Web Hosting', 'Colocation']);
            // KOLOM BARU: foreign key ke layanan_induk
            $table->foreignId('layanan_induk_id')->nullable()->constrained('layanan_induk')->onDelete('set null'); // Relasi ke tabel layanan_induk

            // SPK Checkboxes
            $table->boolean('spk_osp_instalasi')->default(false);
            $table->boolean('spk_isp_instalasi')->default(false);
            $table->boolean('spk_osp_aktif_kembali')->default(false);
            $table->boolean('spk_isp_aktif_kembali')->default(false);

            $table->enum('tipe_layanan_spk', ['TV', 'Internet HFC', 'Internet Wireless', 'Wi TV', 'TV-DTH'])->nullable();
            $table->text('konfigurasi_dhcp')->nullable();
            $table->integer('utilisasi_bandwidth')->nullable()->comment('kbps/pelanggan');
            $table->decimal('biaya_setup', 15, 2)->default(0)->nullable();
            $table->decimal('biaya_reguler_1_bulan', 15, 2)->default(0)->nullable();
            $table->decimal('biaya_reguler_3_bulan', 15, 2)->default(0)->nullable();
            $table->enum('bonus_reguler_3_bulan', ['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'])->nullable();
            $table->decimal('biaya_reguler_6_bulan', 15, 2)->default(0)->nullable();
            $table->enum('bonus_reguler_6_bulan', ['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'])->nullable();
            $table->decimal('biaya_reguler_12_bulan', 15, 2)->default(0)->nullable();
            $table->enum('bonus_reguler_12_bulan', ['1 Bulan', '2 Bulan', '3 Bulan', '4 Bulan', '5 Bulan', '6 Bulan'])->nullable();
            $table->enum('koneksi_tv_kabel', ['Corporate TV', 'Layanan Lain'])->nullable();
            $table->enum('kompensasi_diskoneksi', ['Terima Kompensasi', 'Tidak Terima Kompensasi'])->nullable();
            $table->string('redaksional_invoice')->nullable();
            $table->string('redaksional_invoice_2')->nullable()->comment('khusus layanan Hosting');
            $table->string('account_myob_1')->nullable();
            $table->string('account_myob_2')->nullable();
            $table->string('nama_milis')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_entry');
    }
};