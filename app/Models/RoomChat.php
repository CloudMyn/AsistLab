<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChat extends Model
{
    /** @use HasFactory<\Database\Factories\RoomChatFactory> */
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id_asisten',
        'user_id_praktikan',
    ];


    public function asisten()
    {
        return $this->belongsTo(User::class, 'user_id_asisten');
    }

    public function praktikan()
    {
        return $this->belongsTo(User::class, 'user_id_praktikan');
    }
}
