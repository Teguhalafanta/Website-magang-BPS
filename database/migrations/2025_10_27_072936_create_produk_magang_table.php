<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk_magang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelajar_id');
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->string('file_produk');
            $table->timestamps();

            $table->foreign('pelajar_id')->references('id')->on('pelajars')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_magang');
    }
};
