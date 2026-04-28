<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranDosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'dosen_id',
        'peran',
        'is_approved',
        'catatan',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
