<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Prodi;
use App\Models\Jurusan;

class ProdiIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $jurusanFilter = '';
    public $filterStatus = '';

    protected $queryString = ['search', 'jurusanFilter', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteProdi($id)
    {
        $prodi = Prodi::findOrFail($id);

        if ($prodi->users()->count() > 0) {
            session()->flash('error', 'Prodi tidak dapat dihapus karena masih memiliki Users.');
            return;
        }

        $prodi->delete();
        session()->flash('success', 'Prodi berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->update(['is_active' => !$prodi->is_active]);
        session()->flash('success', 'Status prodi berhasil diubah.');
    }

    public function render()
    {
        $prodis = Prodi::with('jurusan')
            ->withCount('users')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_prodi', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_prodi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->jurusanFilter, function ($query) {
                $query->where('jurusan_id', $this->jurusanFilter);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        $jurusans = Jurusan::active()->get();

        return view('livewire.admin.prodi-index', [
            'prodis' => $prodis,
            'jurusans' => $jurusans,
        ])->layout('components.layouts.app-auth');
    }
}
