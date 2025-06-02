<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pop', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pop');
            $table->string('kabupaten_kota');
            $table->string('daerah');
            $table->string('rt_rw');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pop');
    }
};