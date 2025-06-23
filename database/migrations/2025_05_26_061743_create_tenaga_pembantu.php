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
        Schema::create('tenaga_pembantu', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('penelitian_id')->nullable();
            $table->unsignedBigInteger('pkm_id')->nullable();
            $table->unsignedBigInteger('prodi_id');
            $table->timestamps();

            // Foreign key untuk penelitian_id
            $table->foreign('penelitian_id')->references('id')->on('penelitian')->onDelete('cascade');
            // Foreign key untuk pkm_id
            $table->foreign('pkm_id')->references('id')->on('pkm')->onDelete('cascade');
            // Foreign key untuk prodi_id
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_pembantu', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['penelitian_id']);
            $table->dropForeign(['pkm_id']);
        });
        Schema::dropIfExists('tenaga_pembantu');
    }
};
