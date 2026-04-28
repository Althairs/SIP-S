<?php

namespace App\Livewire\Panitia\Penjadwalan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftaran;
use Carbon\Carbon;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;

class JadwalUjians extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $jenisFilter = '';
    public $tab = 'siap'; // siap, scheduled, completed
    public $showScheduleModal = false;
    public $selectedPendaftaran;
    public $tanggal_ujian;
    public $ruangan = '';
    public $sesi = 1;
    public $catatan = '';
    public $scheduleMode = 'auto'; // auto atau manual

    public $ruanganOptions = [
        'Ruang Seminar 1',
        'Ruang Seminar 2',
        'Ruang Sidang 1',
        'Ruang Sidang 2',
        'Aula Fakultas',
        'Ruang Rapat Jurusan',
    ];

    protected $queryString = ['search', 'statusFilter', 'jenisFilter', 'tab'];

    public function mount()
    {
        if (request()->has('tab')) {
            $this->tab = request()->get('tab');
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
    }

    public function openScheduleModal($id)
    {
        $this->selectedPendaftaran = Pendaftaran::with(['mahasiswa', 'bidangKeahlians'])->findOrFail($id);

        // Auto-generate jadwal: minimal 1 minggu ke depan
        $minDate = Carbon::now()->addWeek()->startOfDay();
        $suggestedDate = $minDate->copy()->setHour(9)->setMinute(0);

        $this->tanggal_ujian = $this->selectedPendaftaran->tanggal_ujian
            ? Carbon::parse($this->selectedPendaftaran->tanggal_ujian)->format('Y-m-d\TH:i')
            : $suggestedDate->format('Y-m-d\TH:i');
        $this->ruangan = $this->selectedPendaftaran->ruangan ?? '';
        $this->sesi = $this->selectedPendaftaran->sesi ?? 1;
        $this->scheduleMode = 'auto';
        $this->showScheduleModal = true;
    }

    public function closeScheduleModal()
    {
        $this->showScheduleModal = false;
        $this->reset(['selectedPendaftaran', 'tanggal_ujian', 'ruangan', 'sesi', 'catatan']);
    }

    public function autoGenerateJadwal()
    {
        // Generate jadwal acak 1-2 minggu ke depan
        $daysAhead = rand(7, 14);
        $hour = [8, 10, 13, 15][array_rand([8, 10, 13, 15])];
        $minute = [0, 30][array_rand([0, 30])];

        $autoDate = Carbon::now()->addDays($daysAhead)->setHour($hour)->setMinute($minute);

        $this->tanggal_ujian = $autoDate->format('Y-m-d\TH:i');
        $this->ruangan = $this->ruanganOptions[array_rand($this->ruanganOptions)];
        $this->sesi = rand(1, 4);
        $this->scheduleMode = 'auto';
    }

    public function scheduleUjian()
    {
        $this->validate([
            'tanggal_ujian' => 'required|date|after:now',
            'ruangan' => 'required|string',
            'sesi' => 'required|integer|min:1|max:4',
        ]);

        $this->selectedPendaftaran->update([
            'tanggal_ujian' => Carbon::parse($this->tanggal_ujian),
            'ruangan' => $this->ruangan,
            'sesi' => $this->sesi,
            'status' => 'dijadwalkan',
            'scheduled_at' => now(),
        ]);

        session()->flash('success', 'Jadwal ujian berhasil ditetapkan.');
        $this->closeScheduleModal();
    }

    public function updateStatus($id, $status)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => $status]);
        session()->flash('success', 'Status berhasil diperbarui.');
    }

    public function render()
    {
        $jurusanId = Auth::user()->jurusan_id;

        $query = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen'])
            ->where('jurusan_id', $jurusanId);

        switch ($this->tab) {
            case 'siap':
                // Yang sudah disetujui kajur (sudah ada penguji) dan siap dijadwalkan
                $query->where('status', 'disetujui_kajur');
                break;
            case 'scheduled':
                $query->whereIn('status', ['dijadwalkan']);
                break;
            case 'completed':
                $query->where('status', 'selesai');
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

        $pendaftarans = $query->latest()->paginate(10);

        $countSiap = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'disetujui_kajur')->count();
        $countScheduled = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'dijadwalkan')->count();
        $countCompleted = Pendaftaran::where('jurusan_id', $jurusanId)
            ->where('status', 'selesai')->count();

        return view('livewire.panitia.penjadwalan.jadwal-ujian', [
            'pendaftarans' => $pendaftarans,
            'countSiap' => $countSiap,
            'countScheduled' => $countScheduled,
            'countCompleted' => $countCompleted,
        ])->layout('components.layouts.app-auth');
    }
}
