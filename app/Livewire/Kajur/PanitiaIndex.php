<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Prodi;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;

class PanitiaIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $prodiFilter = '';
    public $statusFilter = '';
    public $showModal = false;
    public $editMode = false;
    public $userId;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $nip = '';
    public $prodi_id = '';
    public $nomor_hp = '';
    public $alamat = '';
    public $is_active = true;

    protected $queryString = ['search', 'prodiFilter', 'statusFilter'];

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
        $this->userId = $id;

        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nip = $user->nip;
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

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'nip', 'prodi_id', 'nomor_hp', 'alamat', 'is_active', 'userId', 'editMode']);
    }

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->userId],
            'nip' => ['required', 'string', 'max:20', 'unique:users,nip,' . $this->userId],
            'prodi_id' => ['nullable', 'exists:prodis,id'],
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
        $validated = $this->validate();

        $jurusanId = Auth::user()->jurusan_id;

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nip' => $this->nip,
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
            session()->flash('success', 'Panitia berhasil diperbarui.');
        } else {
            $user = User::create($data);
            $user->assignRole('panitia');
            session()->flash('success', 'Panitia berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status panitia berhasil diubah.');
    }

    public function deletePanitia($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Panitia berhasil dihapus.');
    }

    public function render()
    {
        $jurusanId = Auth::user()->jurusan_id;

        $panitias = User::role('panitia')
            ->where('jurusan_id', $jurusanId)
            ->with('prodi')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%');
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
        ])->layout('components.layouts.app-auth');
    }
}
