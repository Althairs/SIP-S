<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendaftaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mahasiswa_id',
        'jurusan_id',
        'prodi_id',
        'jenis_ujian',
        'judul_penelitian',
        'abstrak',
        // HAPUS 'bidang_keahlian' dari fillable karena sekarang jadi relasi
        'file_proposal',
        'file_skripsi',
        'file_persetujuan',
        'file_krs',
        'file_transkrip',
        'file_bukti_bimbingan',
        'status',
        'tanggal_ujian',
        'ruangan',
        'sesi',
        'nilai_total',
        'grade',
        'catatan_revisi',
        'catatan_penguji',
        'approved_at',
        'scheduled_at',
        'completed_at',
    ];

    protected $casts = [
        'tanggal_ujian' => 'datetime',
        'nilai_total' => 'decimal:2',
        'approved_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Relasi ke Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    // TAMBAHAN: Relasi ke Bidang Keahlian (many-to-many)
    public function bidangKeahlians()
    {
        return $this->belongsToMany(BidangKeahlian::class, 'pendaftaran_bidang_keahlian')
            ->withTimestamps();
    }

    // Relasi ke Dosen (many-to-many via pendaftaran_dosens)
    public function dosens()
    {
        return $this->hasMany(PendaftaranDosen::class);
    }

    // Get Pembimbing 1
    public function pembimbing1()
    {
        return $this->hasOne(PendaftaranDosen::class)->where('peran', 'pembimbing_1');
    }

    // Get Pembimbing 2
    public function pembimbing2()
    {
        return $this->hasOne(PendaftaranDosen::class)->where('peran', 'pembimbing_2');
    }

    // Get Penguji
    public function pengujis()
    {
        return $this->hasMany(PendaftaranDosen::class)->whereIn('peran', ['penguji_1', 'penguji_2', 'penguji_3']);
    }

    // Scope
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_ujian', $jenis);
    }

    // Helper untuk label status
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending' => 'Menunggu Verifikasi',
            'disetujui_panitia' => 'Disetujui Panitia',
            'ditolak_panitia' => 'Ditolak Panitia',
            'disetujui_sekjur' => 'Disetujui Sekjur',
            'ditolak_sekjur' => 'Ditolak Sekjur',
            'disetujui_kajur' => 'Disetujui Kajur',
            'ditolak_kajur' => 'Ditolak Kajur',
            'dijadwalkan' => 'Sudah Dijadwalkan',
            'selesai' => 'Selesai',
            'revisi' => 'Perlu Revisi',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'gray',
            'pending' => 'yellow',
            'disetujui_panitia' => 'blue',
            'ditolak_panitia' => 'red',
            'disetujui_sekjur' => 'green',
            'ditolak_sekjur' => 'red',
            'disetujui_kajur' => 'emerald',
            'ditolak_kajur' => 'red',
            'dijadwalkan' => 'purple',
            'selesai' => 'green',
            'revisi' => 'orange',
            default => 'gray',
        };
    }
}
