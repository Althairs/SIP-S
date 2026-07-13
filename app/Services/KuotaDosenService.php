<?php

namespace App\Services;

use App\Models\Jurusan;
use App\Models\KuotaDosen;
use App\Models\User;
use Illuminate\Support\Carbon;

class KuotaDosenService
{
    public const DEFAULT_KUOTA_PEMBIMBING = 20;

    public const DEFAULT_KUOTA_PENGUJI = 20;

    public function defaultsForJurusan(Jurusan $jurusan): array
    {
        return [
            'kuota_pembimbing' => $jurusan->default_kuota_pembimbing ?? self::DEFAULT_KUOTA_PEMBIMBING,
            'kuota_penguji' => $jurusan->default_kuota_penguji ?? self::DEFAULT_KUOTA_PENGUJI,
        ];
    }

    public function resetBulananForJurusan(Jurusan $jurusan): int
    {
        $defaults = $this->defaultsForJurusan($jurusan);
        $updated = 0;

        $dosens = User::role('dosen')->where('jurusan_id', $jurusan->id)->pluck('id');

        foreach ($dosens as $dosenId) {
            KuotaDosen::updateOrCreate(
                ['dosen_id' => $dosenId],
                [
                    'jurusan_id' => $jurusan->id,
                    'kuota_pembimbing' => $defaults['kuota_pembimbing'],
                    'kuota_penguji' => $defaults['kuota_penguji'],
                    'terpakai_pembimbing' => 0,
                    'terpakai_penguji' => 0,
                ]
            );
            $updated++;
        }

        $jurusan->update(['kuota_last_reset_at' => Carbon::now()]);

        return $updated;
    }

    public function resetBulananAll(): int
    {
        $total = 0;

        Jurusan::active()->each(function (Jurusan $jurusan) use (&$total) {
            $total += $this->resetBulananForJurusan($jurusan);
        });

        return $total;
    }

    public function ensureKuotaForDosen(User $dosen): KuotaDosen
    {
        $jurusan = $dosen->jurusan;

        if (! $jurusan) {
            return KuotaDosen::updateOrCreate(
                ['dosen_id' => $dosen->id],
                [
                    'jurusan_id' => $dosen->jurusan_id,
                    'kuota_pembimbing' => self::DEFAULT_KUOTA_PEMBIMBING,
                    'kuota_penguji' => self::DEFAULT_KUOTA_PENGUJI,
                    'terpakai_pembimbing' => 0,
                    'terpakai_penguji' => 0,
                ]
            );
        }

        $defaults = $this->defaultsForJurusan($jurusan);

        return KuotaDosen::updateOrCreate(
            ['dosen_id' => $dosen->id],
            [
                'jurusan_id' => $jurusan->id,
                'kuota_pembimbing' => $defaults['kuota_pembimbing'],
                'kuota_penguji' => $defaults['kuota_penguji'],
                'terpakai_pembimbing' => 0,
                'terpakai_penguji' => 0,
            ]
        );
    }
}
