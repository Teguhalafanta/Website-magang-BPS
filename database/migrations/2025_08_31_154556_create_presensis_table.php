<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensisTable extends Migration
{
    public function up()
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            // Kolom user_id
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Kolom pelajar_id
            $table->unsignedBigInteger('pelajar_id');
            $table->foreign('pelajar_id')->references('id')->on('pelajars')->onDelete('cascade');

            // Data presensi
            $table->date('tanggal');                     // tanggal presensi
            $table->time('waktu_datang')->nullable();    // waktu datang
            $table->time('waktu_pulang')->nullable();    // waktu pulang
            $table->enum('status', ['Tepat Waktu', 'Terlambat'])->nullable(); // status otomatis

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('presensis');
    }
}
