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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluhan_id')->nullable()->constrained('keluhan')->onDelete('cascade');
            $table->foreignId('spk_id')->nullable()->constrained('spk')->onDelete('cascade');
            $table->enum('type', ['sistem', 'keluhan', 'spk']);
            $table->string('title');
            $table->text('message');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
