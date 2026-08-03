<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\UjianPenguji;

class InputNilaiSistem extends Component
{
    use WithFileUploads;

    public $pendaftaran;
    public $pendaftaranId;
    public $peranDosen;
    public $existingPenilaian;
    public $editMode = false;

    // Komponen Nilai (0-100)
    public $presentasi = 0;
    public $penguasaan = 0;
    public $menjawab = 0;
    public $deskripsi = 0;
    public $analisis = 0;
    public $menyimpulkan = 0;
    public $implikasi = 0;

    // Hasil
    public $nilaiAkhir = 0;
    public $nilaiHuruf = '';
    public $predikat = '';

    // Catatan
    public $catatan = '';

    public $showPreview = false;

    public function mount($pendaftaran)
    {
        if ($pendaftaran instanceof Pendaftaran) {
            $this->pendaftaran = $pendaftaran;
        } else {
            $this->pendaftaran = Pendaftaran::with(['mahasiswa', 'pengujis'])->findOrFail($pendaftaran);
        }

        $this->pendaftaranId = $this->pendaftaran->id;

        if (!in_array($this->pendaftaran->status, ['dijadwalkan', 'selesai'])) {
            session()->flash('error', 'Ujian ini belum bisa dinilai. Status: ' . $this->pendaftaran->statusLabel);
            $this->redirect(route('dosen.nilai.index'));
            return;
        }

        $isSuperAdmin = auth()->user() && auth()->user()->hasRole('super_admin');

        $peran = UjianPenguji::where('pendaftaran_id', $this->pendaftaran->id)
            ->when(!$isSuperAdmin, function ($query) {
                $query->where('dosen_id', auth()->id());
            })
            ->first();

        if (!$peran && !$isSuperAdmin) {
            session()->flash('error', 'Anda tidak terdaftar sebagai penguji untuk ujian ini.');
            $this->redirect(route('dosen.nilai.index'));
            return;
        }

        $this->peranDosen = $peran->peran ?? 'penguji_1';
        $targetDosenId = $peran ? $peran->dosen_id : auth()->id();

        $this->existingPenilaian = Penilaian::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', $targetDosenId)
            ->first();

        if ($this->existingPenilaian) {
            $this->editMode = true;
            $this->presentasi = $this->existingPenilaian->presentasi ?? 0;
            $this->penguasaan = $this->existingPenilaian->penguasaan ?? 0;
            $this->menjawab = $this->existingPenilaian->menjawab ?? 0;
            $this->deskripsi = $this->existingPenilaian->deskripsi ?? 0;
            $this->analisis = $this->existingPenilaian->analisis ?? 0;
            $this->menyimpulkan = $this->existingPenilaian->menyimpulkan ?? 0;
            $this->implikasi = $this->existingPenilaian->implikasi ?? 0;
            $this->catatan = $this->existingPenilaian->catatan ?? '';
            $this->hitungNilai();
        }
    }

    public function updated($field)
    {
        $komponenNilai = ['presentasi', 'penguasaan', 'menjawab', 'deskripsi', 'analisis', 'menyimpulkan', 'implikasi'];

        if (in_array($field, $komponenNilai)) {
            // PERBAIKAN: Jika input sedang dikosongkan saat mengedit, biarkan sementara agar tidak tereset ke 0
            if ($this->$field === '' || $this->$field === null) {
                return;
            }

            // Pastikan dikonversi ke numerik
            $val = (float) $this->$field;

            if ($val < 0) $val = 0;
            if ($val > 100) $val = 100;

            $this->$field = $val;
            $this->hitungNilai();
        }
    }

    public function hitungNilai()
    {
        $nilaiKomponen = [
            'presentasi' => (float) ($this->presentasi ?: 0),
            'penguasaan' => (float) ($this->penguasaan ?: 0),
            'menjawab' => (float) ($this->menjawab ?: 0),
            'deskripsi' => (float) ($this->deskripsi ?: 0),
            'analisis' => (float) ($this->analisis ?: 0),
            'menyimpulkan' => (float) ($this->menyimpulkan ?: 0),
            'implikasi' => (float) ($this->implikasi ?: 0),
        ];

        $this->nilaiAkhir = Penilaian::hitungNilaiAkhir($nilaiKomponen);
        $konversi = Penilaian::konversiNilai($this->nilaiAkhir);
        $this->nilaiHuruf = $konversi['huruf'];
        $this->predikat = $konversi['predikat'];
        $this->showPreview = true;
    }

    public function save()
    {
        $this->validate([
            'presentasi' => 'required|numeric|min:0|max:100',
            'penguasaan' => 'required|numeric|min:0|max:100',
            'menjawab' => 'required|numeric|min:0|max:100',
            'deskripsi' => 'required|numeric|min:0|max:100',
            'analisis' => 'required|numeric|min:0|max:100',
            'menyimpulkan' => 'required|numeric|min:0|max:100',
            'implikasi' => 'required|numeric|min:0|max:100',
        ]);

        $this->hitungNilai();

        $isSuperAdmin = auth()->user() && auth()->user()->hasRole('super_admin');
        $peran = UjianPenguji::where('pendaftaran_id', $this->pendaftaran->id)
            ->when(!$isSuperAdmin, function ($query) {
                $query->where('dosen_id', auth()->id());
            })
            ->first();
        $targetDosenId = $peran ? $peran->dosen_id : auth()->id();

        $data = [
            'pendaftaran_id' => $this->pendaftaran->id,
            'dosen_id' => $targetDosenId,
            'peran_pemberi' => $this->peranDosen,
            'tipe_input' => 'sistem',
            'presentasi' => $this->presentasi,
            'penguasaan' => $this->penguasaan,
            'menjawab' => $this->menjawab,
            'deskripsi' => $this->deskripsi,
            'analisis' => $this->analisis,
            'menyimpulkan' => $this->menyimpulkan,
            'implikasi' => $this->implikasi,
            'nilai_akhir' => $this->nilaiAkhir,
            'nilai_huruf' => $this->nilaiHuruf,
            'predikat' => $this->predikat,
            'catatan' => $this->catatan,
            'status' => 'selesai',
            'submitted_at' => now(),
        ];

        if ($this->editMode && $this->existingPenilaian) {
            $this->existingPenilaian->update($data);
            session()->flash('success', 'Nilai berhasil diperbarui.');
        } else {
            Penilaian::create($data);
            session()->flash('success', 'Nilai berhasil disimpan.');
        }

        $this->updateNilaiAkhirPendaftaran();

        return redirect()->route('dosen.nilai.index');
    }

    private function updateNilaiAkhirPendaftaran()
    {
        $penilaians = Penilaian::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('tipe_input', 'sistem')
            ->whereNotNull('nilai_akhir')
            ->get();

        if ($penilaians->count() >= 2) {
            $rataRata = $penilaians->avg('nilai_akhir');
            $konversi = Penilaian::konversiNilai($rataRata);

            $this->pendaftaran->update([
                'nilai_total' => round($rataRata, 2),
                'grade' => $konversi['huruf'],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dosen.input-nilai-sistem')->layout('components.layouts.app-auth');
    }
}
