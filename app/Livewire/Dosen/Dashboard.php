<?php

namespace App\Livewire\Dosen;

use App\Models\KuotaDosen;
use App\Models\Penilaian;
use App\Models\Revisi;
use App\Models\UjianPenguji;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalMenguji;

    public $totalRevisi;

    public $totalSelesai;

    public $kuotaPembimbing;

    public $kuotaPenguji;

    public $jadwalHariIni;

    public $jadwalMendatang;

    public $pendingRevisis;

    public $totalNilaiPerluInput;

    public function mount()
    {
        $dosenId = auth()->id();

        // Statistik menguji
        $this->totalMenguji = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'dijadwalkan');
            })
            ->count();

        $this->totalSelesai = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'selesai');
            })
            ->count();

        // Revisi pending
        $this->totalRevisi = Revisi::byDosen($dosenId)->pending()->count();

        // Kuota
        $kuota = KuotaDosen::where('dosen_id', $dosenId)->first();
        $this->kuotaPembimbing = $kuota ? $kuota->sisa_pembimbing : 5;
        $this->kuotaPenguji = $kuota ? $kuota->sisa_penguji : 10;

        // Jadwal hari ini
        $this->jadwalHariIni = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'dijadwalkan')
                    ->whereDate('tanggal_ujian', now()->format('Y-m-d'));
            })
            ->with('pendaftaran.mahasiswa')
            ->get();

        $this->jadwalMendatang = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'dijadwalkan')
                    ->whereDate('tanggal_ujian', '>=', now()->format('Y-m-d'));
            })
            ->with('pendaftaran.mahasiswa')
            ->get()
            ->sortBy(fn ($jadwal) => $jadwal->pendaftaran->tanggal_ujian)
            ->take(5);

        $pendaftaranSudahDinilai = Penilaian::where('dosen_id', $dosenId)
            ->whereIn('status', ['selesai', 'diverifikasi'])
            ->pluck('pendaftaran_id');

        $this->totalNilaiPerluInput = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function ($q) {
                $q->whereIn('status', ['dijadwalkan', 'selesai']);
            })
            ->whereNotIn('pendaftaran_id', $pendaftaranSudahDinilai)
            ->count();

        // Revisi pending list
        $this->pendingRevisis = Revisi::byDosen($dosenId)
            ->pending()
            ->with(['pendaftaran.mahasiswa'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dosen.dashboard')->layout('components.layouts.app-auth');
    }
}
