<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidangKeahlian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'jurusan_id',
        'kode',
        'nama_bidang',
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
     * Relasi ke Pendaftaran (many-to-many)
     */
    public function pendaftarans()
    {
        return $this->belongsToMany(Pendaftaran::class, 'pendaftaran_bidang_keahlian')
            ->withTimestamps();
    }

    /**
     * Scope aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by jurusan
     */
    public function scopeByJurusan($query, $jurusanId)
    {
        return $query->where('jurusan_id', $jurusanId);
    }
}
