<?php

namespace App\Livewire\Kajur;

use App\Services\PermissionService;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Url;
// use Illuminate\Container\Attributes\Auth;
use Livewire\WithPagination;

class PanitiaIndex extends Component
{
    use WithPagination;

    private const PANITIA_ROLES = [
        'panitia_verifikasi',
        'panitia_penjadwalan',
        'panitia_administrasi',
    ];

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $prodiFilter = '';

    #[Url(history: true)]
    public $roleFilter = '';

    #[Url(history: true)]
    public $statusFilter = '';

    public $showForm = false;

    public $editMode = false;

    public $userId;

    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $nip = '';

    public $role = '';

    public $prodi_id = '';

    public $nomor_hp = '';

    public $alamat = '';

    public $is_active = true;

    public array $summary = [];

    protected $queryString = ['search', 'prodiFilter', 'roleFilter', 'statusFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedProdiFilter()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
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
        $this->userId = $id;

        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nip = $user->nip;
        $this->role = $user->getRoleNames()->first() ?? '';
        $this->prodi_id = $user->prodi_id;
        $this->nomor_hp = $user->nomor_hp;
        $this->alamat = $user->alamat;
        $this->is_active = $user->is_active;

        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'nip', 'role', 'prodi_id', 'nomor_hp', 'alamat', 'is_active', 'userId', 'editMode']);
    }

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$this->userId],
            'role' => ['required', 'string', Rule::in(self::PANITIA_ROLES)],
            'nip' => ['required', 'string', 'max:20', 'unique:users,nip,'.$this->userId],
            'prodi_id' => ['nullable', 'exists:prodis,id'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];

        if (! $this->editMode) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function save()
    {
        $validated = $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nip' => $this->nip,
            'prodi_id' => $this->prodi_id ?: null,
            'jurusan_id' => PermissionService::getJurusanId(),
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editMode) {
            abort_unless(auth()->user()->can('edit_panitia'), 403);

            $user = User::findOrFail($this->userId);
            $user->update($data);
            $user->syncRoles([$this->role]);
            session()->flash('success', 'Panitia berhasil diperbarui.');
        } else {
            abort_unless(auth()->user()->can('create_panitia'), 403);

            $user = User::create($data);
            $user->assignRole($this->role);
            session()->flash('success', 'Panitia berhasil ditambahkan.');
        }

        $this->closeForm();
    }

    public function toggleStatus($id)
    {
        abort_unless(auth()->user()->can('edit_panitia'), 403);

        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
        session()->flash('success', 'Status panitia berhasil diubah.');
    }

    public function deletePanitia($id)
    {
        abort_unless(auth()->user()->can('delete_panitia'), 403);

        User::findOrFail($id)->delete();
        session()->flash('success', 'Panitia berhasil dihapus.');
    }

    public function render()
    {
        $jurusanId = PermissionService::getJurusanId();

        $baseQuery = User::role(self::PANITIA_ROLES)
            ->where(PermissionService::jurusanScope());

        $this->summary = [
            'total' => (clone $baseQuery)->count(),
            'verifikasi' => (clone $baseQuery)->whereHas('roles', function ($q) {
                $q->where('name', 'panitia_verifikasi');
            })->count(),
            'penjadwalan' => (clone $baseQuery)->whereHas('roles', function ($q) {
                $q->where('name', 'panitia_penjadwalan');
            })->count(),
            'administrasi' => (clone $baseQuery)->whereHas('roles', function ($q) {
                $q->where('name', 'panitia_administrasi');
            })->count(),
        ];

        $query = (clone $baseQuery)
            ->with(['prodi', 'roles']);

        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $panitias = $query
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('nip', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->prodiFilter, function ($query) {
                $query->where('prodi_id', $this->prodiFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        $prodis = Prodi::where('jurusan_id', $jurusanId)->active()->get();

        return view('livewire.kajur.panitia-index', [
            'panitias' => $panitias,
            'prodis' => $prodis,
            'summary' => $this->summary,
        ])->layout('components.layouts.app-auth');
    }
}
