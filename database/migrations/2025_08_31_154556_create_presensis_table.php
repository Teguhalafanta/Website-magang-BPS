<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            // Kolom user_id
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Kolom pelajar_id (nullable jika tidak semua user punya pelajar)
            $table->unsignedBigInteger('pelajar_id')->nullable();
            $table->foreign('pelajar_id')->references('id')->on('pelajars')->onDelete('cascade');

            // Data presensi
            $table->date('tanggal');
            $table->time('waktu_datang')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->enum('status', ['Tepat Waktu', 'Terlambat'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
