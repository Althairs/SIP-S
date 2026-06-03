<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\KuotaDosen;
use App\Models\PendaftaranDosen;
use App\Models\UjianPenguji;

class KuotaSaya extends Component
{
    public $kuota;
    public $pembimbingList;
    public $pengujiList;

    public function mount()
    {
        $dosenId = auth()->id();

        $this->kuota = KuotaDosen::where('dosen_id', $dosenId)->first();

        // List bimbingan aktif
        $this->pembimbingList = PendaftaranDosen::where('dosen_id', $dosenId)
            ->whereIn('peran', ['pembimbing_1', 'pembimbing_2'])
            ->with('pendaftaran.mahasiswa')
            ->get();

        // List menguji
        $this->pengujiList = UjianPenguji::where('dosen_id', $dosenId)
            ->with('pendaftaran.mahasiswa')
            ->get();
    }

    public function render()
    {
        return view('livewire.dosen.kuota-saya')->layout('components.layouts.app-auth');
    }
}
