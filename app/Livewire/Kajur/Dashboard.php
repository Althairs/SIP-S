<?php

namespace App\Livewire\Kajur;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    private const PANITIA_ROLES = [
        'panitia_verifikasi',
        'panitia_penjadwalan',
        'panitia_administrasi',
    ];

    public $totalDosen;

    public $totalMahasiswa;

    public $totalPanitia;

    public $totalPanitiaVerifikasi;

    public $totalPanitiaPenjadwalan;

    public $totalPanitiaAdministrasi;

    public $totalMenungguKajur;

    public $totalSiapDijadwalkan;

    public $totalDijadwalkan;

    public $jurusanNama;

    public function mount()
    {
        $user = Auth::user();
        $this->jurusanNama = $user->jurusan?->nama_jurusan ?? 'Belum ditentukan';

        $jurusanId = $user->jurusan_id;
        $this->totalDosen = User::role('dosen')->where('jurusan_id', $jurusanId)->count();
        $this->totalMahasiswa = User::role('mahasiswa')->where('jurusan_id', $jurusanId)->count();
        $this->totalPanitia = User::role(self::PANITIA_ROLES)->where('jurusan_id', $jurusanId)->count();
        $this->totalPanitiaVerifikasi = User::role('panitia_verifikasi')->where('jurusan_id', $jurusanId)->count();
        $this->totalPanitiaPenjadwalan = User::role('panitia_penjadwalan')->where('jurusan_id', $jurusanId)->count();
        $this->totalPanitiaAdministrasi = User::role('panitia_administrasi')->where('jurusan_id', $jurusanId)->count();

        $pendaftaranQuery = Pendaftaran::where('jurusan_id', $jurusanId);

        $this->totalMenungguKajur = (clone $pendaftaranQuery)->where('status', 'disetujui_sekjur')->count();
        $this->totalSiapDijadwalkan = (clone $pendaftaranQuery)->where('status', 'disetujui_kajur')->count();
        $this->totalDijadwalkan = (clone $pendaftaranQuery)->where('status', 'dijadwalkan')->count();
    }

    public function render()
    {
        return view('livewire.kajur.dashboard')->layout('components.layouts.app-auth');
    }
}
