<?php

namespace App\Livewire\Panitia\Verifikasi;

use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $totalPending;
    public $totalDiverifikasi;
    public $totalDitolak;

    public function mount()
    {
        $jurusanId = Auth::user()->jurusan_id;

        $this->totalPending = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'pending')->count();
        $this->totalDiverifikasi = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kaprodi')->count();
        $this->totalDitolak = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'ditolak_kaprodi')->count();
    }

    public function render()
    {
        return view('livewire.panitia.verifikasi.dashboard')->layout('components.layouts.app-auth');
    }
}
