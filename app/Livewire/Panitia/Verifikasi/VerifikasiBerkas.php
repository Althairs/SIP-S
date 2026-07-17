<?php

namespace App\Livewire\Panitia\Verifikasi;

use App\Services\PermissionService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Pendaftaran;

class VerifikasiBerkas extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $statusFilter = 'pending';
    public $showDetail = false;
    public $selectedPendaftaran;

    public function mount()
    {
        if (request()->has('statusFilter')) {
            $this->statusFilter = request()->get('statusFilter');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function showDetail($id)
    {
        $this->selectedPendaftaran = Pendaftaran::with([
            'mahasiswa',
            'bidangKeahlians',
            'dosens.dosen.kepakaran',
            'pembimbing1.dosen',
            'pembimbing2.dosen',
            'jurusan',
            'prodi'
        ])->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedPendaftaran = null;
    }

    public function updateStatus($id, $status)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => $status,
            'approved_at' => $status === 'disetujui_panitia' ? now() : null,
        ]);

        $statusLabel = $status === 'disetujui_panitia' ? 'disetujui dan diteruskan ke Sekjur' : 'ditolak';
        session()->flash('success', "Pendaftaran berhasil {$statusLabel}.");
    }

    public function render()
    {
        $pendaftarans = Pendaftaran::with([
            'mahasiswa',
            'bidangKeahlians',
            'dosens.dosen',
            'pembimbing1.dosen',
            'pembimbing2.dosen'
        ])
            ->where(PermissionService::jurusanScope())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('mahasiswa', function ($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('nim', 'like', '%' . $this->search . '%');
                    })->orWhere('judul_penelitian', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.panitia.verifikasi.verifikasi-berkas', [
            'pendaftarans' => $pendaftarans,
        ])->layout('components.layouts.app-auth');
    }
}
