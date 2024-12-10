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
