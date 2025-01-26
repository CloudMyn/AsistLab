<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Model Schedule merepresentasikan data jadwal asistensi.
 * Model ini berisi informasi tentang topik, tanggal, waktu mulai, waktu selesai, dan status jadwal.
 * Model ini juga memiliki relasi dengan asisten dan praktikan, serta kehadiran mereka.
 */
class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    public $fillable = [
        'user_id',
        'mata_kuliah_id',
        'date',
        'start_time',
        'end_time',
        'topic',
        'status',
        'room',
    ];

    /**
     * Menghandle event booting untuk model {@see Schedule}.
     *
     * - Saat membuat jadwal asistensi, maka asisten yang terkait dengan jadwal asistensi akan di set dengan user yang sedang login.
     * - Saat mengupdate jadwal asistensi, maka praktikan yang terkait dengan jadwal asistensi akan menerima notifikasi perubahan jadwal asistensi.
     */
    protected static function booting()
    {
        parent::booting();

        // Set asisten yang terkait dengan jadwal asistensi
        static::creating(function ($schedule) {
            $schedule->asisten()->associate(get_auth_user());
        });

        // Set praktikan yang terkait dengan jadwal asistensi
        static::updating(function ($schedule) {

            foreach ($schedule->attendances as $attendance) {
                $praktikan = $attendance->praktikan;

                // Notifikasikan ke pada praktikan terkait perubahan jadwal
                send_warning_notification(
                    title: 'Jadwal Asistensi',
                    message: 'Terdapat Perubahan Jadwal Asistensi, Silahkan cek jadwal kembali anda!',
                    users: $praktikan
                );
            }
        });
    }


    /**
     * Mengembalikan asisten yang terkait dengan jadwal asistensi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asisten()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mengembalikan relasi hasMany dengan model Attendance.
     *
     * Attendance merepresentasikan kehadiran praktikan terhadap jadwal asistensi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mengembalikan relasi hasMany dengan model RoomChat.
     *
     * RoomChat merepresentasikan percakapan yang terkait dengan jadwal asistensi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function room_chats()
    {
        return $this->hasMany(RoomChat::class);
    }

    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
