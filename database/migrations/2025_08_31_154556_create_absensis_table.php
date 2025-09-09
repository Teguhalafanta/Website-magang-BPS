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

            $table->unsignedBigInteger('user_id');  // wajib ada ini
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->string('nama_pelajar')->nullable();
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
