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
        Schema::create('keluhan', function (Blueprint $table) {
            $table->id('id_keluhan'); // Primary Key (pk)
            $table->foreignId('layanan_induk_id')->constrained('layanan_induk')->onDelete('cascade');
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade'); // Foreign Key (fk)
            $table->enum('tujuan', ['Technical Support', 'Maintenance Cable', 'Maintenance Wireless', 'E-Gov Kota', 'E-Gov Propinsi', 'NOC', 'TV Kabel', 'Helpdesk', 'Admin CLEON', 'Support CLEON', 'SYS Admin CLEON']);
            $table->enum('prioritas', ['low', 'medium', 'high']);
            $table->string('keluhan1');
            $table->string('keluhan2');
            $table->enum('jenis_spk', ['Tidak', 'SPK OSP', 'SPK VOIP', 'SPK TS'])->default('Tidak');
            $table->enum('via', ['Datang', 'Telpon/Fax', 'Email', 'SMS/WA/BBM/LINE']);
            $table->text('deskripsi');
            $table->text('penyelesaian');
            $table->string('disampaikan_oleh');
            $table->string('sumber');
            $table->timestamp('tanggal_input');
            $table->timestamps();

            // Tambahkan index jika diperlukan untuk performa
            $table->index('pelanggan_id');
            $table->index('layanan_induk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhan');
    }
};
