<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\UjianPenguji;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class JadwalMenguji extends Component
{
    public $jadwalAkanDatang;
    public $jadwalSelesai;
    public $selectedUjian = null;
    public $showDetail = false;
    public $tab = 'upcoming';
    public $isSuperAdmin = false;

    public function mount()
    {
        $this->isSuperAdmin = auth()->user() && auth()->user()->hasRole('super_admin');
        $this->loadData();
    }

    public function loadData()
    {
        $dosenId = auth()->id();

        // Jadwal akan datang
        $this->jadwalAkanDatang = UjianPenguji::when(!$this->isSuperAdmin, function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereHas('pendaftaran', function ($q) {
                $q->where('status', 'dijadwalkan')
                  ->where('tanggal_ujian', '>=', now());
            })
            ->with(['pendaftaran.mahasiswa', 'pendaftaran.bidangKeahlians', 'pendaftaran.pengujis.dosen'])
            ->get()
            ->sortBy(function ($jp) {
                return $jp->pendaftaran->tanggal_ujian;
            });

        // Riwayat menguji
        $this->jadwalSelesai = UjianPenguji::when(!$this->isSuperAdmin, function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereHas('pendaftaran', function ($q) {
                $q->where(function ($sq) {
                    $sq->where('status', 'selesai')
                       ->orWhere(function ($ssq) {
                           $ssq->where('status', 'dijadwalkan')
                               ->where('tanggal_ujian', '<', now());
                       });
                });
            })
            ->with(['pendaftaran.mahasiswa', 'pendaftaran.bidangKeahlians'])
            ->get()
            ->sortByDesc(function ($jp) {
                return $jp->pendaftaran->tanggal_ujian;
            });
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function showDetailUjian($id)
    {
        $this->selectedUjian = UjianPenguji::when(!$this->isSuperAdmin, function ($query) {
                $query->where('dosen_id', auth()->id());
            })
            ->where('pendaftaran_id', $id)
            ->with([
                'pendaftaran.mahasiswa',
                'pendaftaran.jurusan',
                'pendaftaran.prodi',
                'pendaftaran.bidangKeahlians',
                'pendaftaran.pengujis.dosen.kepakaran',
                'pendaftaran.pembimbing1.dosen.kepakaran',
                'pendaftaran.pembimbing2.dosen.kepakaran',
            ])
            ->first();

        if ($this->selectedUjian) {
            $this->showDetail = true;
        }
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedUjian = null;
    }

    public function render()
    {
        return view('livewire.dosen.jadwal-menguji', [
            'isSuperAdmin' => $this->isSuperAdmin,
        ])->layout('components.layouts.app-auth');
    }
}
