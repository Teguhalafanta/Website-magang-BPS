<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisTable extends Migration
{
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')
                ->references('id_pelajar')
                ->on('mahasiswas')
                ->onDelete('cascade');

            $table->string('nama_mahasiswa')->nullable();
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alfa']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensis');
    }
}
