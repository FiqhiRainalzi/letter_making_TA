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
        Schema::table('ketua', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ketua', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });
    }
};
