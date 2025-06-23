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
        Schema::create('tugaspub', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ajuan_surat_id')->constrained('ajuan_surat')->onDelete('cascade');
            $table->string('kategori_jurnal')->nullable();
            $table->string('nomorSurat')->nullable();
            $table->string('namaPublikasi');
            $table->string('penerbit');
            $table->string('alamat');
            $table->string('link');
            $table->string('volume');
            $table->string('nomor');
            $table->string('bulan');
            $table->string('akreditas')->nullable();
            $table->string('issn');
            $table->string('judul');
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
        Schema::dropIfExists('tugaspub');
    }
};
