<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Kepakaran as ModelKepakaran;

class Kepakaran extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $kepakaranId;

    public $nama_kepakaran = '';
    public $hierarki_level = 1;
    public $deskripsi = '';
    public $is_active = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->kepakaranId = $id;

        $k = ModelKepakaran::findOrFail($id);
        $this->nama_kepakaran = $k->nama_kepakaran;
        $this->hierarki_level = $k->hierarki_level;
        $this->deskripsi = $k->deskripsi;
        $this->is_active = $k->is_active;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['nama_kepakaran', 'hierarki_level', 'deskripsi', 'is_active', 'kepakaranId', 'editMode']);
        $this->hierarki_level = 1;
    }

    protected function rules()
    {
        return [
            'nama_kepakaran' => ['required', 'string', 'max:255', 'unique:kepakarans,nama_kepakaran,' . $this->kepakaranId],
            'hierarki_level' => ['required', 'integer', 'min:1', 'max:20'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    protected $messages = [
        'nama_kepakaran.required' => 'Nama kepakaran wajib diisi.',
        'nama_kepakaran.unique' => 'Nama kepakaran sudah ada.',
        'hierarki_level.required' => 'Level hierarki wajib diisi.',
        'hierarki_level.min' => 'Level minimal 1.',
    ];

    public function save()
    {
        $validated = $this->validate();

        if ($this->editMode) {
            ModelKepakaran::findOrFail($this->kepakaranId)->update($validated);
            session()->flash('success', 'Kepakaran berhasil diperbarui.');
        } else {
            ModelKepakaran::create($validated);
            session()->flash('success', 'Kepakaran berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $k = ModelKepakaran::findOrFail($id);
        $k->update(['is_active' => !$k->is_active]);
        session()->flash('success', 'Status kepakaran berhasil diubah.');
    }

    public function deleteKepakaran($id)
    {
        $k = ModelKepakaran::findOrFail($id);
        if ($k->users()->count() > 0) {
            session()->flash('error', 'Kepakaran tidak dapat dihapus karena masih digunakan oleh dosen.');
            return;
        }
        $k->delete();
        session()->flash('success', 'Kepakaran berhasil dihapus.');
    }

    public function render()
    {
        $kepakarans = ModelKepakaran::withCount('users')
            ->when($this->search, function ($query) {
                $query->where('nama_kepakaran', 'like', '%' . $this->search . '%');
            })
            ->orderBy('hierarki_level')
            ->paginate(15);

        return view('livewire.kajur.kepakaran', [
            'kepakarans' => $kepakarans,
        ])->layout('components.layouts.app-auth');
    }
}
