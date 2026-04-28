<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class PendaftaranIndex extends Component
{
    public function render()
    {
        $pendaftarans = Pendaftaran::with(['dosens.dosen', 'bidangkeahlians'])
            ->where('mahasiswa_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.mahasiswa.pendaftaran-index', [
            'pendaftarans' => $pendaftarans,
        ])->layout('components.layouts.app-auth');
    }

    public function deletePendaftaran($id)
    {
        $pendaftaran = Pendaftaran::where('mahasiswa_id', Auth::id())->findOrFail($id);
        if ($pendaftaran->status === 'draft') {
            $pendaftaran->delete();
            session()->flash('success', 'Pendaftaran berhasil dihapus.');
        }
    }
}
