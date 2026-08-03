<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\UjianPenguji;
use App\Models\Penilaian;

class BerikanNilai extends Component
{
    public $showDetail = false;
    public $selectedPendaftaran = null;
    public $isSuperAdmin = false;

    public function mount()
    {
        $this->isSuperAdmin = auth()->user() && auth()->user()->hasRole('super_admin');
    }

    public function showDetail($id)
    {
        $this->selectedPendaftaran = \App\Models\Pendaftaran::with([
            'mahasiswa',
            'mahasiswa.jurusan',
            'mahasiswa.prodi',
            'bidangKeahlians',
            'dosens.dosen',
            'pembimbing1.dosen',
            'pembimbing2.dosen',
            'pengujis.dosen',
            'jurusan',
            'prodi',
        ])->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedPendaftaran = null;
    }

    public function render()
    {
        $dosenId = auth()->id();

        $ujianSaya = UjianPenguji::when(!$this->isSuperAdmin, function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->with(['pendaftaran.mahasiswa', 'pendaftaran' => function($q) {
                $q->whereIn('status', ['dijadwalkan', 'selesai']);
            }])
            ->get()
            ->filter(fn($jp) => $jp->pendaftaran !== null);

        // Ambil penilaian yang sudah dibuat
        $penilaianSaya = Penilaian::when(!$this->isSuperAdmin, function ($query) use ($dosenId) {
                $query->byDosen($dosenId);
            })
            ->get()
            ->keyBy('pendaftaran_id');

        return view('livewire.dosen.berikan-nilai', [
            'ujianSaya' => $ujianSaya,
            'penilaianSaya' => $penilaianSaya,
            'isSuperAdmin' => $this->isSuperAdmin,
        ])->layout('components.layouts.app-auth');
    }
}
