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
    public $greeting;
    public $recentPendaftarans = [];

    public function mount()
    {
        $user = Auth::user();
        $this->jurusanNama = $user->jurusan?->nama_jurusan ?? 'Belum ditentukan';

        $hour = (int) now()->format('H');
        $this->greeting = match(true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

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

        $this->recentPendaftarans = Pendaftaran::where('jurusan_id', $jurusanId)
            ->with('mahasiswa')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'nama' => $p->mahasiswa->name ?? '-',
                'nim' => $p->mahasiswa->nim ?? '-',
                'judul' => \Str::limit($p->judul_penelitian, 40),
                'status' => $p->status,
                'tanggal' => $p->created_at->format('d M Y'),
            ]);
    }

    public function render()
    {
        return view('livewire.kajur.dashboard')->layout('components.layouts.app-auth');
    }
}
