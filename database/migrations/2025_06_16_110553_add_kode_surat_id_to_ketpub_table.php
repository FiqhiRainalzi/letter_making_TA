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
        Schema::table('ketpub', function (Blueprint $table) {
            $table->foreignId('kode_surat_id')->nullable()->constrained('kode_surat')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ketpub', function (Blueprint $table) {
            $table->dropForeign(['kode_surat_id']);
            $table->dropColumn('kode_surat_id');
        });
    }
};
