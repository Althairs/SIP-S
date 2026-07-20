<?php

namespace App\Livewire\Kajur;

use App\Services\PermissionService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Pendaftaran;

class VerifikasiSeminarProposal extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approvePendaftaran($id)
    {
        $p = Pendaftaran::findOrFail($id);
        $p->update([
            'status' => 'disetujui_kajur',
            'approved_at' => now(),
        ]);
        session()->flash('success', 'Pendaftaran disetujui dan diteruskan ke Panitia Penjadwalan.');
    }

    public function rejectPendaftaran($id)
    {
        $p = Pendaftaran::findOrFail($id);
        $p->update(['status' => 'ditolak_kajur']);
        session()->flash('success', 'Pendaftaran ditolak.');
    }

    public function revisiPendaftaran($id)
    {
        $p = Pendaftaran::findOrFail($id);
        $p->update(['status' => 'revisi']);
        session()->flash('success', 'Pendaftaran dikembalikan untuk revisi.');
    }

    public function render()
    {
        $pendaftarans = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pembimbing1.dosen', 'pembimbing2.dosen'])
            ->where(PermissionService::jurusanScope())
            ->where('jenis_ujian', 'seminar_proposal')
            ->whereIn('status', ['disetujui_sekjur', 'disetujui_kajur', 'ditolak_kajur'])
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
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.kajur.verifikasi-index', [
            'pendaftarans' => $pendaftarans,
            'title' => 'Verifikasi Seminar Proposal',
            'jenisUjian' => 'seminar_proposal',
        ])->layout('components.layouts.app-auth');
    }
}
