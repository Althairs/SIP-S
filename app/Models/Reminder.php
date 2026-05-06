<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'pendaftaran_id',
        'pengaturan_reminder_id',
        'judul',
        'pesan',
        'tipe',
        'prioritas',
        'tanggal_tampil',
        'tanggal_kadaluarsa',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'tanggal_tampil' => 'datetime',
        'tanggal_kadaluarsa' => 'datetime',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pengaturanReminder()
    {
        return $this->belongsTo(PengaturanReminder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('tanggal_tampil', '<=', now())
            ->where(function ($q) {
                $q->whereNull('tanggal_kadaluarsa')
                  ->orWhere('tanggal_kadaluarsa', '>=', now());
            });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
