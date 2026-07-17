<?php

namespace App\Livewire\Kajur;

use App\Services\PermissionService;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\BidangKeahlian;
use Illuminate\Support\Facades\Auth;

class BidangKeahlians extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    public $showForm = false;
    public $editMode = false;
    public $bidangId;

    public $kode = '';
    public $nama_bidang = '';
    public $deskripsi = '';
    public $is_active = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showForm = true;
    }

    public function openEdit($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->bidangId = $id;

        $bidang = BidangKeahlian::findOrFail($id);
        $this->kode = $bidang->kode;
        $this->nama_bidang = $bidang->nama_bidang;
        $this->deskripsi = $bidang->deskripsi;
        $this->is_active = $bidang->is_active;

        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['kode', 'nama_bidang', 'deskripsi', 'is_active', 'bidangId', 'editMode']);
    }

    protected function rules()
    {
        return [
            'kode' => ['required', 'string', 'max:10', 'unique:bidang_keahlians,kode,' . $this->bidangId],
            'nama_bidang' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        $data = [
            'jurusan_id' => PermissionService::getJurusanId(),
            'kode' => $this->kode,
            'nama_bidang' => $this->nama_bidang,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->is_active,
        ];

        if ($this->editMode) {
            BidangKeahlian::findOrFail($this->bidangId)->update($data);
            session()->flash('success', 'Bidang keahlian berhasil diperbarui.');
        } else {
            BidangKeahlian::create($data);
            session()->flash('success', 'Bidang keahlian berhasil ditambahkan.');
        }

        $this->closeForm();
    }

    public function toggleStatus($id)
    {
        $bidang = BidangKeahlian::findOrFail($id);
        $bidang->update(['is_active' => !$bidang->is_active]);
        session()->flash('success', 'Status bidang keahlian berhasil diubah.');
    }

    public function deleteBidang($id)
    {
        BidangKeahlian::findOrFail($id)->delete();
        session()->flash('success', 'Bidang keahlian berhasil dihapus.');
    }

    public function render()
    {
        $jurusanId = PermissionService::getJurusanId();

        $bidangs = BidangKeahlian::where('jurusan_id', $jurusanId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_bidang', 'like', '%' . $this->search . '%')
                      ->orWhere('kode', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('pendaftarans')
            ->latest()
            ->paginate(10);

        return view('livewire.kajur.bidang-keahlian', [
            'bidangs' => $bidangs,
        ])->layout('components.layouts.app-auth');
    }
}
