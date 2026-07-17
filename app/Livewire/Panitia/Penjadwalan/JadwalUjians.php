<?php

namespace App\Livewire\Panitia\Penjadwalan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Pendaftaran;
use App\Models\UjianPenguji;
use App\Models\Ruangan;
use App\Models\PengaturanJadwal;
use App\Services\JadwalConflictService;
use App\Jobs\SendWhatsAppNotification;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class JadwalUjians extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $jenisFilter = '';

    #[Url]
    public $tab = 'siap';

    public string $modalMode = '';
    public $selectedPendaftaran;
    public $selectedIds = [];
    public $selectAll = false;

    // Persistent notification
    public $notificationMessage = '';
    public $notificationType = ''; // 'success' or 'error'
    public $showNotification = false;

    public $tanggal_ujian;
    public $tanggal_minimal;
    public $ruangan = '';
    public $sesi = 1;
    public $catatan = '';
    public $scheduleMode = 'manual'; // UBAH default ke manual
    public $tanggalDaftar;
    public $batchMode = false;

    public $ruanganOptions = [];
    public $jamMulaiOptions = [];
    public $jamSelesaiOptions = [];
    public $labelSesiOptions = [];

    public function mount()
    {
        if (request()->has('tab')) {
            $this->tab = request()->get('tab');
        }
        $this->loadRuanganOptions();
        $this->loadSesiOptions();
        $this->checkCompletedUjian();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'jenisFilter', 'tab'])) {
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedIds = $this->getSiapList()->pluck('id')->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    private function loadRuanganOptions()
    {
        $jurusanId = auth()->user()->jurusan_id;
        $this->ruanganOptions = Ruangan::where('jurusan_id', $jurusanId)
            ->active()
            ->pluck('nama_ruangan')
            ->toArray();

        if (empty($this->ruanganOptions)) {
            $this->ruanganOptions = ['Ruang Seminar 1', 'Ruang Seminar 2', 'Ruang Sidang 1', 'Aula Fakultas'];
        }
    }

    private function loadSesiOptions()
    {
        $jurusanId = auth()->user()->jurusan_id;
        $pengaturan = PengaturanJadwal::where('jurusan_id', $jurusanId)->where('is_active', true)->first();

        if ($pengaturan) {
            $this->jamMulaiOptions = $pengaturan->jam_mulai;
            $this->jamSelesaiOptions = $pengaturan->jam_selesai;
            $this->labelSesiOptions = $pengaturan->label_sesi;
        } else {
            $this->jamMulaiOptions = ['08:00', '10:00', '13:00', '15:00'];
            $this->jamSelesaiOptions = ['10:00', '12:00', '15:00', '17:00'];
            $this->labelSesiOptions = ['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4'];
        }
    }

    public function openScheduleModal($id)
    {
        $this->batchMode = false;
        $this->selectedPendaftaran = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen'])->findOrFail($id);

        $tanggalDaftar = $this->selectedPendaftaran->first_registered_at
            ?? $this->selectedPendaftaran->created_at;
        $this->tanggalDaftar = Carbon::parse($tanggalDaftar);
        $this->tanggal_minimal = $this->tanggalDaftar->copy()->addDays(7)->startOfDay();

        if ($this->tanggal_minimal->isPast()) {
            $this->tanggal_minimal = Carbon::tomorrow()->startOfDay();
        }

        // Gunakan data existing jika ada
        if ($this->selectedPendaftaran->tanggal_ujian) {
            $this->tanggal_ujian = Carbon::parse($this->selectedPendaftaran->tanggal_ujian)->format('Y-m-d');
        } else {
            $this->tanggal_ujian = $this->tanggal_minimal->copy()->addDays(rand(0, 7))->format('Y-m-d');
        }

        $this->ruangan = $this->selectedPendaftaran->ruangan ?? '';
        $this->sesi = $this->selectedPendaftaran->sesi ?? 1;
        $this->scheduleMode = 'manual'; // Default manual
        $this->modalMode = 'schedule';
    }

    public function closeScheduleModal()
    {
        $this->modalMode = '';
        $this->resetValidation();
    }

    public function openBatchModal()
    {
        if (empty($this->selectedIds)) {
            $this->showNotification('Pilih minimal satu pendaftaran.', 'error');
            return;
        }

        $notReady = Pendaftaran::whereIn('id', $this->selectedIds)
            ->where('status', '!=', 'disetujui_kajur')
            ->count();

        if ($notReady > 0) {
            $this->showNotification('Beberapa pendaftaran belum siap.', 'error');
            return;
        }

        $oldestDate = Pendaftaran::whereIn('id', $this->selectedIds)->min('first_registered_at');
        $this->tanggalDaftar = Carbon::parse($oldestDate);
        $this->tanggal_minimal = $this->tanggalDaftar->copy()->addDays(7)->startOfDay();

        if ($this->tanggal_minimal->isPast()) {
            $this->tanggal_minimal = Carbon::tomorrow()->startOfDay();
        }

        $this->tanggal_ujian = $this->tanggal_minimal->format('Y-m-d');
        $this->ruangan = '';
        $this->sesi = 1;
        $this->batchMode = true;
        $this->modalMode = 'batch';
    }

    public function closeBatchModal()
    {
        $this->modalMode = '';
    }

    public function openDetailModal($id)
    {
        $this->selectedPendaftaran = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pembimbing1.dosen', 'pembimbing2.dosen'])->findOrFail($id);
        $this->modalMode = 'detail';
    }

    public function closeDetailModal()
    {
        $this->modalMode = '';
        $this->selectedPendaftaran = null;
    }

    private function showNotification($message, $type = 'success')
    {
        $this->notificationMessage = $message;
        $this->notificationType = $type;
        $this->showNotification = true;
    }

    public function closeNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = '';
    }

    // Method auto generate
    public function autoGenerateJadwal()
    {
        $this->tanggal_ujian = $this->tanggal_minimal->copy()->addDays(rand(0, 7))->format('Y-m-d');
        $this->ruangan = $this->ruanganOptions[array_rand($this->ruanganOptions)] ?? 'Ruang Seminar 1';
        $this->sesi = rand(1, count($this->labelSesiOptions));
        $this->scheduleMode = 'auto';
    }

    // Method untuk switch ke manual (reset mode)
    public function setManualMode()
    {
        $this->scheduleMode = 'manual';
    }

    public function scheduleUjian()
    {
        $this->validate([
            'tanggal_ujian' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:' . $this->tanggal_minimal->format('Y-m-d'),
            ],
            'ruangan' => 'required|string',
            'sesi' => 'required|integer|min:1|max:' . count($this->labelSesiOptions),
        ], [
            'tanggal_ujian.after_or_equal' => 'Tanggal ujian harus minimal ' . $this->tanggal_minimal->format('d M Y') . ' (H+7 dari pendaftaran).',
        ]);

        $sesiIndex = $this->sesi - 1;
        $jamMulai = $this->jamMulaiOptions[$sesiIndex] ?? '08:00';
        $tanggalUjian = Carbon::parse($this->tanggal_ujian . ' ' . $jamMulai);

        $conflictService = new JadwalConflictService();
        $conflictService->validateSchedule($this->selectedPendaftaran, $this->tanggal_ujian, $this->sesi, $this->ruangan);

        $this->selectedPendaftaran->update([
            'tanggal_ujian' => $tanggalUjian,
            'ruangan' => $this->ruangan,
            'sesi' => $this->sesi,
            'status' => 'dijadwalkan',
            'scheduled_at' => now(),
        ]);

        // Send WA to Mahasiswa
        if ($this->selectedPendaftaran->mahasiswa && $this->selectedPendaftaran->mahasiswa->nomor_hp) {
            $msg = "Halo {$this->selectedPendaftaran->mahasiswa->name},\n\nJadwal ujian Anda telah ditetapkan:\nTanggal: {$tanggalUjian->format('d M Y H:i')}\nRuangan: {$this->ruangan}\n\nSilakan cek SIP-S untuk detailnya.";
            SendWhatsAppNotification::dispatch($this->selectedPendaftaran->mahasiswa->nomor_hp, $msg);
        }

        // Send WA to Dosens
        foreach ($this->selectedPendaftaran->pengujis as $dosenUjian) {
            if ($dosenUjian->dosen && $dosenUjian->dosen->nomor_hp) {
                $msgDosen = "Halo Bapak/Ibu {$dosenUjian->dosen->name},\n\nAnda dijadwalkan untuk menguji pada:\nTanggal: {$tanggalUjian->format('d M Y H:i')}\nMahasiswa: {$this->selectedPendaftaran->mahasiswa->name}\nRuangan: {$this->ruangan}\n\nSilakan cek SIP-S untuk detailnya.";
                SendWhatsAppNotification::dispatch($dosenUjian->dosen->nomor_hp, $msgDosen);
            }
        }

        $sesiLabel = $this->labelSesiOptions[$sesiIndex] ?? 'Sesi ' . $this->sesi;
        session()->flash('success', 'Ujian dijadwalkan pada ' . $tanggalUjian->format('d M Y') . ', ' . $sesiLabel . ' (' . $jamMulai . ' - ' . ($this->jamSelesaiOptions[$sesiIndex] ?? 'selesai') . ').');
        $this->closeScheduleModal();
    }

    public function scheduleBatchUjian()
    {
        $this->validate([
            'tanggal_ujian' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:' . $this->tanggal_minimal->format('Y-m-d'),
            ],
            'ruangan' => 'required|string',
            'sesi' => 'required|integer|min:1|max:' . count($this->labelSesiOptions),
        ], [
            'tanggal_ujian.after_or_equal' => 'Tanggal ujian harus minimal ' . $this->tanggal_minimal->format('d M Y') . '.',
        ]);

        $sesiIndex = $this->sesi - 1;
        $jamMulai = $this->jamMulaiOptions[$sesiIndex] ?? '08:00';
        $tanggalUjian = Carbon::parse($this->tanggal_ujian . ' ' . $jamMulai);

        $conflictService = new JadwalConflictService();
        $pendaftarans = Pendaftaran::whereIn('id', $this->selectedIds)
            ->where('status', 'disetujui_kajur')
            ->get();

        $successCount = 0;
        $errorMessages = [];

        foreach ($pendaftarans as $pendaftaran) {
            try {
                $conflictService->validateSchedule($pendaftaran, $this->tanggal_ujian, $this->sesi, $this->ruangan);

                $pendaftaran->update([
                    'tanggal_ujian' => $tanggalUjian,
                    'ruangan' => $this->ruangan,
                    'sesi' => $this->sesi,
                    'status' => 'dijadwalkan',
                    'scheduled_at' => now(),
                ]);

                // Send WA to Mahasiswa
                if ($pendaftaran->mahasiswa && $pendaftaran->mahasiswa->nomor_hp) {
                    $msg = "Halo {$pendaftaran->mahasiswa->name},\n\nJadwal ujian Anda telah ditetapkan:\nTanggal: {$tanggalUjian->format('d M Y H:i')}\nRuangan: {$this->ruangan}\n\nSilakan cek SIP-S untuk detailnya.";
                    SendWhatsAppNotification::dispatch($pendaftaran->mahasiswa->nomor_hp, $msg);
                }

                // Send WA to Dosens
                foreach ($pendaftaran->pengujis as $dosenUjian) {
                    if ($dosenUjian->dosen && $dosenUjian->dosen->nomor_hp) {
                        $msgDosen = "Halo Bapak/Ibu {$dosenUjian->dosen->name},\n\nAnda dijadwalkan untuk menguji pada:\nTanggal: {$tanggalUjian->format('d M Y H:i')}\nMahasiswa: {$pendaftaran->mahasiswa->name}\nRuangan: {$this->ruangan}\n\nSilakan cek SIP-S untuk detailnya.";
                        SendWhatsAppNotification::dispatch($dosenUjian->dosen->nomor_hp, $msgDosen);
                    }
                }

                $successCount++;
            } catch (ValidationException $e) {
                $msg = $e->validator->errors()->first('tanggal_ujian') ?: 'Gagal dijadwalkan (konflik).';
                $errorMessages[] = "{$pendaftaran->mahasiswa->name}: {$msg}";
            }
        }

        $this->selectedIds = [];
        $this->selectAll = false;

        if ($successCount > 0) {
            $this->showNotification("$successCount ujian berhasil dijadwalkan pada " . $tanggalUjian->format('d M Y') . '.', 'success');
        }

        if (!empty($errorMessages)) {
            $errorText = "Gagal menjadwalkan " . count($errorMessages) . " ujian: " . implode(" | ", $errorMessages);
            $this->showNotification($errorText, 'error');
        }

        $this->closeBatchModal();
    }

    public function rescheduleUjian($id)
    {
        $this->openScheduleModal($id);
    }

    public function cancelJadwal($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'disetujui_kajur',
            'tanggal_ujian' => null,
            'ruangan' => null,
            'sesi' => null,
            'scheduled_at' => null,
        ]);
        session()->flash('success', 'Jadwal dibatalkan.');
    }

    public function markAsCompleted($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'selesai',
            'completed_at' => now(),
        ]);
        session()->flash('success', 'Ujian ditandai selesai.');
    }

    public function checkCompletedUjian()
    {
        $jurusanId = auth()->user()->jurusan_id;
        Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'dijadwalkan')
            ->where('tanggal_ujian', '<', now())
            ->update([
                'status' => 'selesai',
                'completed_at' => now(),
            ]);
    }

    private function getSiapList()
    {
        $jurusanId = auth()->user()->jurusan_id;
        return Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pembimbing1.dosen', 'pembimbing2.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kajur')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul_penelitian', 'like', '%' . $this->search . '%')
                      ->orWhereHas('mahasiswa', function ($mq) {
                          $mq->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('nim', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->jenisFilter, function ($query) {
                $query->where('jenis_ujian', $this->jenisFilter);
            })
            ->orderBy('first_registered_at')
            ->get();
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $query = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pembimbing1.dosen', 'pembimbing2.dosen'])
            ->where('jurusan_id', $jurusanId);

        switch ($this->tab) {
            case 'siap':
                $query->where('status', 'disetujui_kajur')->orderBy('first_registered_at');
                break;
            case 'scheduled':
                $query->where('status', 'dijadwalkan')->orderBy('tanggal_ujian');
                break;
            case 'completed':
                $query->where('status', 'selesai')->orderBy('completed_at', 'desc');
                break;
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul_penelitian', 'like', '%' . $this->search . '%')
                  ->orWhereHas('mahasiswa', function ($mq) {
                      $mq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('nim', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->jenisFilter) {
            $query->where('jenis_ujian', $this->jenisFilter);
        }

        $pendaftarans = $query->paginate(10);

        $countSiap = Pendaftaran::where('jurusan_id', $jurusanId)->where('status', 'disetujui_kajur')->count();
        $countScheduled = Pendaftaran::where('jurusan_id', $jurusanId)->where('status', 'dijadwalkan')->count();
        $countCompleted = Pendaftaran::where('jurusan_id', $jurusanId)->where('status', 'selesai')->count();

        return view('livewire.panitia.penjadwalan.jadwal-ujians', [
            'pendaftarans' => $pendaftarans,
            'countSiap' => $countSiap,
            'countScheduled' => $countScheduled,
            'countCompleted' => $countCompleted,
        ])->layout('components.layouts.app-auth');
    }
}
