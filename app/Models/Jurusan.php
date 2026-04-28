<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurusan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Prodi
     */
    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }

    /**
     * Relasi ke Users (Mahasiswa, Dosen, dll dalam jurusan ini)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi ke Kajur
     */
    public function kajur()
    {
        return $this->hasOne(User::class)->whereHas('roles', function ($q) {
            $q->where('name', 'kajur');
        });
    }

    /**
     * Relasi ke Sekjur
     */
    public function sekjur()
    {
        return $this->hasOne(User::class)->whereHas('roles', function ($q) {
            $q->where('name', 'sekjur');
        });
    }

    /**
     * Scope untuk jurusan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
