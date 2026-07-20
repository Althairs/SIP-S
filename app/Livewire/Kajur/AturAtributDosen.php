<?php

namespace App\Livewire\Kajur;

use App\Services\PermissionService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\User;
use App\Models\BidangKeahlian;
use App\Models\Kepakaran;

class AturAtributDosen extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $kepakaranFilter = '';

    #[Url]
    public $bidangFilter = '';

    public $showForm = false;
    public $editDosenId;
    public $editDosenNama;
    public $editDosenNip;
    public $selectedKepakaran = '';
    public $selectedBidangKeahlian = [];

    public $quickMode = false;
    public $quickKepakaran = '';
    public $quickBidangKeahlian = [];
    public $selectedDosenIds = [];
    public $selectAll = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKepakaranFilter()
    {
        $this->resetPage();
    }

    public function updatingBidangFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedDosenIds = $this->getFilteredDosens()->pluck('id')->toArray();
        } else {
            $this->selectedDosenIds = [];
        }
    }

    public function openEdit($id)
    {
        $dosen = User::with(['kepakaran', 'bidangKeahlians'])->findOrFail($id);
        $this->editDosenId = $dosen->id;
        $this->editDosenNama = $dosen->name;
        $this->editDosenNip = $dosen->nip;
        $this->selectedKepakaran = $dosen->kepakaran_id ?? '';
        $this->selectedBidangKeahlian = $dosen->bidangKeahlians->pluck('id')->toArray();
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['editDosenId', 'editDosenNama', 'editDosenNip', 'selectedKepakaran', 'selectedBidangKeahlian']);
    }

    public function toggleBidang($bidangId)
    {
        if (in_array($bidangId, $this->selectedBidangKeahlian)) {
            $this->selectedBidangKeahlian = array_values(array_diff($this->selectedBidangKeahlian, [$bidangId]));
        } else {
            $this->selectedBidangKeahlian[] = (string) $bidangId;
        }
    }

    public function saveAtribut()
    {
        $this->validate([
            'selectedKepakaran' => 'nullable',
            'selectedBidangKeahlian' => 'nullable|array',
            'selectedBidangKeahlian.*' => 'exists:bidang_keahlians,id',
        ]);

        $dosen = User::findOrFail($this->editDosenId);

        $dosen->update([
            'kepakaran_id' => $this->selectedKepakaran ?: null,
        ]);

        $dosen->bidangKeahlians()->sync($this->selectedBidangKeahlian ?: []);

        session()->flash('success', "Atribut dosen <strong>{$dosen->name}</strong> berhasil diperbarui.");
        $this->closeForm();
    }

    public function applyQuickAssign()
    {
        if (empty($this->selectedDosenIds)) {
            session()->flash('error', 'Pilih minimal satu dosen.');
            return;
        }

        $count = 0;
        foreach ($this->selectedDosenIds as $dosenId) {
            $dosen = User::find($dosenId);
            if ($dosen && $dosen->hasRole('dosen')) {
                if ($this->quickKepakaran) {
                    $dosen->update(['kepakaran_id' => $this->quickKepakaran]);
                }
                if (!empty($this->quickBidangKeahlian)) {
                    $dosen->bidangKeahlians()->syncWithoutDetaching($this->quickBidangKeahlian);
                }
                $count++;
            }
        }

        $this->selectedDosenIds = [];
        $this->selectAll = false;
        $this->quickMode = false;

        session()->flash('success', "Atribut berhasil diterapkan ke <strong>$count dosen</strong>.");
    }

    public function resetAtribut($dosenId)
    {
        $dosen = User::findOrFail($dosenId);
        $dosen->update(['kepakaran_id' => null]);
        $dosen->bidangKeahlians()->detach();
        session()->flash('success', "Atribut dosen <strong>{$dosen->name}</strong> direset.");
    }

    private function getFilteredDosens()
    {
        $jurusanId = PermissionService::getJurusanId();
        $isSuperAdmin = auth()->user()->hasRole('super_admin');

        return User::role('dosen')
            // ->where('jurusan_id', $jurusanId)
            ->when(!$isSuperAdmin, function ($query) use ($jurusanId) {
                return $query->where('jurusan_id', $jurusanId);
            })
            ->with(['kepakaran', 'bidangKeahlians', 'prodi'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kepakaranFilter, function ($query) {
                if ($this->kepakaranFilter === 'null') {
                    $query->whereNull('kepakaran_id');
                } else {
                    $query->where('kepakaran_id', $this->kepakaranFilter);
                }
            })
            ->when($this->bidangFilter, function ($query) {
                if ($this->bidangFilter === 'null') {
                    $query->whereDoesntHave('bidangKeahlians');
                } else {
                    $query->whereHas('bidangKeahlians', function ($q) {
                        $q->where('bidang_keahlian_id', $this->bidangFilter);
                    });
                }
            })
            ->orderBy('name');
    }

    public function render()
    {
        $jurusanId = PermissionService::getJurusanId();
        $isSuperAdmin = auth()->user()->hasRole('super_admin');

        $dosens = $this->getFilteredDosens()->paginate(12);

        $listKepakaran = Kepakaran::active()->orderBy('hierarki_level')->get();
        // $listBidangKeahlian = BidangKeahlian::active()->byJurusan($jurusanId)->orderBy('nama_bidang')->get();
        $listBidangKeahlian = BidangKeahlian::active()
            ->when(!$isSuperAdmin, function ($query) use ($jurusanId) {
                return $query->byJurusan($jurusanId);
            })
            ->orderBy('nama_bidang')->get();

        $baseDosenQuery = User::role('dosen')
            ->when(!$isSuperAdmin, function ($query) use ($jurusanId) {
                return $query->where('jurusan_id', $jurusanId);
            });    
        $totalDosen = User::role('dosen')->where('jurusan_id', $jurusanId)->count();
        $dosenDenganKepakaran = User::role('dosen')->where('jurusan_id', $jurusanId)->whereNotNull('kepakaran_id')->count();
        $dosenDenganBidang = User::role('dosen')->where('jurusan_id', $jurusanId)->whereHas('bidangKeahlians')->count();
        $dosenBelumDiatur = User::role('dosen')->where('jurusan_id', $jurusanId)
            ->where(function ($q) {
                $q->whereNull('kepakaran_id')->orWhereDoesntHave('bidangKeahlians');
            })->count();

        return view('livewire.kajur.atur-atribut-dosen', [
            'dosens' => $dosens,
            'listKepakaran' => $listKepakaran,
            'listBidangKeahlian' => $listBidangKeahlian,
            'totalDosen' => $totalDosen,
            'dosenDenganKepakaran' => $dosenDenganKepakaran,
            'dosenDenganBidang' => $dosenDenganBidang,
            'dosenBelumDiatur' => $dosenBelumDiatur,
        ])->layout('components.layouts.app-auth');
    }
}
