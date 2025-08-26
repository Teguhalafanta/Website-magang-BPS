<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganToAbsensisTable extends Migration
{
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('keterangan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
}

