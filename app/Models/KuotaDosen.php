<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuotaDosen extends Model
{
    protected $fillable = [
        'dosen_id',
        'jurusan_id',
        'kuota_pembimbing',
        'kuota_penguji',
        'terpakai_pembimbing',
        'terpakai_penguji',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function getSisaPembimbingAttribute()
    {
        return $this->kuota_pembimbing - $this->terpakai_pembimbing;
    }

    public function getSisaPengujiAttribute()
    {
        return $this->kuota_penguji - $this->terpakai_penguji;
    }

    public function getIsOverloadPengujiAttribute()
    {
        return $this->terpakai_penguji >= $this->kuota_penguji;
    }
}
