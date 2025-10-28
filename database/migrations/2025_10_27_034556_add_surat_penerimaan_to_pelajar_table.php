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
            $table->string('surat_penerimaan')->nullable()->after('surat_pengajuan');
        });
    }

    public function down()
    {
        Schema::table('pelajars', function (Blueprint $table) {
            $table->dropColumn('surat_penerimaan');
        });
    }
};
