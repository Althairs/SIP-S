<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use App\Models\PengaturanReminder as PengaturanReminderModel;

class PengaturanReminder extends Component
{
    public $jenisUjian = '';
    public $isActive = true;
    public $deadlineDays = 30;
    public $pesanTemplate = '';
    public $reminders = [];
    public $showSuccess = false;

    // Untuk menambah reminder baru
    public $newReminderType = 'daily';
    public $newReminderInterval = 5;
    public $newReminderDays = 7;

    public $jenisUjianOptions = [
        'seminar_proposal' => 'Seminar Proposal',
        'seminar_hasil' => 'Seminar Hasil',
        'sidang_skripsi' => 'Sidang Skripsi',
        'revisi' => 'Revisi',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function updatedJenisUjian()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        if (empty($this->jenisUjian)) {
            $this->jenisUjian = 'revisi';
        }

        $jurusanId = auth()->user()->jurusan_id;
        $setting = PengaturanReminderModel::where('jurusan_id', $jurusanId)
            ->where('jenis_ujian', $this->jenisUjian)
            ->first();

        if ($setting) {
            $this->isActive = $setting->is_active;
            $this->deadlineDays = $setting->deadline_days;
            $this->pesanTemplate = $setting->pesan_template ?? '';
            $this->reminders = $setting->reminder_settings ?? [];
        } else {
            $this->isActive = true;
            $this->deadlineDays = 30;
            $this->pesanTemplate = 'Mohon segera menyelesaikan {jenis_ujian} untuk judul "{judul}". Deadline: {deadline}';
            $this->reminders = [
                ['type' => 'daily', 'interval' => 5, 'label' => 'Setiap 5 Hari'],
                ['type' => 'before_deadline', 'days' => 7, 'label' => 'H-7 Deadline'],
                ['type' => 'before_deadline', 'days' => 3, 'label' => 'H-3 Deadline'],
                ['type' => 'before_deadline', 'days' => 1, 'label' => 'H-1 Deadline'],
            ];
        }
    }

    public function addReminder()
    {
        $label = '';
        if ($this->newReminderType === 'daily') {
            $label = "Setiap {$this->newReminderInterval} Hari";
        } else {
            $label = "H-{$this->newReminderDays} Deadline";
        }

        $this->reminders[] = [
            'type' => $this->newReminderType,
            'interval' => $this->newReminderType === 'daily' ? $this->newReminderInterval : null,
            'days' => $this->newReminderType === 'before_deadline' ? $this->newReminderDays : null,
            'label' => $label,
        ];

        $this->reset(['newReminderType', 'newReminderInterval', 'newReminderDays']);
        $this->newReminderType = 'daily';
        $this->newReminderInterval = 5;
        $this->newReminderDays = 7;
    }

    public function removeReminder($index)
    {
        unset($this->reminders[$index]);
        $this->reminders = array_values($this->reminders);
    }

    public function save()
    {
        $this->validate([
            'jenisUjian' => 'required|in:seminar_proposal,seminar_hasil,sidang_skripsi,revisi',
            'deadlineDays' => 'required|integer|min:7|max:365',
            'reminders' => 'required|array|min:1',
        ]);

        $jurusanId = auth()->user()->jurusan_id;

        PengaturanReminderModel::updateOrCreate(
            [
                'jurusan_id' => $jurusanId,
                'jenis_ujian' => $this->jenisUjian,
            ],
            [
                'is_active' => $this->isActive,
                'deadline_days' => $this->deadlineDays,
                'pesan_template' => $this->pesanTemplate,
                'reminder_settings' => $this->reminders,
            ]
        );

        $this->showSuccess = true;
        session()->flash('success', 'Pengaturan reminder berhasil disimpan.');
    }

    public function generateReminders()
    {
        // Generate reminder untuk semua mahasiswa yang sudah selesai ujian
        $jurusanId = auth()->user()->jurusan_id;

        $pendaftarans = \App\Models\Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'selesai')
            ->whereNotNull('tanggal_ujian')
            ->get();

        $count = 0;
        foreach ($pendaftarans as $p) {
            $count += $this->createRemindersForPendaftaran($p);
        }

        session()->flash('success', "Berhasil mengenerate {$count} reminder untuk {$pendaftarans->count()} ujian.");
    }

    private function createRemindersForPendaftaran($pendaftaran)
    {
        $settings = PengaturanReminderModel::getSettings($pendaftaran->jurusan_id, 'revisi');
        $deadlineDate = $pendaftaran->tanggal_ujian->copy()->addDays($settings['deadline_days']);
        $count = 0;

        // Hapus reminder lama untuk pendaftaran ini
        \App\Models\Reminder::where('pendaftaran_id', $pendaftaran->id)
            ->where('tipe', '!=', 'info')
            ->delete();

        foreach ($settings['reminders'] as $reminder) {
            $tanggalTampil = null;
            $pesan = $settings['pesan_template'] ?? 'Mohon segera menyelesaikan revisi.';

            // Replace template variables
            $pesan = str_replace(
                ['{jenis_ujian}', '{judul}', '{deadline}'],
                [
                    ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)),
                    $pendaftaran->judul_penelitian,
                    $deadlineDate->format('d M Y'),
                ],
                $pesan
            );

            if ($reminder['type'] === 'daily') {
                $tanggalTampil = now()->addDays($reminder['interval']);
                $judul = "Pengingat Revisi - {$reminder['label']}";
            } elseif ($reminder['type'] === 'before_deadline') {
                $tanggalTampil = $deadlineDate->copy()->subDays($reminder['days']);
                $judul = "Peringatan Deadline - {$reminder['label']}";
            }

            if ($tanggalTampil && $tanggalTampil->gt(now())) {
                \App\Models\Reminder::create([
                    'user_id' => $pendaftaran->mahasiswa_id,
                    'pendaftaran_id' => $pendaftaran->id,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'tipe' => $reminder['type'] === 'daily' ? 'periodik' : 'deadline',
                    'prioritas' => $reminder['type'] === 'before_deadline' ? 'tinggi' : 'sedang',
                    'tanggal_tampil' => $tanggalTampil,
                    'tanggal_kadaluarsa' => $deadlineDate,
                ]);
                $count++;
            }
        }

        return $count;
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $allSettings = PengaturanReminderModel::where('jurusan_id', $jurusanId)
            ->get()
            ->keyBy('jenis_ujian');

        return view('livewire.kajur.pengaturan-reminder', [
            'allSettings' => $allSettings,
        ])->layout('components.layouts.app-auth');
    }
}
