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
        Schema::create('ketpub', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id'); // Menambahkan kolom user_id
            $table->string('nomorSurat')->nullable();
            $table->string('kategori_publikasi')->nullable();
            $table->string('statusSurat')->nullable();        
            $table->string('judul');
            $table->string('namaPenerbit');
            $table->string('penerbit');
            $table->bigInteger('Jilid');
            $table->string('Edisi');
            $table->string('bulan');
            $table->string('akreditas');
            $table->string('issn');
            $table->string('tahun');
            $table->date('tanggal');

            $table->timestamps();
             // Menambahkan foreign key constraint
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ketpub', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('ketpub');
    }
};
