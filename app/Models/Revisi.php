<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'dosen_id',
        'ujian_penguji_id',
        'peran_pemberi',
        'isi_revisi',
        'kategori',
        'status',
        'file_revisi_mahasiswa',
        'catatan_mahasiswa',
        'uploaded_at',
        'is_approved',
        'approved_at',
        'catatan_dosen',
        'deadline',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'uploaded_at' => 'datetime',
        'approved_at' => 'datetime',
        'deadline' => 'date',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function ujianPenguji()
    {
        return $this->belongsTo(UjianPenguji::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    public function getKategoriColorAttribute()
    {
        return match($this->kategori) {
            'minor' => 'blue',
            'major' => 'amber',
            'kritis' => 'red',
            default => 'gray',
        };
    }

    public function getKategoriLabelAttribute()
    {
        return match($this->kategori) {
            'minor' => 'Minor',
            'major' => 'Mayor',
            'kritis' => 'Kritis',
            default => 'Unknown',
        };
    }
}
