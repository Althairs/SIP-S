<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);

        // Cegah hapus role yang sedang digunakan
        if ($role->users()->count() > 0) {
            session()->flash('error', "Role '{$role->name}' tidak dapat dihapus karena masih digunakan oleh {$role->users()->count()} user.");
            return;
        }

        // Cegah hapus role super_admin
        if ($role->name === 'super_admin') {
            session()->flash('error', 'Role Super Admin tidak dapat dihapus.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role berhasil dihapus.');
    }

    public function render()
    {
        $roles = Role::withCount('users', 'permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.role-index', [
            'roles' => $roles,
        ])->layout('components.layouts.app-auth');
    }
}
