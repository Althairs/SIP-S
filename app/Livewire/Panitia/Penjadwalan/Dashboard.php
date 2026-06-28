<?php

namespace App\Livewire\Panitia\Penjadwalan;

use App\Models\Pendaftaran;
use App\Models\UjianPenguji;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalDisetujui;

    public $totalDijadwalkan;

    public $totalSelesai;

    public $totalPenguji;

    public $pendingApproval;

    public $jadwalHariIni;

    public $jadwalMingguIni;

    public $siapDijadwalkan;

    public $menungguMasaTunggu;

    public function mount()
    {
        $jurusanId = auth()->user()->jurusan_id;

        // Auto-check ujian yang sudah lewat
        Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'dijadwalkan')
            ->where('tanggal_ujian', '<', now())
            ->update([
                'status' => 'selesai',
                'completed_at' => now(),
            ]);

        $this->totalDisetujui = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kajur')
            ->count();

        $this->totalDijadwalkan = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'dijadwalkan')
            ->count();

        $this->totalSelesai = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'selesai')
            ->count();

        $this->totalPenguji = UjianPenguji::whereHas('pendaftaran', function ($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        })->count();

        // Yang bisa dijadwalkan (minimal 7 hari sudah terlewati dari first_registered_at)
        $this->pendingApproval = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kajur')
            ->where('first_registered_at', '<=', now()->subDays(7))
            ->count();

        $this->siapDijadwalkan = Pendaftaran::with('mahasiswa')
            ->where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kajur')
            ->where('first_registered_at', '<=', now()->subDays(7))
            ->latest('approved_at')
            ->take(5)
            ->get();

        $this->menungguMasaTunggu = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kajur')
            ->where('first_registered_at', '>', now()->subDays(7))
            ->count();

        // Jadwal hari ini
        $this->jadwalHariIni = Pendaftaran::with(['mahasiswa'])
            ->where('jurusan_id', $jurusanId)
            ->where('status', 'dijadwalkan')
            ->whereDate('tanggal_ujian', Carbon::today())
            ->orderBy('tanggal_ujian')
            ->get();

        // Jadwal minggu ini
        $this->jadwalMingguIni = Pendaftaran::with(['mahasiswa'])
            ->where('jurusan_id', $jurusanId)
            ->where('status', 'dijadwalkan')
            ->whereBetween('tanggal_ujian', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('tanggal_ujian')
            ->get();
    }

    public function render()
    {
        return view('livewire.panitia.penjadwalan.dashboard')->layout('components.layouts.app-auth');
    }
}
