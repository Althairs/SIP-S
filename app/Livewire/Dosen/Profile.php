<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $nip;
    public $nomor_hp;
    public $alamat;
    public $foto;
    public $currentFoto;

    // Password
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public $activeTab = 'profile';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nip = $user->nip;
        $this->nomor_hp = $user->nomor_hp;
        $this->alamat = $user->alamat;
        $this->currentFoto = $user->foto;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    protected function rulesForProfile()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'nomor_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ];
    }

    protected function rulesForPassword()
    {
        return [
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    protected $messages = [
        'current_password.current_password' => 'Password saat ini tidak cocok.',
    ];

    public function updateProfile()
    {
        $this->validate($this->rulesForProfile());

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
        ];

        if ($this->foto) {
            $data['foto'] = $this->foto->store('profile-photos', 'public');
            $this->currentFoto = $data['foto'];
        }

        Auth::user()->update($data);
        session()->flash('success', 'Profile berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $this->validate($this->rulesForPassword());

        Auth::user()->update(['password' => Hash::make($this->password)]);
        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('success', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.dosen.profile')->layout('components.layouts.app-auth');
    }
}
