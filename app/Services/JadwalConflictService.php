<?php

namespace App\Services;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class JadwalConflictService
{
    /**
     * Memvalidasi jadwal pendaftaran sebelum disimpan.
     * Akan melempar ValidationException jika ada konflik.
     */
    public function validateSchedule(Pendaftaran $pendaftaran, $tanggal, $sesi, $ruangan)
    {
        $tanggalObj = Carbon::parse($tanggal)->startOfDay();
        
        $existingJadwals = Pendaftaran::where('status', 'dijadwalkan')
            ->whereDate('tanggal_ujian', $tanggalObj)
            ->where('sesi', $sesi)
            ->where('ruangan', $ruangan)
            ->where('id', '!=', $pendaftaran->id)
            ->get();

        if ($existingJadwals->isEmpty()) {
            return;
        }

        // Jika ada jadwal lain di sana
        if (in_array($pendaftaran->jenis_ujian, ['seminar_hasil', 'sidang_skripsi'])) {
            throw ValidationException::withMessages([
                'tanggal_ujian' => 'Terdapat konflik jadwal. Ruangan dan sesi tersebut sudah terisi.',
            ]);
        }

        // Khusus proposal
        foreach ($existingJadwals as $existing) {
            if ($existing->jenis_ujian !== 'seminar_proposal') {
                throw ValidationException::withMessages([
                    'tanggal_ujian' => 'Ruangan dan sesi ini sudah digunakan oleh ujian ' . str_replace('_', ' ', $existing->jenis_ujian) . '.',
                ]);
            }
        }

        // Cek grouping proposal
        $pendaftaranIds = $existingJadwals->pluck('id')->push($pendaftaran->id)->toArray();
        if (!$this->canGroupProposal($pendaftaranIds)) {
            throw ValidationException::withMessages([
                'tanggal_ujian' => 'Grup Proposal tidak kompatibel (Penguji 1 & 2 harus identik, dan minimal 1 pembimbing sama).',
            ]);
        }
    }

    /**
     * Mengecek apakah array ID pendaftaran proposal kompatibel untuk digrup.
     */
    public function canGroupProposal(array $pendaftaranIds)
    {
        if (count($pendaftaranIds) <= 1) {
            return true;
        }

        $pendaftarans = Pendaftaran::with(['pengujis', 'dosens'])
            ->whereIn('id', $pendaftaranIds)
            ->get();

        if ($pendaftarans->count() < count($pendaftaranIds)) {
            return false;
        }

        // Ambil elemen referensi (pendaftaran pertama)
        $firstPendaftaran = $pendaftarans->first();
        
        $basePenguji = $firstPendaftaran->pengujis
            ->whereIn('peran', ['penguji_1', 'penguji_2'])
            ->pluck('dosen_id')
            ->sort()
            ->values()
            ->toArray();

        $basePembimbing = $firstPendaftaran->dosens
            ->whereIn('peran', ['pembimbing_1', 'pembimbing_2'])
            ->pluck('dosen_id')
            ->toArray();

        // Pastikan pendaftaran pertama punya penguji (minimal 1 penguji_1 atau penguji_2)
        if (empty($basePenguji)) {
            return false; 
        }

        foreach ($pendaftarans as $index => $pendaftaran) {
            if ($index === 0) continue;

            // 1. Cek penguji (harus sama persis penguji_1 dan penguji_2 dengan referensi)
            $currentPenguji = $pendaftaran->pengujis
                ->whereIn('peran', ['penguji_1', 'penguji_2'])
                ->pluck('dosen_id')
                ->sort()
                ->values()
                ->toArray();

            if ($basePenguji !== $currentPenguji) {
                return false;
            }

            // 2. Cek pembimbing (minimal 1 pembimbing sama)
            $currentPembimbing = $pendaftaran->dosens
                ->whereIn('peran', ['pembimbing_1', 'pembimbing_2'])
                ->pluck('dosen_id')
                ->toArray();
            
            $intersect = array_intersect($basePembimbing, $currentPembimbing);
            if (empty($intersect)) {
                return false;
            }
        }

        return true;
    }
}
