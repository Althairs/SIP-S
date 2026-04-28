<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Jurusan;
use Spatie\Permission\Models\Role;

class KajurSekjurIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $jurusanFilter = '';
    public $roleFilter = '';

    protected $queryString = ['search', 'jurusanFilter', 'roleFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::with(['jurusan', 'roles'])
            ->role(['kajur', 'sekjur'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->jurusanFilter, function ($query) {
                $query->where('jurusan_id', $this->jurusanFilter);
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->latest()
            ->paginate(10);

        $jurusans = Jurusan::active()->get();
        $roles = Role::whereIn('name', ['kajur', 'sekjur'])->get();

        return view('livewire.admin.kajur-sekjur-index', [
            'users' => $users,
            'jurusans' => $jurusans,
            'roles' => $roles,
        ])->layout('components.layouts.app-auth');
    }
}
