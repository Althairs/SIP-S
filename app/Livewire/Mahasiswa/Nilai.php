<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\UjianPenguji;

class Nilai extends Component
{
    public $nilais;
    public $rataRata;
    public $totalUjian;
    public $selectedUjian = null;
    public $showDetail = false;
    public $detailPenilaian = [];

    public function mount()
    {
        $userId = auth()->id();

        $this->nilais = Pendaftaran::with(['dosens.dosen', 'pengujis.dosen'])
            ->where('mahasiswa_id', $userId)
            ->whereNotNull('nilai_total')
            ->latest()
            ->get();

        $this->totalUjian = $this->nilais->count();
        $this->rataRata = $this->nilais->avg('nilai_total');
    }

    public function showDetailNilai($id)
    {
        $this->selectedUjian = Pendaftaran::with([
            'dosens.dosen',
            'jurusan',
            'pengujis.dosen',
            'pembimbing1.dosen',
            'pembimbing2.dosen',
        ])->where('mahasiswa_id', auth()->id())->findOrFail($id);

        // Ambil penilaian dari masing-masing penguji
        $this->detailPenilaian = [];

        // Penilaian Penguji 1
        $penilaianPenguji1 = Penilaian::where('pendaftaran_id', $id)
            ->where('peran_pemberi', 'penguji_1')
            ->where('tipe_input', 'sistem')
            ->first();

        if ($penilaianPenguji1) {
            $this->detailPenilaian['penguji_1'] = [
                'nama' => $this->selectedUjian->pengujis->where('peran', 'penguji_1')->first()?->dosen?->name ?? 'Penguji 1',
                'nip' => $this->selectedUjian->pengujis->where('peran', 'penguji_1')->first()?->dosen?->nip ?? '-',
                'tipe' => 'sistem',
                'detail' => $penilaianPenguji1->toArray(),
                'aspek' => [
                    'presentasi' => $penilaianPenguji1->presentasi,
                    'penguasaan' => $penilaianPenguji1->penguasaan,
                    'menjawab' => $penilaianPenguji1->menjawab,
                    'deskripsi' => $penilaianPenguji1->deskripsi,
                    'analisis' => $penilaianPenguji1->analisis,
                    'menyimpulkan' => $penilaianPenguji1->menyimpulkan,
                    'implikasi' => $penilaianPenguji1->implikasi,
                ],
            ];
        }

        // Penilaian Penguji 2
        $penilaianPenguji2 = Penilaian::where('pendaftaran_id', $id)
            ->where('peran_pemberi', 'penguji_2')
            ->where('tipe_input', 'sistem')
            ->first();

        if ($penilaianPenguji2) {
            $this->detailPenilaian['penguji_2'] = [
                'nama' => $this->selectedUjian->pengujis->where('peran', 'penguji_2')->first()?->dosen?->name ?? 'Penguji 2',
                'nip' => $this->selectedUjian->pengujis->where('peran', 'penguji_2')->first()?->dosen?->nip ?? '-',
                'tipe' => 'sistem',
                'detail' => $penilaianPenguji2->toArray(),
                'aspek' => [
                    'presentasi' => $penilaianPenguji2->presentasi,
                    'penguasaan' => $penilaianPenguji2->penguasaan,
                    'menjawab' => $penilaianPenguji2->menjawab,
                    'deskripsi' => $penilaianPenguji2->deskripsi,
                    'analisis' => $penilaianPenguji2->analisis,
                    'menyimpulkan' => $penilaianPenguji2->menyimpulkan,
                    'implikasi' => $penilaianPenguji2->implikasi,
                ],
            ];
        }

        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedUjian = null;
        $this->detailPenilaian = [];
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
