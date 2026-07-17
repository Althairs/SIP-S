<?php

namespace App\Livewire\Panitia\Penjadwalan;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Ruangan;

class SettingRuangan extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    public $showForm = false;
    public $editMode = false;
    public $ruanganId;

    public $kode_ruangan = '';
    public $nama_ruangan = '';
    public $lokasi = '';
    public $kapasitas = 20;
    public $deskripsi = '';
    public $is_active = true;

    protected $queryString = ['search'];

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
        $this->ruanganId = $id;

        $r = Ruangan::findOrFail($id);
        $this->kode_ruangan = $r->kode_ruangan;
        $this->nama_ruangan = $r->nama_ruangan;
        $this->lokasi = $r->lokasi;
        $this->kapasitas = $r->kapasitas;
        $this->deskripsi = $r->deskripsi;
        $this->is_active = $r->is_active;

        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['kode_ruangan', 'nama_ruangan', 'lokasi', 'kapasitas', 'deskripsi', 'is_active', 'ruanganId', 'editMode']);
        $this->kapasitas = 20;
    }

    protected function rules()
    {
        return [
            'kode_ruangan' => ['required', 'string', 'max:10', 'unique:ruangans,kode_ruangan,' . $this->ruanganId],
            'nama_ruangan' => ['required', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['jurusan_id'] = auth()->user()->jurusan_id;

        if ($this->editMode) {
            Ruangan::findOrFail($this->ruanganId)->update($validated);
            session()->flash('success', 'Ruangan berhasil diperbarui.');
        } else {
            Ruangan::create($validated);
            session()->flash('success', 'Ruangan berhasil ditambahkan.');
        }

        $this->closeForm();
    }

    public function toggleStatus($id)
    {
        $r = Ruangan::findOrFail($id);
        $r->update(['is_active' => !$r->is_active]);
        session()->flash('success', 'Status ruangan diubah.');
    }

    public function deleteRuangan($id)
    {
        Ruangan::findOrFail($id)->delete();
        session()->flash('success', 'Ruangan dihapus.');
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $ruangans = Ruangan::where('jurusan_id', $jurusanId)
            ->when($this->search, function ($query) {
                $query->where('nama_ruangan', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_ruangan', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.panitia.penjadwalan.setting-ruangan', [
            'ruangans' => $ruangans,
        ])->layout('components.layouts.app-auth');
    }
}
