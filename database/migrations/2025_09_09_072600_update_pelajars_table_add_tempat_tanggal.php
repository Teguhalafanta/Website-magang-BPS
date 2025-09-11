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
        Schema::table('pelajars', function (Blueprint $table) {
            if (!Schema::hasColumn('pelajars', 'tempat_lahir')) {
                $table->string('tempat_lahir')->after('jenis_kelamin');
            }

            if (!Schema::hasColumn('pelajars', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->after('tempat_lahir');
            }
        });
    }
};
