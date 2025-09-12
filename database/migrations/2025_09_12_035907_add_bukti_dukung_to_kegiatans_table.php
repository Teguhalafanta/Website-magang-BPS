<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            // Tambahkan kolom bukti_dukung (tipe data bisa disesuaikan)
            $table->string('bukti_dukung')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('bukti_dukung');
        });
    }
};
