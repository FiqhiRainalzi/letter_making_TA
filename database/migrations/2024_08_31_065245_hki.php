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
            $table->string('namaPemegang');
            $table->string('alamat');
            $table->string('nomorSurat')->nullable();
            $table->string('statusSurat')->nullable();
            $table->string('judul');
            $table->string('inventor1');
            $table->string('inventor2')->nullable();
            $table->string('inventor3')->nullable();
            $table->string('inventor4')->nullable();
            $table->string('inventor5')->nullable();
            $table->string('inventor6')->nullable();
            $table->string('inventor7')->nullable();
            $table->string('inventor8')->nullable();
            $table->string('inventor9')->nullable();
            $table->string('inventor10')->nullable();
            $table->string('bidangStudi1');
            $table->string('bidangStudi2')->nullable();
            $table->string('bidangStudi3')->nullable();
            $table->string('bidangStudi4')->nullable();
            $table->string('bidangStudi5')->nullable();
            $table->string('bidangStudi6')->nullable();
            $table->string('bidangStudi7')->nullable();
            $table->string('bidangStudi8')->nullable();
            $table->string('bidangStudi9')->nullable();
            $table->string('bidangStudi10')->nullable();
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
        Schema::table('hki', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('hki');
    }
};
