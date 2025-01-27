<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentScheduleFactory> */
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'mata_kuliah',
        'frekuensi',
        'jadwal',
        'asisten',
        'asisten_id',
        'topik',
        'nilai',
        'approver_id',
        'approved_at',
    ];


    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function assessment()
    {
        return $this->hasMany(Assessment::class);
    }
}
