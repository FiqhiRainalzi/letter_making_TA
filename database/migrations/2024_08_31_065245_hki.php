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
        $table->id('id_hki');
        $table->string('namaPemHki');
        $table->string('alamatPemHki');
        $table->string('judulInvensi');
        $table->string('namaInventor1');
        $table->string('bidangStudi1');
        $table->string('namaInventor2')->nullable();
        $table->string('bidangStudi2')->nullable();
        $table->string('namaInventor3')->nullable();
        $table->string('bidangStudi3')->nullable();
        $table->string('namaInventor4')->nullable();
        $table->string('bidangStudi4')->nullable();
        $table->string('namaInventor5')->nullable();
        $table->string('bidangStudi5')->nullable();
        $table->string('namaInventor6')->nullable();
        $table->string('bidangStudi6')->nullable();
        $table->string('namaInventor7')->nullable();
        $table->string('bidangStudi7')->nullable();
        $table->string('namaInventor8')->nullable();
        $table->string('bidangStudi8')->nullable();
        $table->date('tanggalPemHki');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hki');
    }
};
