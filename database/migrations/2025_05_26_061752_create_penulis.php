<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penulis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ketpub_id')->nullable();
            $table->unsignedBigInteger('tugaspub_id')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->string('nama');
            $table->timestamps();

            //foreign key ketpub
            $table->foreign('ketpub_id')->references('id')->on('ketpub')->onDelete('cascade');
            //foreign key tugaspub
            $table->foreign('tugaspub_id')->references('id')->on('tugaspub')->onDelete('cascade');
            //foreign key tugaspub
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('penulis', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['ketpub_id']);
            $table->dropForeign(['tugaspub_id']);
            $table->dropForeign(['prodi_id']);
        });
        Schema::dropIfExists('penulis');
    }
};
