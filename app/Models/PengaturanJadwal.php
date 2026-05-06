<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanJadwal extends Model
{
    protected $fillable = [
        'jurusan_id',
        'jam_mulai',
        'jam_selesai',
        'label_sesi',
        'is_active',
    ];

    protected $casts = [
        'jam_mulai' => 'array',
        'jam_selesai' => 'array',
        'label_sesi' => 'array',
        'is_active' => 'boolean',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public static function getDefaultSesi($jurusanId)
    {
        $pengaturan = self::where('jurusan_id', $jurusanId)->where('is_active', true)->first();

        if ($pengaturan) {
            return [
                'jam_mulai' => $pengaturan->jam_mulai,
                'jam_selesai' => $pengaturan->jam_selesai,
                'labels' => $pengaturan->label_sesi,
            ];
        }

        // Default
        return [
            'jam_mulai' => ['08:00', '10:00', '13:00', '15:00'],
            'jam_selesai' => ['10:00', '12:00', '15:00', '17:00'],
            'labels' => ['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4'],
        ];
    }
}
