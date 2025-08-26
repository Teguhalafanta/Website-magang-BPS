<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id(); // Id User (auto-increment primary key)
            $table->string('nama');
            $table->string('nim_nisn')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('instansi'); // Universitas / Instansi / Sekolah
            $table->string('jurusan'); // Fakultas / Jurusan (SMA/SMK)
            $table->string('prodi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi_magang');
            $table->year('tahun_magang');
            $table->string('bulan_mulai');
            $table->string('bulan_selesai');
            $table->text('link_laporan');
            $table->text('link_sertifikat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
