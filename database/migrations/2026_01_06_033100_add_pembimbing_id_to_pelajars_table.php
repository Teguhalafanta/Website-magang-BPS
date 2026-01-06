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
        // Schema::table('pelajars', function (Blueprint $table) {
        // $table->unsignedBigInteger('pembimbing_id')->nullable()->after('user_id');
        // $table->foreign('pembimbing_id')->references('id')->on('pembimbings')->onDelete('cascade');
        // });
        Schema::table('pelajars', function (Blueprint $table) {
            $table->foreignId('pembimbing_id')
                ->nullable()
                ->after('user_id')
                ->constrained('pembimbings')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelajars', function (Blueprint $table) {
            $table->dropForeign(['pembimbing_id']);
            $table->dropColumn('pembimbing_id');
        });
    }
};
