<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    public $fillable = [
        'schedule_id',
        'user_id',
        'present'
    ];

    protected static function booting()
    {
        parent::booting();

        static::creating(function ($attendance) {
            send_info_notification(
                title: 'Jadwal Asistensiu',
                message: 'Anda telah mengikuti jadwal asistensi',
                users: [$attendance->praktikan]
            );
        });

        static::deleting(function ($attendance) {
            send_danger_notification(
                title: 'Jadwal Asistensiu',
                message: 'Jadwal asistensi anda pada tanggal ' . $attendance->schedule->date . ' telah dihapus',
                users: [$attendance->praktikan]
            );
        });
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function praktikan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }
}
