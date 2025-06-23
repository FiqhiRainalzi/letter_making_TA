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
        Schema::create('pkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ajuan_surat_id')->constrained('ajuan_surat')->onDelete('cascade');
            $table->string('nomorSurat')->nullable();
            $table->string('namaKetua');
            $table->string('nipNidn');
            $table->string('jabatanAkademik');
            $table->string('jurusanProdi');
            $table->string('judul');
            $table->string('skim');
            $table->string('dasarPelaksanaan');
            $table->string('lokasi');
            $table->date('bulanPelaksanaan');
            $table->date('bulanAkhirPelaksanaan');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('hki', function (Blueprint $table) {
            $table->dropForeign(['ajuan_surat_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('pkm');
    }
};
