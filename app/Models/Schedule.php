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


    protected static function booting()
    {
        parent::booting();

        static::creating(function ($schedule) {
            $schedule->status = 'OPEN';

            $schedule->praktikan()->associate(get_auth_user());
        });

        static::updating(function ($schedule) {

            foreach ($schedule->attendances as $attendance) {
                $praktikan = $attendance->praktikan;

                send_warning_notification(
                    title: 'Jadwal Asistensi',
                    message: 'Terdapat Perubahan Jadwal Asistensi, Silahkan cek jadwal kembali anda!',
                    users: $praktikan
                );
            }
        });
    }

    public function praktikan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
