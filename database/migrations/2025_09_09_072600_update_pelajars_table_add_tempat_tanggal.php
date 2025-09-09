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
            // jangan dropColumn kalau memang tidak ada
            if (Schema::hasColumn('pelajars', 'tempat_tanggal_lahir')) {
                $table->dropColumn('tempat_tanggal_lahir');
            }

            $table->string('tempat_lahir')->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
        });
    }
};
