<?php

namespace App\Livewire\Panitia\Administrasi;

use App\Models\Prodi;
use Livewire\Component;

class Laporan extends Component
{
    public $prodiFilter = '';

    public $jenisUjianFilter = '';

    public $bulanFilter = '';

    public $tahunFilter = '';

    public function mount(): void
    {
        $this->tahunFilter = (string) now()->year;
        $this->bulanFilter = (string) now()->month;
    }

    public function downloadUrl(string $jenis): string
    {
        $params = array_filter([
            'prodi_id' => $this->prodiFilter ?: null,
            'jenis_ujian' => $this->jenisUjianFilter ?: null,
            'bulan' => $this->bulanFilter ?: null,
            'tahun' => $this->tahunFilter ?: null,
        ]);

        return route('panitia.administrasi.laporan.download', array_merge(['jenis' => $jenis], $params));
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $prodis = Prodi::where('jurusan_id', $jurusanId)->active()->get();

        $laporans = [
            [
                'jenis' => 'pendaftaran',
                'judul' => 'Laporan Pendaftaran Mahasiswa',
                'deskripsi' => 'Rekap seluruh pendaftaran ujian mahasiswa per jurusan.',
            ],
            [
                'jenis' => 'kuota-dosen',
                'judul' => 'Laporan Kuota Dosen',
                'deskripsi' => 'Rekap kuota pembimbing dan penguji setiap dosen.',
            ],
            [
                'jenis' => 'nilai',
                'judul' => 'Laporan Nilai Mahasiswa',
                'deskripsi' => 'Rekap nilai akhir mahasiswa yang telah selesai ujian.',
            ],
            [
                'jenis' => 'ujian-selesai',
                'judul' => 'Laporan Ujian Selesai',
                'deskripsi' => 'Mahasiswa yang sudah menyelesaikan ujian proposal, hasil, dan skripsi.',
            ],
        ];

        return view('livewire.panitia.administrasi.laporan', [
            'prodis' => $prodis,
            'laporans' => $laporans,
        ])->layout('components.layouts.app-auth');
    }
}
