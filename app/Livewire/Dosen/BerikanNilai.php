<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\UjianPenguji;
use App\Models\Pendaftaran;

class BerikanNilai extends Component
{
    public function render()
    {
        $dosenId = auth()->id();

        // Ujian yang dosen ini jadi penguji
        $ujianSaya = UjianPenguji::where('dosen_id', $dosenId)
            ->with(['pendaftaran.mahasiswa', 'pendaftaran' => function($q) {
                $q->whereIn('status', ['dijadwalkan', 'selesai']);
            }])
            ->get()
            ->filter(function($jp) { return $jp->pendaftaran !== null; });

        return view('livewire.dosen.berikan-nilai', [
            'ujianSaya' => $ujianSaya,
        ])->layout('components.layouts.app-auth');
    }
}
