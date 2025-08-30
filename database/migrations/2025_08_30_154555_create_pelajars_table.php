<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelajars', function (Blueprint $table) {
            $table->bigIncrements('id_pelajar'); // primary key
            $table->unsignedBigInteger('id_user'); // foreign key ke users

            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_tanggal_lahir');
            $table->text('alamat');
            $table->string('telepon')->nullable();
            $table->string('email')->unique();
            $table->string('nim_nisn')->unique();
            $table->string('asal_institusi');
            $table->string('fakultas')->nullable();
            $table->string('jurusan');
            $table->date('rencana_mulai');
            $table->date('rencana_selesai');
            $table->timestamps();

            // foreign key ke users.id_user
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelajars');
    }
};

