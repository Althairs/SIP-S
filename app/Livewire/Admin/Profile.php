<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $nomor_hp;
    public $alamat;
    public $foto;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nomor_hp = $user->nomor_hp;
        $this->alamat = $user->alamat;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'], // max 2MB
        ];
    }

    public function updateProfile()
    {
        $validated = $this->validate();

        // Handle foto upload
        if ($this->foto) {
            $path = $this->foto->store('profile-photos', 'public');
            $validated['foto'] = $path;
        }

        Auth::user()->update($validated);

        session()->flash('success', 'Profile berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.profile')->layout('components.layouts.app-auth');
    }
}
