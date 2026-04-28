<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Prodi;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Component
{
    public $editMode = false;
    public $userId = null;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';
    public $jurusan_id = '';
    public $prodi_id = '';
    public $nip = '';
    public $nim = '';
    public $nomor_hp = '';
    public $alamat = '';
    public $is_active = true;

    public function mount()
    {
        // Check if edit mode
        if (request()->has('edit')) {
            $this->editMode = true;
            $this->userId = request()->get('edit');
            $this->loadUser();
        }
    }

    public function loadUser()
    {
        $user = User::findOrFail($this->userId);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first() ?? '';
        $this->jurusan_id = $user->jurusan_id;
        $this->prodi_id = $user->prodi_id;
        $this->nip = $user->nip;
        $this->nim = $user->nim;
        $this->nomor_hp = $user->nomor_hp;
        $this->alamat = $user->alamat;
        $this->is_active = $user->is_active;
    }

    public function updatedRole()
    {
        // Reset fields berdasarkan role
        if (in_array($this->role, ['kajur', 'sekjur', 'panitia', 'dosen'])) {
            $this->nim = '';
        } else {
            $this->nip = '';
        }
    }

    protected function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
            'jurusan_id' => ['nullable', 'exists:jurusans,id'],
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

        // Validasi NIP untuk role dosen/kajur/sekjur/panitia
        if (in_array($this->role, ['kajur', 'sekjur', 'panitia', 'dosen'])) {
            $rules['nip'] = ['required', 'string', 'max:20', Rule::unique('users', 'nip')->ignore($this->userId)];
        }

        // Validasi NIM untuk role mahasiswa
        if ($this->role === 'mahasiswa') {
            $rules['nim'] = ['required', 'string', 'max:15', Rule::unique('users', 'nim')->ignore($this->userId)];
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Role wajib dipilih.',
        'nip.required' => 'NIP wajib diisi untuk role ini.',
        'nip.unique' => 'NIP sudah terdaftar.',
        'nim.required' => 'NIM wajib diisi untuk mahasiswa.',
        'nim.unique' => 'NIM sudah terdaftar.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'jurusan_id' => $this->jurusan_id ?: null,
            'prodi_id' => $this->prodi_id ?: null,
            'nip' => $this->nip ?: null,
            'nim' => $this->nim ?: null,
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editMode) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            $user->syncRoles([$this->role]);
            session()->flash('success', 'User berhasil diperbarui.');
        } else {
            $user = User::create($data);
            $user->assignRole($this->role);
            session()->flash('success', 'User berhasil ditambahkan.');
        }

        return $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        $jurusans = Jurusan::active()->get();
        $prodis = Prodi::active()->get();
        $roles = Role::all();

        return view('livewire.admin.user-create', [
            'jurusans' => $jurusans,
            'prodis' => $prodis,
            'roles' => $roles,
        ])->layout('components.layouts.app-auth');
    }
}
