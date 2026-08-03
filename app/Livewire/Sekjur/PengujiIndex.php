<?php

namespace App\Livewire\Sekjur;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Pendaftaran;
use App\Services\PermissionService;

class PengujiIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $statusFilter = '';


    public $showDetail = false;
    public $selectedPendaftaran = null;
    public $isSuperAdmin = false;

    public function mount()
    {
        $this->isSuperAdmin = auth()->user() && auth()->user()->hasRole('super_admin');
    }

    public function showDetail($id)
    {
        $this->selectedPendaftaran = Pendaftaran::with([
            'mahasiswa',
            'mahasiswa.jurusan',
            'mahasiswa.prodi',
            'bidangKeahlians',
            'dosens.dosen',
            'pembimbing1.dosen',
            'pembimbing2.dosen',
            'pengujis.dosen',
            'jurusan',
            'prodi',
        ])->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedPendaftaran = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $jurusanId = PermissionService::getJurusanId();

        // Pendaftaran yang sudah disetujui kaprodi (siap ditambahkan penguji)
        $pendaftarans = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pengujis.dosen.kepakaran'])
            ->where(PermissionService::jurusanScope())
            ->whereIn('status', ['disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul_penelitian', 'like', '%' . $this->search . '%')
                        ->orWhereHas('mahasiswa', function ($mq) {
                            $mq->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('nim', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'has_penguji') {
                    $query->has('pengujis');
                } elseif ($this->statusFilter === 'no_penguji') {
                    $query->doesntHave('pengujis');
                }
            })
            ->latest()
            ->paginate(10);

        // Count stats
        $totalMenunggu = Pendaftaran::when($jurusanId, fn($q) => $q->where('jurusan_id', $jurusanId))
            ->whereIn('status', ['disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur'])
            ->doesntHave('pengujis')
            ->count();

        $totalSudahDiatur = Pendaftaran::when($jurusanId, fn($q) => $q->where('jurusan_id', $jurusanId))
            ->whereIn('status', ['disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan'])
            ->has('pengujis')
            ->count();

        return view('livewire.sekjur.penguji-index', [
            'pendaftarans' => $pendaftarans,
            'totalMenunggu' => $totalMenunggu,
            'totalSudahDiatur' => $totalSudahDiatur,
            'isSuperAdmin' => $this->isSuperAdmin,
        ])->layout('components.layouts.app-auth');
    }
}
