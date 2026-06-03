<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Revisi;
use App\Models\UjianPenguji;
use App\Models\Pendaftaran;

class DaftarRevisi extends Component
{
    public function render()
    {
        $dosenId = auth()->id();

        // Ujian yang sudah selesai di mana dosen ini jadi penguji
        $ujianSelesai = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function($q) {
                $q->where('status', 'selesai');
            })
            ->with(['pendaftaran.mahasiswa', 'pendaftaran.revisis' => function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            }])
            ->get();

        // Revisi yang sudah dibuat
        $revisiSaya = Revisi::byDosen($dosenId)
            ->with('pendaftaran.mahasiswa')
            ->latest()
            ->get();

        return view('livewire.dosen.daftar-revisi', [
            'ujianSelesai' => $ujianSelesai,
            'revisiSaya' => $revisiSaya,
        ])->layout('components.layouts.app-auth');
    }
}
