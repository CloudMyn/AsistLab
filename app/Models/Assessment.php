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
        'attendance_id',
        'assessment_schedule_id',
        'score',
        'comments',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    protected static function booting()
    {
        parent::booting();

        static::created(function ($assessment) {
            // // Add logic to execute during the creating event

            // $model = AssessmentSchedule::where('schedule_id', $assessment->schedule_id)->first();

            // if (!$model) {

            //     $model = AssessmentSchedule::create([
            //         'schedule_id' => $assessment->schedule_id,
            //         'topik' => $assessment->schedule->topic,
            //         'mata_kuliah' => $assessment->schedule?->mata_kuliah?->nama,
            //         'frekuensi' => $assessment->attendance->praktikan->praktikan->frekuensi->name,
            //         'jadwal' => $assessment->schedule->date,
            //         'asisten' => $assessment->schedule->asisten->name,
            //         'asisten_id' => $assessment->schedule->user_id,
            //         'nilai' => $assessment->score,
            //         'approver_id' => null,
            //         'approved_at' => null,
            //     ]);
            // } else {
            //     $model->update([
            //         'nilai' => $assessment->score + $model->nilai,
            //     ]);
            // }

            // $assessment->update([
            //     'assessment_schedule_id' => $model->id
            // ]);
        });
    }

    public function assessmentSchedule()
    {
        return $this->belongsTo(AssessmentSchedule::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
