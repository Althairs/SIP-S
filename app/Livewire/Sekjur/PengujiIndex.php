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


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
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
        $totalMenunggu = Pendaftaran::where('jurusan_id', $jurusanId)
            ->whereIn('status', ['disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur'])
            ->doesntHave('pengujis')
            ->count();

        $totalSudahDiatur = Pendaftaran::where('jurusan_id', $jurusanId)
            ->whereIn('status', ['disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan'])
            ->has('pengujis')
            ->count();

        return view('livewire.sekjur.penguji-index', [
            'pendaftarans' => $pendaftarans,
            'totalMenunggu' => $totalMenunggu,
            'totalSudahDiatur' => $totalSudahDiatur,
        ])->layout('components.layouts.app-auth');
    }
}
