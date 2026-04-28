<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Prodi;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $jurusanFilter = '';
    public $prodiFilter = '';
    public $roleFilter = '';
    public $statusFilter = '';

    protected $queryString = ['search', 'jurusanFilter', 'prodiFilter', 'roleFilter', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status user berhasil diubah.');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Cegah super admin menghapus dirinya sendiri
        if ($user->hasRole('super_admin')) {
            session()->flash('error', 'Tidak dapat menghapus akun Super Admin.');
            return;
        }

        $user->delete();
        session()->flash('success', 'User berhasil dihapus.');
    }

    public function render()
    {
        $users = User::with(['jurusan', 'prodi', 'roles'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%')
                      ->orWhere('nim', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->jurusanFilter, function ($query) {
                $query->where('jurusan_id', $this->jurusanFilter);
            })
            ->when($this->prodiFilter, function ($query) {
                $query->where('prodi_id', $this->prodiFilter);
            })
            ->when($this->roleFilter, function ($query) {
                $query->role($this->roleFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        $jurusans = Jurusan::active()->get();
        $prodis = Prodi::active()->get();
        $roles = Role::all();

        return view('livewire.admin.user-index', [
            'users' => $users,
            'jurusans' => $jurusans,
            'prodis' => $prodis,
            'roles' => $roles,
        ])->layout('components.layouts.app-auth');
    }
}
