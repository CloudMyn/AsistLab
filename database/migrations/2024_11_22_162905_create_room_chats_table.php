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
        Schema::create('room_chats', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 16)->unique(); // Nama ruang chat
            $table->foreignId('schedule_id');
            $table->foreignId('user_id_asisten');
            $table->foreignId('user_id_praktikan');
            $table->timestamps();

            $table->unique(['schedule_id', 'user_id_asisten', 'user_id_praktikan']);

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('user_id_asisten')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_praktikan')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_chats');
    }
};
