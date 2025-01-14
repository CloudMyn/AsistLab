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
        // Tabel ini berisi data jadwal asistensi.
        // Jadwal asistensi ini di buat oleh asisten dan diatur berdasarkan tanggal, waktu mulai, waktu selesai, topik, dan ruangan.
        // Jadwal asistensi ini juga memiliki status yang dapat berupa 'SCHEDULED', 'CANCELLED', atau 'COMPLETED'.
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('topic');
            $table->string('room');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Asisten
            $table->enum('status', ['SCHEDULED', 'CANCELLED', 'COMPLETED'])->default('SCHEDULED');
            $table->timestamps();

            // Jadwal asistensi yang sama tidak dapat di buat dua kali
            $table->unique(['date', 'start_time', 'end_time', 'room']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
