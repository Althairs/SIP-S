<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Pendaftaran;
use App\Models\Reminder;
use Livewire\Component;

class Dashboard extends Component
{
    public $pendaftarans;

    public $reminders;

    public $tahapan;

    public $reminderTerdekat;

    public $pendaftaranAktif;

    public array $nextAction = [];

    public function mount()
    {
        $userId = auth()->id();

        $this->pendaftarans = Pendaftaran::with(['dosens.dosen', 'jurusan', 'pengujis'])
            ->where('mahasiswa_id', $userId)
            ->latest()
            ->get();

        $this->pendaftaranAktif = $this->pendaftarans->first(function ($pendaftaran) {
            return ! in_array($pendaftaran->status, ['selesai', 'ditolak_panitia', 'ditolak_sekjur', 'ditolak_kajur']);
        });

        $this->nextAction = $this->resolveNextAction($this->pendaftaranAktif);

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

    private function resolveNextAction(?Pendaftaran $pendaftaran): array
    {
        if (! $pendaftaran) {
            return [
                'label' => 'Mulai Pendaftaran',
                'title' => 'Belum ada pendaftaran aktif.',
                'description' => 'Mulai dari tahapan ujian yang tersedia agar sistem bisa menuntun proses berikutnya.',
                'url' => route('mahasiswa.pendaftaran.create'),
                'color' => 'blue',
            ];
        }

        return match ($pendaftaran->status) {
            'draft', 'revisi' => [
                'label' => 'Lengkapi Berkas',
                'title' => 'Pendaftaran perlu dilengkapi.',
                'description' => 'Periksa kembali data, pembimbing, dan berkas sebelum dikirim ulang.',
                'url' => route('mahasiswa.pendaftaran.edit', $pendaftaran),
                'color' => 'amber',
            ],
            'pending' => [
                'label' => 'Pantau Verifikasi',
                'title' => 'Berkas sedang menunggu verifikasi panitia.',
                'description' => 'Cek reminder dan riwayat pendaftaran untuk mengetahui perubahan status.',
                'url' => route('mahasiswa.pendaftaran.index'),
                'color' => 'yellow',
            ],
            'disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur' => [
                'label' => 'Lihat Status',
                'title' => 'Pendaftaran sedang bergerak ke tahap berikutnya.',
                'description' => 'Sistem akan meneruskan proses sampai jadwal ujian tersedia.',
                'url' => route('mahasiswa.pendaftaran.index'),
                'color' => 'green',
            ],
            'dijadwalkan' => [
                'label' => 'Lihat Jadwal',
                'title' => 'Jadwal ujian sudah tersedia.',
                'description' => 'Cek waktu, ruangan, dan penguji agar persiapan ujian lebih tenang.',
                'url' => route('mahasiswa.jadwal'),
                'color' => 'purple',
            ],
            default => [
                'label' => 'Buka Pendaftaran',
                'title' => $pendaftaran->statusLabel,
                'description' => 'Pantau detail pendaftaran untuk mengetahui tindakan berikutnya.',
                'url' => route('mahasiswa.pendaftaran.index'),
                'color' => 'gray',
            ],
        };
    }

    public function render()
    {
        return view('livewire.mahasiswa.dashboard')->layout('components.layouts.app-auth');
    }
}
