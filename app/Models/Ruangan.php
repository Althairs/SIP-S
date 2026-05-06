<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'jurusan_id',
        'kode_ruangan',
        'nama_ruangan',
        'lokasi',
        'kapasitas',
        'is_active',
        'deskripsi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
