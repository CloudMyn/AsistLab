<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentFactory> */
    use HasFactory;

    public $fillable = [
        'schedule_id',
        'user_id',
        'score',
        'comments',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function praktikan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
