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
        Schema::create('inventor', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('hki_id')->nullable();
            $table->unsignedBigInteger('prodi_id');
            $table->timestamps();

            // Foreign key untuk hki_id
            $table->foreign('hki_id')->references('id')->on('hki')->onDelete('cascade');
            // Foreign key untuk prodi_id
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventor', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['hki_id']);
        });
        Schema::dropIfExists('inventor');
    }
};
