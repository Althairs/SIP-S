<?php

namespace App\Livewire\Sekjur;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\UjianPenguji;
use App\Models\Kepakaran;
use App\Models\KuotaDosen;
use App\Models\BidangKeahlians;

class GeneratePenguji extends Component
{
    public Pendaftaran $pendaftaran;
    public $generated = false;

    // Dosen yang cocok
    public $availableDosens = [];

    // Penguji 1
    public $penguji1Id;
    public $penguji1Kepakaran;
    public $penguji1Kuota;
    public $penguji1Overload = false;

    // Penguji 2
    public $penguji2Id;
    public $penguji2Kepakaran;
    public $penguji2Kuota;
    public $penguji2Overload = false;

    // Manual selection
    public $manualPenguji1;
    public $manualPenguji2;
    public $mode = 'auto'; // auto atau manual

    public function mount(Pendaftaran $pendaftaran)
    {
        $this->pendaftaran = $pendaftaran->load(['bidangKeahlians', 'dosens', 'pengujis']);

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
    }

    public function loadAvailableDosens()
    {
        $jurusanId = $this->pendaftaran->jurusan_id;
        $pembimbingIds = $this->pendaftaran->dosens->pluck('dosen_id')->toArray();
        $existingPengujiIds = $this->pendaftaran->pengujis->pluck('dosen_id')->toArray();
        $excludeIds = array_merge($pembimbingIds, $existingPengujiIds);

        // Dapatkan bidang keahlian pendaftaran
        $bidangIds = $this->pendaftaran->bidangKeahlians->pluck('id')->toArray();

        $this->availableDosens = User::role('dosen')
            ->where('jurusan_id', $jurusanId)
            ->where('is_active', true)
            ->whereNotIn('id', $excludeIds)
            ->with(['kepakaran', 'kuota'])
            ->get()
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

        // 1. Kepakaran (hierarki tertinggi = score tertinggi)
        if ($dosen->kepakaran) {
            $score += (10 - $dosen->kepakaran->hierarki_level) * 9;
        }

        // 2. Kuota (lebih banyak sisa = score lebih tinggi)
        $sisaKuota = $dosen->kuota?->sisa_penguji ?? 0;
        $score += min($sisaKuota, 10) * 2;

        // 3. Overload penalty
        if ($dosen->kuota && $dosen->kuota->is_overload_penguji) {
            $score -= 5;
        }

        return $score;
    }

    public function generatePenguji()
    {
        $this->mode = 'auto';
        $bidangIds = $this->pendaftaran->bidangKeahlians->pluck('id')->toArray();

        // Sort by score (tertinggi dulu)
        $sorted = $this->availableDosens->sortByDesc('score')->values();

        // Penguji 1: Score tertinggi (kepakaran tinggi & bidang sesuai)
        $p1 = $sorted->first();
        if ($p1) {
            $this->penguji1Id = $p1->id;
            $this->penguji1Kepakaran = $p1->kepakaran?->nama_kepakaran ?? '-';
            $this->penguji1Kuota = $p1->kuota?->sisa_penguji ?? 0;
            $this->penguji1Overload = $p1->kuota?->is_overload_penguji ?? false;
        }

        // Penguji 2: Score tertinggi selanjutnya (bukan penguji 1)
        $p2 = $sorted->where('id', '!=', $p1?->id)->first();
        if ($p2) {
            $this->penguji2Id = $p2->id;
            $this->penguji2Kepakaran = $p2->kepakaran?->nama_kepakaran ?? '-';
            $this->penguji2Kuota = $p2->kuota?->sisa_penguji ?? 0;
            $this->penguji2Overload = $p2->kuota?->is_overload_penguji ?? false;
        }

        // Update manual selectors
        $this->manualPenguji1 = $this->penguji1Id;
        $this->manualPenguji2 = $this->penguji2Id;

        $this->generated = true;
    }

    public function setManual()
    {
        $this->mode = 'manual';

        if ($this->manualPenguji1) {
            $dosen = User::with(['kepakaran', 'kuota'])->find($this->manualPenguji1);
            $this->penguji1Id = $dosen->id;
            $this->penguji1Kepakaran = $dosen->kepakaran?->nama_kepakaran ?? '-';
            $this->penguji1Kuota = $dosen->kuota?->sisa_penguji ?? 0;
            $this->penguji1Overload = $dosen->kuota?->is_overload_penguji ?? false;
        }

        if ($this->manualPenguji2) {
            $dosen = User::with(['kepakaran', 'kuota'])->find($this->manualPenguji2);
            $this->penguji2Id = $dosen->id;
            $this->penguji2Kepakaran = $dosen->kepakaran?->nama_kepakaran ?? '-';
            $this->penguji2Kuota = $dosen->kuota?->sisa_penguji ?? 0;
            $this->penguji2Overload = $dosen->kuota?->is_overload_penguji ?? false;
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

    public function render()
    {
        return view('livewire.sekjur.generate-penguji')->layout('components.layouts.app-auth');
    }
}
