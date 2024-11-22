<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMessageFactory> */
    use HasFactory;

    public $fillable = [
        'user_id',
        'room_chat_id',
        'message',
    ];

    public function roomChat()
    {
        return $this->belongsTo(RoomChat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
