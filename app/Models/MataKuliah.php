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
    ];

    protected $width = ['frekuensi'];

    public function frekuensi()
    {
        return $this->hasMany(Frekuensi::class, 'mata_kuliah_id', 'id');
    }
}
