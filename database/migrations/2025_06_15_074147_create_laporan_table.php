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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // pencetak
            $table->string('judul')->nullable();
            $table->text('catatan')->nullable();
            $table->string('jenis_surat')->nullable(); // 'penelitian', 'hki', dst
            $table->string('status_filter')->nullable(); // status filter seperti 'disetujui'
            $table->date('tanggal_dari')->nullable();
            $table->date('tanggal_sampai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('laporan');
    }
};
