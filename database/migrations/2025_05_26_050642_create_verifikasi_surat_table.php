<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verifikasi_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_surat_id')->constrained('ajuan_surat')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('verified_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('verifikasi_surat', function (Blueprint $table) {
            $table->dropForeign(['ajuan_surat_id']);
            $table->dropForeign(['petugas_id']);
        });

        Schema::dropIfExists('verifikasi_surat');
    }
};
