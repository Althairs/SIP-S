<?php

namespace App\Livewire\Panitia\Administrasi;

use App\Services\PermissionService;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\Revisi;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalDokumenSiap;

    public $totalSelesai;

    public $totalRevisi;

    public $totalNilaiMasuk;

    public $recentSelesai;

    public $recentRevisi;

    public function mount()
    {
        $jurusanId = PermissionService::getJurusanId();

        $pendaftaranQuery = Pendaftaran::where('jurusan_id', $jurusanId);

        $this->totalDokumenSiap = (clone $pendaftaranQuery)->whereIn('status', ['dijadwalkan', 'selesai'])->count();
        $this->totalSelesai = (clone $pendaftaranQuery)->where('status', 'selesai')->count();
        $this->totalRevisi = (clone $pendaftaranQuery)->where('status', 'revisi')->count();

        $this->totalNilaiMasuk = Penilaian::whereHas('pendaftaran', function ($query) use ($jurusanId) {
            $query->where('jurusan_id', $jurusanId);
        })->whereIn('status', ['selesai', 'diverifikasi'])->count();

        $this->recentSelesai = Pendaftaran::with(['mahasiswa', 'pengujis.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->where('status', 'selesai')
            ->latest('completed_at')
            ->take(5)
            ->get();

        $this->recentRevisi = Revisi::with(['pendaftaran.mahasiswa', 'dosen'])
            ->whereHas('pendaftaran', function ($query) use ($jurusanId) {
                $query->where('jurusan_id', $jurusanId);
            })
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.panitia.administrasi.dashboard')->layout('components.layouts.app-auth');
    }
}
