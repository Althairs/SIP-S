<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $pendaftarans;
    public $reminder;
    public $tahapan;

    public function mount()
    {
        $userId = Auth::id();

        // Ambil semua pendaftaran
        $this->pendaftarans = Pendaftaran::with(['dosens.dosen', 'jurusan'])
            ->where('mahasiswa_id', $userId)
            ->latest()
            ->get();

        // Reminder (jadwal yang akan datang)
        $this->reminder = Pendaftaran::where('mahasiswa_id', $userId)
            ->where('status', 'dijadwalkan')
            ->where('tanggal_ujian', '>=', now())
            ->orderBy('tanggal_ujian')
            ->first();

        // Tahapan yang sudah dilalui
        $this->tahapan = [
            'seminar_proposal' => [
                'label' => 'Seminar Proposal',
                'completed' => Pendaftaran::where('mahasiswa_id', $userId)
                    ->where('jenis_ujian', 'seminar_proposal')
                    ->where('status', 'selesai')
                    ->exists(),
            ],
            'seminar_hasil' => [
                'label' => 'Seminar Hasil',
                'completed' => Pendaftaran::where('mahasiswa_id', $userId)
                    ->where('jenis_ujian', 'seminar_hasil')
                    ->where('status', 'selesai')
                    ->exists(),
            ],
            'sidang_skripsi' => [
                'label' => 'Sidang Skripsi',
                'completed' => Pendaftaran::where('mahasiswa_id', $userId)
                    ->where('jenis_ujian', 'sidang_skripsi')
                    ->where('status', 'selesai')
                    ->exists(),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.mahasiswa.dashboard')->layout('components.layouts.app-auth');
    }
}
