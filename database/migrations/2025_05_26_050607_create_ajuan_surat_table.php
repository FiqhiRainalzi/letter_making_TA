<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_surat', ['hki', 'penelitian', 'pengabdian', 'tugaspub', 'ketpub']);
            $table->enum('status', ['diajukan', 'disetujui', 'sudah ditandatangani', 'siap diambil', 'diambil'])->default('diajukan');

            // Penandatanganan oleh ketua
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('signed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('ajuan_surat', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['signed_by']);
        });

        Schema::dropIfExists('ajuan_surat');
    }
};
