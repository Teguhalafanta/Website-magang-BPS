<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            if (!Schema::hasColumn('absensis', 'status')) {
                $table->string('status')->nullable()->after('mahasiswa_id');
            }
            // jangan tambahkan keterangan karena sudah ada
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('keterangan');
            // Kalau kamu menambahkan kolom status di migrasi ini, bisa drop juga
            // $table->dropColumn('status');
        });
    }
};
