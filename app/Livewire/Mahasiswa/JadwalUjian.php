<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class JadwalUjian extends Component
{
    public $upcomingUjian;
    public $riwayatUjian;
    public $selectedUjian = null;
    public $showDetail = false;

    public function mount()
    {
        $this->loadJadwal();
    }

    protected function loadJadwal(): void
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');

        // Upcoming Ujian Query
        $this->upcomingUjian = Pendaftaran::with([
            'mahasiswa',
            'pembimbing1.dosen',
            'bidangKeahlians',
            'jurusan',
            'prodi',
        ])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->where('status', 'dijadwalkan')
            ->where('tanggal_ujian', '>=', now())
            ->orderBy('tanggal_ujian')
            ->get();

        // Riwayat Ujian Query
        $this->riwayatUjian = Pendaftaran::with([
            'mahasiswa',
            'pembimbing1.dosen',
            'bidangKeahlians',
        ])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->where(function ($query) {
                $query->whereIn('status', ['selesai', 'revisi'])
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
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');

        $this->selectedUjian = Pendaftaran::with([
            'mahasiswa.jurusan',
            'dosens.dosen.kepakaran',
            'bidangKeahlians',
            'jurusan',
            'prodi',
            'pengujis.dosen.kepakaran',
            'pembimbing1.dosen.kepakaran',
            'pembimbing2.dosen.kepakaran'
        ])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
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
        return view('livewire.mahasiswa.jadwal-ujian', [
            'isSuperAdmin' => Auth::user()->hasRole('super_admin'),
        ])->layout('components.layouts.app-auth');
    }
}
