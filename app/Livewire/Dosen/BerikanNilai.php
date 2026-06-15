<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\UjianPenguji;
use App\Models\Penilaian;

class BerikanNilai extends Component
{
    public function render()
    {
        $dosenId = auth()->id();

        $ujianSaya = UjianPenguji::where('dosen_id', $dosenId)
            ->with(['pendaftaran.mahasiswa', 'pendaftaran' => function($q) {
                $q->whereIn('status', ['dijadwalkan', 'selesai']);
            }])
            ->get()
            ->filter(fn($jp) => $jp->pendaftaran !== null);

        // Ambil penilaian yang sudah dibuat
        $penilaianSaya = Penilaian::byDosen($dosenId)->get()->keyBy('pendaftaran_id');

        return view('livewire.dosen.berikan-nilai', [
            'ujianSaya' => $ujianSaya,
            'penilaianSaya' => $penilaianSaya,
        ])->layout('components.layouts.app-auth');
    }
}
