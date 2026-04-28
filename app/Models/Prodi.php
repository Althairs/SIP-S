<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'jurusan_id',
        'kode_prodi',
        'nama_prodi',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Jurusan
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Relasi ke Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope untuk prodi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
