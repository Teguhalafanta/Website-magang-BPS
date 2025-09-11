<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pelajars', function (Blueprint $table) {
            $table->string('proposal')->nullable();
            $table->string('surat_pengajuan')->nullable();
            $table->string('status')->default('menunggu'); // menunggu, diterima, ditolak
            $table->text('alasan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pelajars', function (Blueprint $table) {
            $table->dropColumn(['proposal', 'surat_pengajuan', 'status', 'alasan']);
        });
    }
};
