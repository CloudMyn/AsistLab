<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    /** @use HasFactory<\Database\Factories\MataKuliahFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'tahun_akademik',
        'semester',
    ];

    protected $width = ['frekuensi'];

    protected static function booting()
    {
        static::deleting(function ($model) {
            foreach ($model->frekuensi as $frekuensi) {
                $frekuensi->update([
                    'mata_kuliah_id'  =>  null,
                ]);
            }
        });
    }

    public function frekuensi()
    {
        return $this->hasMany(Frekuensi::class, 'mata_kuliah_id', 'id');
    }
}
