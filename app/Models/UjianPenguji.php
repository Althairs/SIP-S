<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianPenguji extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'dosen_id',
        'peran',
        'bidang_keahlian_id',
        'kepakaran_id',
        'kuota_tersisa',
        'is_overload',
        'catatan',
    ];

    protected $casts = [
        'is_overload' => 'boolean',
        'kuota_tersisa' => 'integer',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function bidangKeahlian()
    {
        return $this->belongsTo(BidangKeahlian::class);
    }

    public function kepakaran()
    {
        return $this->belongsTo(Kepakaran::class);
    }
}
