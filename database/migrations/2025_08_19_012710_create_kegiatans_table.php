<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key

            // Sesuaikan dengan primary key di tabel users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->string('nama_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->integer('volume')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('durasi')->nullable();
            $table->string('pemberi_tugas')->nullable();
            $table->string('tim_kerja')->nullable();
            
            $table->enum('status_penyelesaian', ['Belum Dimulai', 'Dalam Proses', 'Selesai'])
            ->default('Belum Dimulai');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
