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
        Schema::table('praktikans', function (Blueprint $table) {
            // Menambahkan kolom email
            $table->foreignId('frekuensi_id')->after('user_id')->nullable();

            $table->foreign('frekuensi_id')->references('id')->on('frekuensis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('praktikans', function (Blueprint $table) {
            // Menghapus kolom email
            $table->dropColumn('frekuensi_id');
        });
    }
};
