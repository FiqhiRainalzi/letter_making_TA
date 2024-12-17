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
        Schema::create('hki', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id'); // Menambahkan kolom user_id
            $table->string('namaPemHki');
            $table->string('alamatPemHki');
            $table->string('nomorSurat')->nullable();
            $table->string('statusSurat')->nullable();
            $table->string('judulInvensi');
            $table->date('tanggalPemHki');
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
        Schema::table('hki', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('hki');
    }
};
