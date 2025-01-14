<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frekuensi extends Model
{
    /** @use HasFactory<\Database\Factories\FrekuensiFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function praktikans()
    {
        return $this->hasMany(Praktikan::class);
    }

}
