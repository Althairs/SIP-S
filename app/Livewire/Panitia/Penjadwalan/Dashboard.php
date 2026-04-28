<?php

namespace App\Livewire\Panitia\Penjadwalan;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\UjianPenguji;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $totalDisetujui;
    public $totalDijadwalkan;
    public $totalSelesai;
    public $totalPenguji;
    public $pendingApproval;
    public $jadwalHariIni;
    public $jadwalMingguIni;

    public function mount()
    {
        $jurusanId = Auth::user()->jurusan_id;

        // Statistik
        $this->totalDisetujui = Pendaftaran::where('jurusan_id', $jurusanId)
            ->whereIn('status', ['disetujui_kajur'])
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

        // Pending approval (disetujui kaprodi, belum di-generate penguji)
        $this->pendingApproval = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kaprodi')
            ->count();

        // Jadwal hari ini
        $this->jadwalHariIni = Pendaftaran::with(['mahasiswa', 'ruangan'])
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
