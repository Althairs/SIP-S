<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class Nilai extends Component
{
    public $nilais;
    public $rataRata;
    public $totalUjian;
    public $selectedUjian = null;
    public $showDetail = false;

    public function mount()
    {
        $userId = Auth::id();

        $this->nilais = Pendaftaran::with(['dosens.dosen'])
            ->where('mahasiswa_id', $userId)
            ->whereNotNull('nilai_total')
            ->latest()
            ->get();

        $this->totalUjian = $this->nilais->count();
        $this->rataRata = $this->nilais->avg('nilai_total');
    }

    public function showDetailNilai($id)
    {
        $this->selectedUjian = Pendaftaran::with(['dosens.dosen', 'jurusan'])
            ->where('mahasiswa_id', Auth::id())
            ->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedUjian = null;
    }

    public function getGradeLabel($grade)
    {
        return match($grade) {
            'A' => 'Sangat Memuaskan',
            'B' => 'Memuaskan',
            'C' => 'Cukup',
            'D' => 'Kurang',
            'E' => 'Tidak Lulus',
            default => '-',
        };
    }

    public function getGradeColor($grade)
    {
        return match($grade) {
            'A' => 'green',
            'B' => 'blue',
            'C' => 'yellow',
            'D' => 'orange',
            'E' => 'red',
            default => 'gray',
        };
    }

    public function render()
    {
        return view('livewire.mahasiswa.nilai')->layout('components.layouts.app-auth');
    }
}
