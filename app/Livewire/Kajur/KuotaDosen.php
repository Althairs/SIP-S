<?php

namespace App\Livewire\Kajur;

use App\Services\PermissionService;
use App\Models\Jurusan;
use App\Models\KuotaDosen as ModelKuotaDosen;
use App\Models\Prodi;
use App\Models\User;
use App\Services\KuotaDosenService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class KuotaDosen extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $prodiFilter = '';

    public $showForm = false;

    public $editDosenId;

    public $editNama;

    public $editNip;

    public $editKuotaPembimbing;

    public $editKuotaPenguji;

    public $editTerpakaiPembimbing;

    public $editTerpakaiPenguji;

    public $defaultKuotaPembimbing;

    public $defaultKuotaPenguji;

    protected $queryString = ['search', 'prodiFilter'];

    public function mount(): void
    {
        $jurusan = Jurusan::find(PermissionService::getJurusanId());

        $this->defaultKuotaPembimbing = $jurusan?->default_kuota_pembimbing ?? KuotaDosenService::DEFAULT_KUOTA_PEMBIMBING;
        $this->defaultKuotaPenguji = $jurusan?->default_kuota_penguji ?? KuotaDosenService::DEFAULT_KUOTA_PENGUJI;
    }

    public function updatingProdiFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openEdit($dosenId)
    {
        $dosen = User::with('kuota')->findOrFail($dosenId);
        $this->editDosenId = $dosen->id;
        $this->editNama = $dosen->name;
        $this->editNip = $dosen->nip;
        $this->editKuotaPembimbing = $dosen->kuota?->kuota_pembimbing ?? $this->defaultKuotaPembimbing;
        $this->editKuotaPenguji = $dosen->kuota?->kuota_penguji ?? $this->defaultKuotaPenguji;
        $this->editTerpakaiPembimbing = $dosen->kuota?->terpakai_pembimbing ?? 0;
        $this->editTerpakaiPenguji = $dosen->kuota?->terpakai_penguji ?? 0;
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editDosenId', 'editNama', 'editNip', 'editKuotaPembimbing', 'editKuotaPenguji', 'editTerpakaiPembimbing', 'editTerpakaiPenguji']);
    }

    public function saveKuota()
    {
        $this->validate([
            'editKuotaPembimbing' => 'required|integer|min:1|max:50',
            'editKuotaPenguji' => 'required|integer|min:1|max:50',
        ]);

        ModelKuotaDosen::updateOrCreate(
            ['dosen_id' => $this->editDosenId],
            [
                'jurusan_id' => PermissionService::getJurusanId(),
                'kuota_pembimbing' => $this->editKuotaPembimbing,
                'kuota_penguji' => $this->editKuotaPenguji,
            ]
        );

        session()->flash('success', 'Kuota dosen berhasil diperbarui.');
        $this->closeForm();
    }

    public function saveDefaultKuota()
    {
        $this->validate([
            'defaultKuotaPembimbing' => 'required|integer|min:1|max:50',
            'defaultKuotaPenguji' => 'required|integer|min:1|max:50',
        ]);

        Jurusan::where('id', PermissionService::getJurusanId())->update([
            'default_kuota_pembimbing' => $this->defaultKuotaPembimbing,
            'default_kuota_penguji' => $this->defaultKuotaPenguji,
        ]);

        session()->flash('success', 'Default kuota bulanan berhasil disimpan.');
    }

    public function resetKuota($dosenId)
    {
        $jurusan = Jurusan::findOrFail(PermissionService::getJurusanId());
        $defaults = app(KuotaDosenService::class)->defaultsForJurusan($jurusan);

        ModelKuotaDosen::updateOrCreate(
            ['dosen_id' => $dosenId],
            [
                'jurusan_id' => PermissionService::getJurusanId(),
                'kuota_pembimbing' => $defaults['kuota_pembimbing'],
                'kuota_penguji' => $defaults['kuota_penguji'],
                'terpakai_pembimbing' => 0,
                'terpakai_penguji' => 0,
            ]
        );

        session()->flash('success', 'Kuota dosen berhasil direset ke default.');
    }

    public function resetKuotaBulanan()
    {
        $jurusan = Jurusan::findOrFail(PermissionService::getJurusanId());
        $count = app(KuotaDosenService::class)->resetBulananForJurusan($jurusan);

        session()->flash('success', "Kuota {$count} dosen berhasil direset ke default bulanan.");
    }

    public function render()
    {
        $jurusanId = PermissionService::getJurusanId();
        $jurusan = Jurusan::find($jurusanId);

        $dosens = User::role('dosen')
            ->where(PermissionService::jurusanScope())
            ->with(['kuota', 'prodi'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nip', 'like', '%'.$this->search.'%');
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
            'jurusan' => $jurusan,
        ])->layout('components.layouts.app-auth');
    }
}
