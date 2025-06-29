<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Menghubungkan dengan user
            $table->string('title');
            $table->text('message');
            $table->enum('status', ['unread', 'read'])->default('unread'); // Status notifikasi
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('notifications');
    }
};
