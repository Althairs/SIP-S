<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\KuotaDosen as ModelKuotaDosen;
use App\Models\Prodi;

class KuotaDosen extends Component
{
    use WithPagination;

    public $search = '';
    public $prodiFilter = '';
    public $showEditModal = false;
    public $editDosenId;
    public $editNama;
    public $editNip;
    public $editKuotaPembimbing;
    public $editKuotaPenguji;
    public $editTerpakaiPembimbing;
    public $editTerpakaiPenguji;

    protected $queryString = ['search', 'prodiFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openEditModal($dosenId)
    {
        $dosen = User::with('kuota')->findOrFail($dosenId);
        $this->editDosenId = $dosen->id;
        $this->editNama = $dosen->name;
        $this->editNip = $dosen->nip;
        $this->editKuotaPembimbing = $dosen->kuota?->kuota_pembimbing ?? 5;
        $this->editKuotaPenguji = $dosen->kuota?->kuota_penguji ?? 10;
        $this->editTerpakaiPembimbing = $dosen->kuota?->terpakai_pembimbing ?? 0;
        $this->editTerpakaiPenguji = $dosen->kuota?->terpakai_penguji ?? 0;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['editDosenId', 'editNama', 'editNip', 'editKuotaPembimbing', 'editKuotaPenguji', 'editTerpakaiPembimbing', 'editTerpakaiPenguji']);
    }

    public function saveKuota()
    {
        $this->validate([
            'editKuotaPembimbing' => 'required|integer|min:1|max:20',
            'editKuotaPenguji' => 'required|integer|min:1|max:30',
        ]);

        ModelKuotaDosen::updateOrCreate(
            ['dosen_id' => $this->editDosenId],
            [
                'jurusan_id' => auth()->user()->jurusan_id,
                'kuota_pembimbing' => $this->editKuotaPembimbing,
                'kuota_penguji' => $this->editKuotaPenguji,
            ]
        );

        session()->flash('success', 'Kuota dosen berhasil diperbarui.');
        $this->closeEditModal();
    }

    public function resetKuota($dosenId)
    {
        ModelKuotaDosen::updateOrCreate(
            ['dosen_id' => $dosenId],
            [
                'jurusan_id' => auth()->user()->jurusan_id,
                'kuota_pembimbing' => 5,
                'kuota_penguji' => 10,
                'terpakai_pembimbing' => 0,
                'terpakai_penguji' => 0,
            ]
        );

        session()->flash('success', 'Kuota dosen berhasil direset ke default.');
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $dosens = User::role('dosen')
            ->where('jurusan_id', $jurusanId)
            ->with(['kuota', 'prodi'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->prodiFilter, function ($query) {
                $query->where('prodi_id', $this->prodiFilter);
            })
            ->paginate(10);

        $prodis = Prodi::where('jurusan_id', $jurusanId)->active()->get();

        return view('livewire.kajur.kuota-dosen', [
            'dosens' => $dosens,
            'prodis' => $prodis,
        ])->layout('components.layouts.app-auth');
    }
}
