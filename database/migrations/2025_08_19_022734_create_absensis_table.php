<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisTable extends Migration
{
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
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
