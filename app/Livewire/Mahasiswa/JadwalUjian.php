<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\UjianPenguji;
use Carbon\Carbon;

class JadwalUjian extends Component
{
    public $upcomingUjian;
    public $riwayatUjian;
    public $selectedUjian = null;
    public $showDetail = false;

    public function mount()
    {
        $userId = auth()->id();

        // Ujian yang akan datang
        $this->upcomingUjian = Pendaftaran::with([
            'dosens.dosen.kepakaran',
            'bidangKeahlians',
            'jurusan',
            'prodi'
        ])
            ->where('mahasiswa_id', $userId)
            ->where('status', 'dijadwalkan')
            ->where('tanggal_ujian', '>=', now())
            ->orderBy('tanggal_ujian')
            ->get();

        // Riwayat ujian (selesai atau sudah lewat)
        $this->riwayatUjian = Pendaftaran::with([
            'dosens.dosen.kepakaran',
            'bidangKeahlians'
        ])
            ->where('mahasiswa_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'selesai')
                    ->orWhere(function ($q) {
                        $q->where('status', 'dijadwalkan')
                          ->where('tanggal_ujian', '<', now());
                    });
            })
            ->orderBy('tanggal_ujian', 'desc')
            ->get();
    }

    public function showDetailUjian($id)
    {
        $this->selectedUjian = Pendaftaran::with([
            'mahasiswa',
            'dosens.dosen.kepakaran',
            'bidangKeahlians',
            'jurusan',
            'prodi',
            'pengujis.dosen.kepakaran',
            'pembimbing1.dosen.kepakaran',
            'pembimbing2.dosen.kepakaran'
        ])
            ->where('mahasiswa_id', auth()->id())
            ->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedUjian = null;
    }

    public function render()
    {
        return view('livewire.mahasiswa.jadwal-ujian')->layout('components.layouts.app-auth');
    }
}
