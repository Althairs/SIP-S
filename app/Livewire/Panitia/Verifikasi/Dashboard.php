<?php

namespace App\Livewire\Panitia\Verifikasi;

use Livewire\Component;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalPending;
    public $totalDiverifikasi;
    public $totalDitolak;
    public $totalDisetujui;
    public $pendaftarans;
    public $recentVerifications;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $this->totalPending = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'pending')->count();

        $this->totalDisetujui = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_panitia')->count();

        $this->totalDitolak = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'ditolak_panitia')->count();

        // Semua yang sudah diverifikasi (disetujui + ditolak)
        $this->totalDiverifikasi = $this->totalDisetujui + $this->totalDitolak;

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
