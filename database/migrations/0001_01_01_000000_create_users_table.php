<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('nim')->primary(); // primary key
            $table->string('username')->unique()->nullable();
            $table->string('nama');
            $table->string('asal_univ');
            $table->string('jurusan');
            $table->string('prodi');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'mahasiswa', 'dosen'])->default('mahasiswa'); // kolom role
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
