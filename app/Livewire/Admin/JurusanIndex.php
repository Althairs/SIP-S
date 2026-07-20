<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Jurusan;

class JurusanIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filterStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function deleteJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        // Cek apakah jurusan memiliki prodi atau users
        if ($jurusan->prodis()->count() > 0 || $jurusan->users()->count() > 0) {
            session()->flash('error', 'Jurusan tidak dapat dihapus karena masih memiliki Prodi atau Users.');
            return;
        }

        $jurusan->delete();
        session()->flash('success', 'Jurusan berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update(['is_active' => !$jurusan->is_active]);
        session()->flash('success', 'Status jurusan berhasil diubah.');
    }

    public function render()
    {
        $jurusans = Jurusan::withCount(['prodis', 'users'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_jurusan', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_jurusan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.jurusan-index', [
            'jurusans' => $jurusans,
        ])->layout('components.layouts.app-auth');
    }
}
