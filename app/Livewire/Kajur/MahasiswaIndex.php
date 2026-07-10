<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MahasiswaIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $prodiFilter = '';

    #[Url(history: true)]
    public $angkatanFilter = '';

    #[Url(history: true)]
    public $statusFilter = '';
    public $showModal = false;
    public $editMode = false;
    public $userId;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $nim = '';
    public $prodi_id = '';
    public $angkatan = '';
    public $nomor_hp = '';
    public $alamat = '';
    public $is_active = true;

    protected $queryString = ['search', 'prodiFilter', 'angkatanFilter', 'statusFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedProdiFilter()
    {
        $this->resetPage();
    }

    public function updatedAngkatanFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->authorize('create_mahasiswa');
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->authorize('edit_mahasiswa');
        $this->resetForm();
        $this->editMode = true;
        $this->userId = $id;

        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim;
        $this->prodi_id = $user->prodi_id;
        $this->nomor_hp = $user->nomor_hp;
        $this->alamat = $user->alamat;
        $this->is_active = $user->is_active;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'email', 'password', 'password_confirmation',
            'nim', 'prodi_id', 'angkatan', 'nomor_hp', 'alamat', 'is_active',
            'userId', 'editMode'
        ]);
    }

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->userId],
            'nim' => ['required', 'string', 'max:15', 'unique:users,nim,' . $this->userId],
            'prodi_id' => ['nullable', 'exists:prodis,id'],
            'angkatan' => ['nullable', 'string', 'max:4'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];

        if (!$this->editMode) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->authorize('edit_dosen');
        } else {
            $this->authorize('create_dosen');
        }

        $validated = $this->validate();

        $jurusanId = Auth::user()->jurusan_id;

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nim' => $this->nim,
            'prodi_id' => $this->prodi_id ?: null,
            'jurusan_id' => $jurusanId,
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editMode) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            session()->flash('success', 'Mahasiswa berhasil diperbarui.');
        } else {
            $user = User::create($data);
            $user->assignRole('mahasiswa');
            session()->flash('success', 'Mahasiswa berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $this->authorize('edit_mahasiswa');
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status mahasiswa berhasil diubah.');
    }

    public function deleteMahasiswa($id)
    {
        $this->authorize('delete_mahasiswa');
        User::findOrFail($id)->delete();
        session()->flash('success', 'Mahasiswa berhasil dihapus.');
    }

    public function exportExcel()
    {
        session()->flash('info', 'Fitur export akan segera tersedia.');
    }

    public function render()
    {
        $jurusanId = Auth::user()->jurusan_id;

        $mahasiswas = User::role('mahasiswa')
            ->where('jurusan_id', $jurusanId)
            ->with('prodi')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nim', 'like', '%' . $this->search . '%');
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
        $angkatans = User::role('mahasiswa')->where('jurusan_id', $jurusanId)
            ->distinct()->pluck('angkatan')->filter();

        return view('livewire.kajur.mahasiswa-index', [
            'mahasiswas' => $mahasiswas,
            'prodis' => $prodis,
            'angkatans' => $angkatans,
            'canCreate' => auth()->user()->can('create_mahasiswa'),
            'canEdit' => auth()->user()->can('edit_mahasiswa'),
            'canDelete' => auth()->user()->can('delete_mahasiswa'),
        ])->layout('components.layouts.app-auth');
    }
}
