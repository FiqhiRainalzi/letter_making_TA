<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenulisTable extends Migration
{
    public function up(): void
    {
        Schema::create('penulis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ketpub_id')->nullable();
            $table->unsignedBigInteger('tugaspub_id')->nullable();
            $table->string('nama');
            $table->string('jurusan_prodi')->nullable();
            $table->timestamps();

            //foreign key ketpub
            $table->foreign('ketpub_id')->references('id')->on('ketpub')->onDelete('cascade');
            //foreign key tugaspub
            $table->foreign('tugaspub_id')->references('id')->on('tugaspub')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penulis');
    }
}
