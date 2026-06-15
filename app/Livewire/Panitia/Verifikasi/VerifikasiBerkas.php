<?php

namespace App\Livewire\Panitia\Verifikasi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftaran;

class VerifikasiBerkas extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';
    public $showDetailModal = false;
    public $selectedPendaftaran;

    protected $queryString = ['search', 'statusFilter'];

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
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
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
        $jurusanId = auth()->user()->jurusan_id;

        $pendaftarans = Pendaftaran::with([
            'mahasiswa',
            'bidangKeahlians',
            'dosens.dosen',
            'pembimbing1.dosen',
            'pembimbing2.dosen'
        ])
            ->where('jurusan_id', $jurusanId)
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
