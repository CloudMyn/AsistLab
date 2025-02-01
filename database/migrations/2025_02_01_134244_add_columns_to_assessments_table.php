<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('assessment_schedules', function (Blueprint $table) {
            // Menambahkan kolom foreign key dosen_id yang bisa bernilai null
            $table->foreignId('dosen_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Mengembalikan perubahan migrasi.
     */
    public function down(): void
    {
        Schema::table('assessment_schedules', function (Blueprint $table) {
            // Menghapus constraint foreign key beserta kolomnya
            $table->dropConstrainedForeignId('dosen_id');
        });
    }
};
