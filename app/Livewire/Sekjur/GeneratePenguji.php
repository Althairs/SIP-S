<?php

namespace App\Livewire\Sekjur;

use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\UjianPenguji;

class GeneratePenguji extends Component
{
    public Pendaftaran $pendaftaran;
    public $generated = false;

    // Dosen yang cocok
    public Collection $availableDosens;

    // Penguji 1
    public $penguji1Id;
    public array $penguji1 = [];
    public $penguji1Kepakaran;
    public $penguji1Kuota;
    public $penguji1Overload = false;
    public $penguji1Score = 0;
    public array $penguji1ScoreBreakdown = [];

    // Penguji 2
    public $penguji2Id;
    public array $penguji2 = [];
    public $penguji2Kepakaran;
    public $penguji2Kuota;
    public $penguji2Overload = false;
    public $penguji2Score = 0;
    public array $penguji2ScoreBreakdown = [];

    // Manual selection
    public $manualPenguji1;
    public $manualPenguji2;
    public $mode = 'auto'; // auto atau manual

    public function mount(Pendaftaran $pendaftaran)
    {
        $this->pendaftaran = $pendaftaran->load(['bidangKeahlians', 'dosens', 'pengujis']);
        $this->availableDosens = collect();

        // Cek apakah sudah ada penguji
        $existingPenguji1 = $this->pendaftaran->pengujis()->where('peran', 'penguji_1')->first();
        $existingPenguji2 = $this->pendaftaran->pengujis()->where('peran', 'penguji_2')->first();

        if ($existingPenguji1) {
            $this->penguji1Id = $existingPenguji1->dosen_id;
            $this->manualPenguji1 = $existingPenguji1->dosen_id;
            $this->mode = 'manual';
        }
        if ($existingPenguji2) {
            $this->penguji2Id = $existingPenguji2->dosen_id;
            $this->manualPenguji2 = $existingPenguji2->dosen_id;
            $this->mode = 'manual';
        }

        $this->loadAvailableDosens();
        $this->hydrateSelectedPenguji();
    }

    public function loadAvailableDosens()
    {
        $jurusanId = $this->pendaftaran->jurusan_id;
        $pembimbingIds = $this->pendaftaran->dosens->pluck('dosen_id')->toArray();
        $excludeIds = $pembimbingIds;

        // Dapatkan bidang keahlian pendaftaran
        $bidangIds = $this->pendaftaran->bidangKeahlians->pluck('id')->toArray();

        $this->availableDosens = User::role('dosen')
            ->where('jurusan_id', $jurusanId)
            ->active()
            ->when(count($excludeIds), fn($query) => $query->whereNotIn('id', $excludeIds))
            ->with(['kepakaran', 'kuota', 'bidangKeahlians'])
            ->get()
            ->filter(function ($dosen) {
                // Filter dosen dengan bidang keahlian lebih dari 3
                return $dosen->bidangKeahlians->count() <= 3;
            })
            ->map(function ($dosen) use ($bidangIds) {
                $dosen->score = $this->calculateScore($dosen, $bidangIds);
                return $dosen;
            })
            ->sortByDesc('score')
            ->values();
    }

    private function calculateScore($dosen, $bidangIds)
    {
        $score = 0;
        $breakdown = [];

        // 1. Kepakaran (hierarki tertinggi = score tertinggi)
        if ($dosen->kepakaran) {
            $kepakaranScore = (11 - $dosen->kepakaran->hierarki_level) * 5;
            $score += $kepakaranScore;
            $breakdown['kepakaran'] = $kepakaranScore;
        } else {
            $breakdown['kepakaran'] = 0;
        }

        // 1.5 Bidang keahlian match bonus (lebih tinggi jika ada intersection)
        $matching = 0;
        if ($dosen->bidangKeahlians && count($bidangIds)) {
            $matching = $dosen->bidangKeahlians->pluck('id')->intersect($bidangIds)->count();
            if ($matching) {
                // $bidangScore = 50 + ($matching * 5);
                $totalBidang = max(count($bidangIds), 1);
                $bidangScore = ($matching / $totalBidang) * 30;
                $score += $bidangScore;
                $breakdown['bidang_keahlian'] = $bidangScore;
                $breakdown['bidang_match_count'] = $matching;
            } else {
                $breakdown['bidang_keahlian'] = 0;
                $breakdown['bidang_match_count'] = 0;
            }
        } else {
            $breakdown['bidang_keahlian'] = 0;
            $breakdown['bidang_match_count'] = 0;
        }

        // 2. Kuota (lebih banyak sisa = score lebih tinggi)
        $sisaKuota = $dosen->kuota?->sisa_penguji ?? 0;
        $kuotaScore = min($sisaKuota, 10) * 2;
        $score += $kuotaScore;
        $breakdown['kuota'] = $kuotaScore;
        $breakdown['sisa_kuota'] = $sisaKuota;

        // 3. Overload penalty
        if ($dosen->kuota && $dosen->kuota->is_overload_penguji) {
            $score -= 5;
            $breakdown['overload_penalty'] = -5;
        } else {
            $breakdown['overload_penalty'] = 0;
        }

        // Cap score at 100
        $score = min($score, 100);
        $breakdown['total'] = $score;

        // Store breakdown in dosen object
        $dosen->scoreBreakdown = $breakdown;

        return $score;
    }

    public function generatePenguji()
    {
        $this->mode = 'auto';
        $this->penguji1Id = null;
        $this->penguji2Id = null;
        $this->penguji1 = [];
        $this->penguji2 = [];
        $this->penguji1Kepakaran = null;
        $this->penguji2Kepakaran = null;
        $this->penguji1Kuota = null;
        $this->penguji2Kuota = null;
        $this->penguji1Overload = false;
        $this->penguji2Overload = false;
        $this->penguji1Score = 0;
        $this->penguji2Score = 0;
        $this->penguji1ScoreBreakdown = [];
        $this->penguji2ScoreBreakdown = [];

        $this->loadAvailableDosens();

        $bidangIds = $this->pendaftaran->bidangKeahlians->pluck('id')->toArray();

        // Sort by score (tertinggi dulu)
        $sorted = $this->availableDosens->sortByDesc('score')->values();
        // Penguji 1: pilih dosen tertinggi yang memiliki bidang keahlian yang matching,
        // jika tidak ada, ambil score tertinggi secara umum.
        $p1 = $sorted->first(function ($d) use ($bidangIds) {
            return $d->bidangKeahlians->pluck('id')->intersect($bidangIds)->count() > 0;
        }) ?? $sorted->first();
        if ($p1) {
            $this->penguji1Id = $p1->id;
            $this->penguji1Kepakaran = $p1->kepakaran?->nama_kepakaran ?? '-';
            $this->penguji1Kuota = $p1->kuota?->sisa_penguji ?? 0;
            $this->penguji1Overload = $p1->kuota?->is_overload_penguji ?? false;
            $this->penguji1Score = $p1->score ?? 0;
            $this->penguji1ScoreBreakdown = $p1->scoreBreakdown ?? [];
            $this->penguji1 = $p1->toArray();
        }

        // Penguji 2: usahakan memilih dari bidang yang sama dengan p1 dan yang punya kuota tersisa
        $p2 = null;
        if ($p1) {
            $p2 = $sorted->first(function ($d) use ($p1, $bidangIds) {
                return $d->id !== $p1->id
                    && $d->bidangKeahlians->pluck('id')->intersect($bidangIds)->count() > 0
                    && ($d->kuota?->sisa_penguji ?? 0) > 0;
            });
        }

        // Jika tidak ditemukan yang sama bidang dengan kuota, ambil dosen lain dengan kuota tersisa
        if (!$p2) {
            $p2 = $sorted->first(function ($d) use ($p1) {
                return $d->id !== $p1?->id && ($d->kuota?->sisa_penguji ?? 0) > 0;
            });
        }

        // Fallback: pick highest-scoring different dosen
        if (!$p2) {
            $p2 = $sorted->first(function ($d) use ($p1) {
                return $d->id !== $p1?->id;
            });
        }

        if ($p2) {
            $this->penguji2Id = $p2->id;
            $this->penguji2Kepakaran = $p2->kepakaran?->nama_kepakaran ?? '-';
            $this->penguji2Kuota = $p2->kuota?->sisa_penguji ?? 0;
            $this->penguji2Overload = $p2->kuota?->is_overload_penguji ?? false;
            $this->penguji2Score = $p2->score ?? 0;
            $this->penguji2ScoreBreakdown = $p2->scoreBreakdown ?? [];
            $this->penguji2 = $p2->toArray();
        }

        // Update manual selectors
        $this->manualPenguji1 = $this->penguji1Id;
        $this->manualPenguji2 = $this->penguji2Id;

        $this->generated = true;
    }

    public function setManual()
    {
        $this->mode = 'manual';

        $this->loadAvailableDosens();

        if ($this->manualPenguji1) {
            $dosen = $this->availableDosens->firstWhere('id', $this->manualPenguji1);

            if ($dosen) {
                $this->penguji1Id = $dosen->id;
                $this->penguji1Kepakaran = $dosen->kepakaran?->nama_kepakaran ?? '-';
                $this->penguji1Kuota = $dosen->kuota?->sisa_penguji ?? 0;
                $this->penguji1Overload = $dosen->kuota?->is_overload_penguji ?? false;
                $this->penguji1Score = $dosen->score ?? 0;
                $this->penguji1ScoreBreakdown = $dosen->scoreBreakdown ?? [];
                $this->penguji1 = $dosen->toArray();
            }
        } else {
            $this->penguji1Id = null;
            $this->penguji1 = [];
            $this->penguji1Kepakaran = null;
            $this->penguji1Kuota = null;
            $this->penguji1Overload = false;
            $this->penguji1Score = 0;
            $this->penguji1ScoreBreakdown = [];
        }

        if ($this->manualPenguji2) {
            $dosen = $this->availableDosens->firstWhere('id', $this->manualPenguji2);

            if ($dosen) {
                $this->penguji2Id = $dosen->id;
                $this->penguji2Kepakaran = $dosen->kepakaran?->nama_kepakaran ?? '-';
                $this->penguji2Kuota = $dosen->kuota?->sisa_penguji ?? 0;
                $this->penguji2Overload = $dosen->kuota?->is_overload_penguji ?? false;
                $this->penguji2Score = $dosen->score ?? 0;
                $this->penguji2ScoreBreakdown = $dosen->scoreBreakdown ?? [];
                $this->penguji2 = $dosen->toArray();
            }
        } else {
            $this->penguji2Id = null;
            $this->penguji2 = [];
            $this->penguji2Kepakaran = null;
            $this->penguji2Kuota = null;
            $this->penguji2Overload = false;
            $this->penguji2Score = 0;
            $this->penguji2ScoreBreakdown = [];
        }
    }

    public function simpanPenguji()
    {
        $this->validate([
            'penguji1Id' => 'required|exists:users,id',
            'penguji2Id' => 'nullable|exists:users,id|different:penguji1Id',
        ]);

        // Hapus penguji lama
        $this->pendaftaran->pengujis()->delete();

        // Simpan Penguji 1
        $dosen1 = User::find($this->penguji1Id);
        UjianPenguji::create([
            'pendaftaran_id' => $this->pendaftaran->id,
            'dosen_id' => $this->penguji1Id,
            'peran' => 'penguji_1',
            'kepakaran_id' => $dosen1->kepakaran_id,
            'kuota_tersisa' => $dosen1->kuota?->sisa_penguji ?? 0,
            'is_overload' => $dosen1->kuota?->is_overload_penguji ?? false,
        ]);

        // Update kuota terpakai
        if ($dosen1->kuota) {
            $dosen1->kuota->increment('terpakai_penguji');
        }

        // Simpan Penguji 2
        if ($this->penguji2Id) {
            $dosen2 = User::find($this->penguji2Id);
            UjianPenguji::create([
                'pendaftaran_id' => $this->pendaftaran->id,
                'dosen_id' => $this->penguji2Id,
                'peran' => 'penguji_2',
                'kepakaran_id' => $dosen2->kepakaran_id,
                'kuota_tersisa' => $dosen2->kuota?->sisa_penguji ?? 0,
                'is_overload' => $dosen2->kuota?->is_overload_penguji ?? false,
            ]);

            if ($dosen2->kuota) {
                $dosen2->kuota->increment('terpakai_penguji');
            }
        }

        // Update status pendaftaran ke disetujui
        $this->pendaftaran->update([
            'status' => 'disetujui_sekjur',
            'approved_at' => now(),
        ]);

        session()->flash('success', 'Penguji berhasil ditentukan dan diteruskan ke Kajur.');
        return redirect()->route('sekjur.data-master.penguji');
    }

    private function hydrateSelectedPenguji(): void
    {
        $this->penguji1 = $this->penguji1Id
            ? User::with(['kepakaran', 'kuota'])->find($this->penguji1Id)?->toArray() ?? []
            : [];

        $this->penguji2 = $this->penguji2Id
            ? User::with(['kepakaran', 'kuota'])->find($this->penguji2Id)?->toArray() ?? []
            : [];
    }

    public function render()
    {
        $this->loadAvailableDosens();

        return view('livewire.sekjur.generate-penguji')->layout('components.layouts.app-auth');
    }
}
