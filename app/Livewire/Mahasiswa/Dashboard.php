<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\Reminder;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $pendaftarans;
    public $reminders;
    public $tahapan;
    public $reminderTerdekat;

    public function mount()
    {
        $userId = auth()->id();

        $this->pendaftarans = Pendaftaran::with(['dosens.dosen', 'jurusan', 'pengujis'])
            ->where('mahasiswa_id', $userId)
            ->latest()
            ->get();

        // TAMBAHAN: Ambil reminder aktif
        $this->reminders = Reminder::with('pendaftaran')
            ->where('user_id', $userId)
            ->active()
            ->orderBy('prioritas')
            ->orderBy('tanggal_tampil')
            ->take(10)
            ->get();

        // TAMBAHAN: Reminder terdekat/terpenting
        $this->reminderTerdekat = Reminder::with('pendaftaran')
            ->where('user_id', $userId)
            ->active()
            ->unread()
            ->orderBy('prioritas')
            ->orderBy('tanggal_tampil')
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

    public function markReminderRead($reminderId)
    {
        $reminder = Reminder::findOrFail($reminderId);
        if ($reminder->user_id === auth()->id()) {
            $reminder->markAsRead();
        }
        $this->mount();
    }

    public function render()
    {
        return view('livewire.mahasiswa.dashboard')->layout('components.layouts.app-auth');
    }
}
