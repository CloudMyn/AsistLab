<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frekuensi extends Model
{
    /** @use HasFactory<\Database\Factories\FrekuensiFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'mata_kuliah_id'
    ];

    /**
     * Get the praktikans associated with the Frekuensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Praktikan>
     */
    public function praktikans()
    {
        return $this->hasMany(Praktikan::class);
    }

    /**
     * Mata kuliah yang diampu oleh praktikan ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
