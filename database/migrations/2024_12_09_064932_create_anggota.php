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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pkm_id')->nullable(); // Foreign key ke PKM
            $table->unsignedBigInteger('penelitian_id')->nullable(); // Foreign key ke Penelitian
            $table->string('nama')->nullable(); // Nama anggota
            $table->string('prodi')->nullable(); // Nama anggota
            $table->timestamps();

            // Foreign key ke tabel PKM
            $table->foreign('pkm_id')->references('id')->on('pkm')->onDelete('cascade');
            // Foreign key ke tabel Penelitian
            $table->foreign('penelitian_id')->references('id')->on('penelitian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
