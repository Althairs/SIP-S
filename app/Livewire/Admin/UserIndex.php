<?php

namespace App\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Prodi;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $jurusanFilter = '';

    #[Url]
    public $prodiFilter = '';

    #[Url]
    public $roleFilter = '';

    #[Url]
    public $statusFilter = '';

    public $onlyKajurSekjur = false;

    public function mount()
    {
        if (request()->routeIs('admin.kajur-sekjur.index')) {
            $this->onlyKajurSekjur = true;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingJurusanFilter()
    {
        $this->resetPage();
    }

    public function updatingProdiFilter()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        abort_unless(auth()->user()->can('edit_users'), 403);

        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status user berhasil diubah.');
    }

    public function deleteUser($userId)
    {
        abort_unless(auth()->user()->can('delete_users'), 403);

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
            ->when($this->onlyKajurSekjur, function ($query) {
                if ($this->roleFilter) {
                    $query->role($this->roleFilter);
                } else {
                    $query->role(['kajur', 'sekjur']);
                }
            })
            ->when(!$this->onlyKajurSekjur && $this->roleFilter, function ($query) {
                $query->role($this->roleFilter);
            })
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
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate($this->onlyKajurSekjur ? 10 : 15);

        $jurusans = Jurusan::active()->get();
        $prodis = Prodi::active()->get();

        $roles = $this->onlyKajurSekjur
            ? Role::whereIn('name', ['kajur', 'sekjur'])->get()
            : Role::all();

        return view('livewire.admin.user-index', [
            'users' => $users,
            'jurusans' => $jurusans,
            'prodis' => $prodis,
            'roles' => $roles,
        ])->layout('components.layouts.app-auth');
    }
}
