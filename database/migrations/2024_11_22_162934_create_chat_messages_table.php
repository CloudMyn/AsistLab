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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_chat_id')->constrained('room_chats')->onDelete('cascade'); // Menghubungkan dengan room_chat
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pengirim pesan
            $table->text('message'); // Isi pesan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
