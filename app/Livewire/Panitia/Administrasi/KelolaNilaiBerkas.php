<?php

namespace App\Livewire\Panitia\Administrasi;

use App\Services\PermissionService;
use App\Models\Penilaian;
use App\Models\Pendaftaran;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaNilaiBerkas extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedPenilaianId = null;

    // --- VISIBILITY STATE ---
    public $showForm = false;
    public $showDetail = false;

    // --- PROPERTI UNTUK INPUT NILAI ---
    public $inputPenilaianId = null;
    public $penilaianToInput = null;

    // Komponen Nilai (Skala 0-100)
    public $presentasi = 0;
    public $penguasaan = 0;
    public $menjawab = 0;
    public $deskripsi = 0;
    public $analisis = 0;
    public $menyimpulkan = 0;
    public $implikasi = 0;

    // Hasil Perhitungan
    public $nilaiAkhir = 0;
    public $nilaiHuruf = '';
    public $predikat = '';
    public $catatanPanitia = '';
    public $showPreview = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openDetail($id)
    {
        $this->selectedPenilaianId = $id;
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->selectedPenilaianId = null;
        $this->showDetail = false;
    }

    // --- FITUR INPUT NILAI OLEH PANITIA ---

    public function openForm($id)
    {
        $this->inputPenilaianId = $id;
        $this->penilaianToInput = Penilaian::with(['pendaftaran.mahasiswa', 'dosen'])->findOrFail($id);

        // Muat nilai jika sebelumnya sudah pernah diinput/diverifikasi
        $this->presentasi = $this->penilaianToInput->presentasi ?? 0;
        $this->penguasaan = $this->penilaianToInput->penguasaan ?? 0;
        $this->menjawab = $this->penilaianToInput->menjawab ?? 0;
        $this->deskripsi = $this->penilaianToInput->deskripsi ?? 0;
        $this->analisis = $this->penilaianToInput->analisis ?? 0;
        $this->menyimpulkan = $this->penilaianToInput->menyimpulkan ?? 0;
        $this->implikasi = $this->penilaianToInput->implikasi ?? 0;
        $this->catatanPanitia = $this->penilaianToInput->catatan ?? '';

        if ($this->penilaianToInput->nilai_akhir) {
            $this->hitungNilai();
        } else {
            $this->showPreview = false;
        }

        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->inputPenilaianId = null;
        $this->penilaianToInput = null;
        $this->reset(['presentasi', 'penguasaan', 'menjawab', 'deskripsi', 'analisis', 'menyimpulkan', 'implikasi', 'nilaiAkhir', 'nilaiHuruf', 'predikat', 'catatanPanitia', 'showPreview']);
        $this->showForm = false;
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
            'presentasi' => (float)$this->presentasi,
            'penguasaan' => (float)$this->penguasaan,
            'menjawab' => (float)$this->menjawab,
            'deskripsi' => (float)$this->deskripsi,
            'analisis' => (float)$this->analisis,
            'menyimpulkan' => (float)$this->menyimpulkan,
            'implikasi' => (float)$this->implikasi,
        ];

        $this->nilaiAkhir = Penilaian::hitungNilaiAkhir($nilaiKomponen);
        $konversi = Penilaian::konversiNilai($this->nilaiAkhir);
        $this->nilaiHuruf = $konversi['huruf'];
        $this->predikat = $konversi['predikat'];
        $this->showPreview = true;
    }

    public function simpanNilai()
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

        $penilaian = Penilaian::findOrFail($this->inputPenilaianId);

        // Simpan nilai dan ubah status menjadi diverifikasi oleh panitia
        $penilaian->update([
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
            'catatan' => $this->catatanPanitia,
            'status' => 'diverifikasi', // Status berubah agar tahu sudah diinput
        ]);

        // Perbarui nilai total di tabel Pendaftaran jika semua penguji sudah dinilai
        $this->updateNilaiAkhirPendaftaran($penilaian->pendaftaran_id);

        session()->flash('success', 'Nilai mahasiswa berhasil diinput berdasarkan berkas dosen.');
        $this->closeForm();
    }

    private function updateNilaiAkhirPendaftaran($pendaftaranId)
    {
        // Hitung dari semua penilaian yang nilainya sudah masuk (baik tipe sistem maupun berkas yang sudah diinput)
        $penilaians = Penilaian::where('pendaftaran_id', $pendaftaranId)
            ->whereNotNull('nilai_akhir')
            ->get();

        if ($penilaians->count() >= 2) {
            $rataRata = $penilaians->avg('nilai_akhir');
            $konversi = Penilaian::konversiNilai($rataRata);

            Pendaftaran::where('id', $pendaftaranId)->update([
                'nilai_total' => round($rataRata, 2),
                'grade' => $konversi['huruf'],
            ]);
        }
    }

    public function render()
    {
        $penilaians = Penilaian::with(['pendaftaran.mahasiswa', 'dosen'])
            ->where('tipe_input', 'berkas')
            ->whereHas('pendaftaran', fn ($q) => $q->where(PermissionService::jurusanScope()))
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('pendaftaran.mahasiswa', fn ($mq) => $mq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nim', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('dosen', fn ($dq) => $dq->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest('submitted_at')
            ->paginate(10);

        $selectedPenilaian = $this->selectedPenilaianId
            ? Penilaian::with(['pendaftaran.mahasiswa', 'dosen'])->find($this->selectedPenilaianId)
            : null;

        return view('livewire.panitia.administrasi.kelola-nilai-berkas', [
            'penilaians' => $penilaians,
            'selectedPenilaian' => $selectedPenilaian,
        ])->layout('components.layouts.app-auth');
    }
}
