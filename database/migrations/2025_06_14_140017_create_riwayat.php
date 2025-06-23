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
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_surat_id')->constrained('ajuan_surat')->onDelete('cascade');
            $table->string('aksi'); // Contoh: "Mengajukan Surat", "Memperbarui Surat"
            $table->string('diubah_oleh'); // Nama user yang melakukan aksi
            $table->text('catatan')->nullable(); // Informasi tambahan
            $table->timestamp('waktu_perubahan')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventor', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['ajuan_surat']);
        });
        Schema::dropIfExists('riwayat');
    }
};
