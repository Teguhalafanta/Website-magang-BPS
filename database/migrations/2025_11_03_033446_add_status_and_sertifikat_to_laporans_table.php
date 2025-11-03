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
        Schema::table('laporans', function (Blueprint $table) {

            if (!Schema::hasColumn('laporans', 'status')) {
                $table->string('status')->default('menunggu');
            }

            if (!Schema::hasColumn('laporans', 'file_sertifikat')) {
                $table->string('file_sertifikat')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (Schema::hasColumn('laporans', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('laporans', 'file_sertifikat')) {
                $table->dropColumn('file_sertifikat');
            }
        });
    }
};
