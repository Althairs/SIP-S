<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'dosen_id',
        'ujian_penguji_id',
        'peran_pemberi',
        'tipe_input',
        'presentasi',
        'penguasaan',
        'menjawab',
        'deskripsi',
        'analisis',
        'menyimpulkan',
        'implikasi',
        'nilai_akhir',
        'nilai_huruf',
        'predikat',
        'file_penilaian',
        'catatan',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'presentasi' => 'decimal:2',
        'penguasaan' => 'decimal:2',
        'menjawab' => 'decimal:2',
        'deskripsi' => 'decimal:2',
        'analisis' => 'decimal:2',
        'menyimpulkan' => 'decimal:2',
        'implikasi' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    // Bobot penilaian
    const BOBOT = [
        'presentasi' => 0.10,    // 10%
        'penguasaan' => 0.15,    // 15%
        'menjawab' => 0.10,      // 10%
        'deskripsi' => 0.10,     // 10%
        'analisis' => 0.20,      // 20%
        'menyimpulkan' => 0.15,  // 15%
        'implikasi' => 0.20,     // 20%
    ];

    // Label komponen
    const LABEL = [
        'presentasi' => 'Presentasi Karya Ilmiah',
        'penguasaan' => 'Penguasaan Materi',
        'menjawab' => 'Cara Menjawab',
        'deskripsi' => 'Daya Deskripsi',
        'analisis' => 'Daya Analisis',
        'menyimpulkan' => 'Daya Menyimpulkan',
        'implikasi' => 'Daya Implikasi',
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

    /**
     * Hitung Nilai Akhir berdasarkan komponen
     */
    public static function hitungNilaiAkhir($nilaiKomponen)
    {
        $na = 0;
        foreach (self::BOBOT as $aspek => $bobot) {
            $nilai = $nilaiKomponen[$aspek] ?? 0;
            $na += $nilai * $bobot;
        }
        return round($na, 2);
    }

    /**
     * Konversi Nilai Akhir ke Nilai Huruf & Predikat
     */
    public static function konversiNilai($na)
    {
        if ($na > 85) {
            return ['huruf' => 'A', 'predikat' => 'Sangat Baik'];
        } elseif ($na >= 70) {
            return ['huruf' => 'B', 'predikat' => 'Baik'];
        } elseif ($na >= 55) {
            return ['huruf' => 'C', 'predikat' => 'Cukup'];
        } elseif ($na >= 50) {
            return ['huruf' => 'D', 'predikat' => 'Kurang'];
        } else {
            return ['huruf' => 'E', 'predikat' => 'Gagal (Tidak Lulus)'];
        }
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'selesai' => 'green',
            'diverifikasi' => 'blue',
            default => 'gray',
        };
    }
}
