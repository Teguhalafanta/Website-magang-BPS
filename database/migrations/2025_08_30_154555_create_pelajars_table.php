<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelajars', function (Blueprint $table) {
            $table->bigIncrements('id'); // primary key
            $table->unsignedBigInteger('user_id'); // foreign key ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('telepon')->nullable();
            $table->string('email')->unique();
            $table->string('nim_nisn')->unique();
            $table->string('asal_institusi');
            $table->string('fakultas')->nullable();
            $table->string('jurusan');
            $table->date('rencana_mulai');
            $table->date('rencana_selesai');
            $table->string('proposal')->nullable();
            $table->string('surat_pengajuan')->nullable();
            $table->string('status')->default('menunggu'); // menunggu, diterima, ditolak
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelajars');
    }
};
