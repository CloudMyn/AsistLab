<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    public $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'topic',
        'status',
        'room',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
