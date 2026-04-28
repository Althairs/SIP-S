<?php

namespace App\Livewire\Kajur;

use Livewire\Component;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $totalDosen;
    public $totalMahasiswa;
    public $totalPanitia;
    public $jurusanNama;

    public function mount()
    {
        $user = Auth::user();
        $this->jurusanNama = $user->jurusan?->nama_jurusan ?? 'Belum ditentukan';

        $jurusanId = $user->jurusan_id;

        $this->totalDosen = User::role('dosen')->where('jurusan_id', $jurusanId)->count();
        $this->totalMahasiswa = User::role('mahasiswa')->where('jurusan_id', $jurusanId)->count();
        $this->totalPanitia = User::role('panitia')->where('jurusan_id', $jurusanId)->count();
    }

    public function render()
    {
        return view('livewire.kajur.dashboard')->layout('components.layouts.app-auth');
    }
}
