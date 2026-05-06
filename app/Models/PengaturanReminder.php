<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanReminder extends Model
{
    protected $fillable = [
        'jurusan_id',
        'jenis_ujian',
        'is_active',
        'reminder_settings',
        'deadline_days',
        'pesan_template',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'reminder_settings' => 'array',
        'deadline_days' => 'integer',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public static function getSettings($jurusanId, $jenisUjian)
    {
        $setting = self::where('jurusan_id', $jurusanId)
            ->where('jenis_ujian', $jenisUjian)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return [
                'deadline_days' => 30,
                'reminders' => [
                    ['type' => 'daily', 'interval' => 5, 'label' => 'Setiap 5 Hari'],
                    ['type' => 'before_deadline', 'days' => 7, 'label' => 'H-7 Deadline'],
                    ['type' => 'before_deadline', 'days' => 3, 'label' => 'H-3 Deadline'],
                    ['type' => 'before_deadline', 'days' => 1, 'label' => 'H-1 Deadline'],
                ]
            ];
        }

        return [
            'deadline_days' => $setting->deadline_days,
            'reminders' => $setting->reminder_settings ?? [],
            'pesan_template' => $setting->pesan_template,
        ];
    }
}
