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

    // HAPUS type hint Pendaftaran, gunakan public property biasa
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
        // Jika $pendaftaran adalah model, gunakan langsung
        // Jika $pendaftaran adalah ID, cari modelnya
        if ($pendaftaran instanceof Pendaftaran) {
            $this->pendaftaran = $pendaftaran;
        } else {
            $this->pendaftaran = Pendaftaran::with(['mahasiswa', 'pengujis'])->findOrFail($pendaftaran);
        }

        $this->pendaftaranId = $this->pendaftaran->id;

        // Cek status
        if (!in_array($this->pendaftaran->status, ['dijadwalkan', 'selesai'])) {
            session()->flash('error', 'Ujian ini belum bisa dinilai. Status: ' . $this->pendaftaran->statusLabel);
            $this->redirect(route('dosen.nilai.index'));
            return;
        }

        // Cari peran dosen
        $peran = UjianPenguji::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->first();

        if (!$peran) {
            session()->flash('error', 'Anda tidak terdaftar sebagai penguji untuk ujian ini.');
            $this->redirect(route('dosen.nilai.index'));
            return;
        }

        $this->peranDosen = $peran->peran ?? 'penguji_1';

        // Cek existing penilaian
        $this->existingPenilaian = Penilaian::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
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
            if ($this->$field < 0) $this->$field = 0;
            if ($this->$field > 100) $this->$field = 100;
            $this->hitungNilai();
        }
    }

    public function hitungNilai()
    {
        $nilaiKomponen = [
            'presentasi' => $this->presentasi,
            'penguasaan' => $this->penguasaan,
            'menjawab' => $this->menjawab,
            'deskripsi' => $this->deskripsi,
            'analisis' => $this->analisis,
            'menyimpulkan' => $this->menyimpulkan,
            'implikasi' => $this->implikasi,
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

        $data = [
            'pendaftaran_id' => $this->pendaftaran->id,
            'dosen_id' => auth()->id(),
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

        // Update nilai_total di pendaftaran jika kedua penguji sudah input
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
