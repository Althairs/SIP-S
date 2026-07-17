<?php

namespace App\Livewire\Panitia\Verifikasi;

use App\Services\PermissionService;
use App\Models\Pendaftaran;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalPending;

    public $totalDiverifikasi;

    public $totalDitolak;

    public $totalDisetujui;

    public $pendaftarans;

    public $recentVerifications;

    public $prioritasBerkas;

    public $progressVerifikasi = 0;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $jurusanId = PermissionService::getJurusanId();

        $this->totalPending = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'pending')->count();

        $this->totalDisetujui = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_panitia')->count();

        $this->totalDitolak = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'ditolak_panitia')->count();

        // Semua yang sudah diverifikasi (disetujui + ditolak)
        $this->totalDiverifikasi = $this->totalDisetujui + $this->totalDitolak;
        $totalMasuk = $this->totalPending + $this->totalDiverifikasi;
        $this->progressVerifikasi = $totalMasuk > 0 ? round(($this->totalDiverifikasi / $totalMasuk) * 100) : 0;

        // Berkas pending paling lama menjadi prioritas kerja.
        $this->prioritasBerkas = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'dosens.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->where('status', 'pending')
            ->oldest()
            ->take(5)
            ->get();

        // Pendaftaran terbaru (semua status)
        $this->recentVerifications = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'dosens.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->whereIn('status', ['pending', 'disetujui_panitia', 'ditolak_panitia'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function showDetail($id)
    {
        return redirect()->route('panitia.verifikasi.berkas', ['search' => '']);
    }

    public function render()
    {
        return view('livewire.panitia.verifikasi.dashboard')->layout('components.layouts.app-auth');
    }
}
