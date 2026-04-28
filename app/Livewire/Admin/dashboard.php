<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{
    public $totalJurusan;
    public $totalProdi;
    public $totalUsers;
    public $totalKajur;
    public $totalSekjur;
    public $totalDosen;
    public $totalMahasiswa;

    public function mount()
    {
        $this->totalJurusan = Jurusan::count();
        $this->totalProdi = Prodi::count();
        $this->totalUsers = User::count();
        $this->totalKajur = User::role('kajur')->count();
        $this->totalSekjur = User::role('sekjur')->count();
        $this->totalDosen = User::role('dosen')->count();
        $this->totalMahasiswa = User::role('mahasiswa')->count();
    }

    public function render()
    {
        $recentUsers = User::with('jurusan', 'roles')
            ->latest()
            ->take(5)
            ->get();

        $recentJurusans = Jurusan::withCount('prodis', 'users')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'recentUsers' => $recentUsers,
            'recentJurusans' => $recentJurusans,
        ])->layout('components.layouts.app-auth');
    }
}
