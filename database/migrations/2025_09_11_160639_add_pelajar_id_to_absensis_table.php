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
        Schema::table('absensis', function (Blueprint $table) {
            $table->unsignedBigInteger('pelajar_id')->after('user_id');

            // Jika ingin tambah foreign key, tambahkan:
            $table->foreign('pelajar_id')->references('pelajar_id')->on('pelajars')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['pelajar_id']);
            $table->dropColumn('pelajar_id');
        });
    }
};
