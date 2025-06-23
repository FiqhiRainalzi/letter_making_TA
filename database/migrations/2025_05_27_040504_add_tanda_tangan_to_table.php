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
        Schema::table('hki', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });

        Schema::table('penelitian', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });

        Schema::table('pkm', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });

        Schema::table('ketpub', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });

        Schema::table('tugaspub', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('hki', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });

        Schema::table('penelitian', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });

        Schema::table('pkm', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });

        Schema::table('ketpub', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });

        Schema::table('tugaspub', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });
    }
};
