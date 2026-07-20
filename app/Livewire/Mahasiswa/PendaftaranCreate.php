<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\BidangKeahlian;
use App\Livewire\Forms\PendaftaranForm;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;

class PendaftaranCreate extends Component
{
    use WithFileUploads;

    public PendaftaranForm $form;

    public $editMode = false;
    public $pendaftaranId;

    // Auto-fill dari user login atau mahasiswa terpilih
    public $nama_mahasiswa;
    public $nim;
    public $nomor_hp;
    public $email;
    public $jurusan;
    public $jurusan_id;

    public $listBidangKeahlian = [];

    // Properties untuk duplicate check
    public $hasExistingRegistration = false;
    public $existingRegistrationType = '';
    public $existingRegistrationStatus = '';
    public $existingRegistrationId = null;

    // Fitur Tambahan untuk Super Admin
    public $isSuperAdmin = false;
    public $selectedMahasiswaId = '';
    public $listMahasiswa = [];

    public function mount($pendaftaran = null)
    {
        $user = Auth::user();
        $this->isSuperAdmin = $user->hasRole('super_admin'); // Deteksi role Super Admin

        if ($this->isSuperAdmin) {
            // Sediakan daftar semua mahasiswa yang aktif untuk dipilih oleh Super Admin
            $this->listMahasiswa = User::role('mahasiswa')->active()->get();
        }

        if ($pendaftaran) {
            $this->editMode = true;
            $this->pendaftaranId = $pendaftaran;
            $p = Pendaftaran::findOrFail($pendaftaran);
            $this->form->setPendaftaran($p);

            // Load data mahasiswa dari pendaftaran existing
            $mahasiswa = $p->mahasiswa;
            if ($mahasiswa) {
                $this->selectedMahasiswaId = $mahasiswa->id;
                $this->nama_mahasiswa = $mahasiswa->name;
                $this->nim = $mahasiswa->nim;
                $this->nomor_hp = $mahasiswa->nomor_hp;
                $this->email = $mahasiswa->email;
                $this->jurusan = $mahasiswa->jurusan?->nama_jurusan;
                $this->jurusan_id = $p->jurusan_id;
            }

            $this->hasExistingRegistration = false;
        } else {
            if (!$this->isSuperAdmin) {
                // Jika user biasa (mahasiswa), jalankan pengisian otomatis seperti biasa
                $this->setMahasiswaData($user);
            }
        }

        // Jalankan loading bidang keahlian (menggunakan fallback global jika belum ada jurusan terpilih)
        $this->loadBidangKeahlian();
    }

    public function updatedSelectedMahasiswaId($value)
    {
        if ($value) {
            $student = User::findOrFail($value);
            $this->setMahasiswaData($student);
        } else {
            $this->resetMahasiswaData();
        }

        $this->loadBidangKeahlian();
    }

    private function setMahasiswaData($user)
    {
        $this->nama_mahasiswa = $user->name;
        $this->nim = $user->nim;
        $this->nomor_hp = $user->nomor_hp;
        $this->email = $user->email;
        $this->jurusan = $user->jurusan?->nama_jurusan;
        $this->jurusan_id = $user->jurusan_id;

        // Pasang data ke Form Object secara dinamis
        $this->form->mahasiswa_id = $user->id;
        $this->form->jurusan_id = $this->jurusan_id;
        $this->form->prodi_id = $user->prodi_id;

        $this->checkExistingRegistration($user->id);

        if (!$this->editMode) {
            $proposal = Pendaftaran::where('mahasiswa_id', $user->id)
                ->where('jenis_ujian', 'seminar_proposal')
                ->latest()
                ->first();

            if ($proposal) {
                $this->form->judul_penelitian = $proposal->judul_penelitian;
                $this->form->abstrak = $proposal->abstrak;
                $this->form->selectedBidangKeahlian = $proposal->bidangKeahlians->pluck('id')->toArray();
                $this->form->dosen_pembimbing_1 = $proposal->dosens()->where('peran', 'pembimbing_1')->first()?->dosen_id ?? '';
                $this->form->dosen_pembimbing_2 = $proposal->dosens()->where('peran', 'pembimbing_2')->first()?->dosen_id ?? '';
            }
        }
    }

    private function resetMahasiswaData()
    {
        $this->nama_mahasiswa = null;
        $this->nim = null;
        $this->nomor_hp = null;
        $this->email = null;
        $this->jurusan = null;
        $this->jurusan_id = null;

        $this->form->mahasiswa_id = null;
        $this->form->jurusan_id = null;
        $this->form->prodi_id = null;

        $this->hasExistingRegistration = false;
    }

    public function checkExistingRegistration($mahasiswaId = null)
    {
        $targetId = $mahasiswaId ?? Auth::id();
        $activeStatuses = ['pending', 'disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan', 'revisi'];
        $jenisUjianList = ['seminar_proposal', 'seminar_hasil', 'sidang_skripsi'];

        $this->hasExistingRegistration = false;

        foreach ($jenisUjianList as $jenis) {
            $existing = Pendaftaran::where('mahasiswa_id', $targetId)
                ->where('jenis_ujian', $jenis)
                ->whereIn('status', $activeStatuses)
                ->first();

            if ($existing) {
                $this->hasExistingRegistration = true;
                $this->existingRegistrationType = $this->getJenisUjianLabel($jenis);
                $this->existingRegistrationStatus = $this->getStatusLabel($existing->status);
                $this->existingRegistrationId = $existing->id;
                $this->form->jenis_ujian = $jenis;
                break;
            }
        }
    }

    public function updatedFormJenisUjian($value)
    {
        if ($value && !$this->editMode) {
            $targetId = $this->form->mahasiswa_id ?? Auth::id();
            $existingCompleted = Pendaftaran::where('mahasiswa_id', $targetId)
                ->where('jenis_ujian', $value)
                ->where('status', 'selesai')
                ->exists();

            if ($existingCompleted) {
                $this->form->jenis_ujian = null;
                session()->flash('error', "Mahasiswa sudah pernah menyelesaikan ujian jenis {$this->getJenisUjianLabel($value)}.");
            }
        }
    }

    public function getJenisUjianLabel($jenis)
    {
        $labels = [
            'seminar_proposal' => 'Seminar Proposal',
            'seminar_hasil' => 'Seminar Hasil',
            'sidang_skripsi' => 'Sidang Skripsi',
        ];
        return $labels[$jenis] ?? $jenis;
    }

    public function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'pending' => 'Menunggu Verifikasi',
            'disetujui_panitia' => 'Disetujui Panitia',
            'disetujui_sekjur' => 'Disetujui Sekjur',
            'disetujui_kajur' => 'Disetujui Kajur',
            'dijadwalkan' => 'Dijadwalkan',
            'revisi' => 'Revisi',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan',
        ];
        return $labels[$status] ?? $status;
    }

    public function loadBidangKeahlian()
    {
        $query = BidangKeahlian::active();

        // JANGAN mengunci ke Auth::user()->jurusan_id secara mutlak jika diakses Super Admin
        if (!$this->isSuperAdmin || $this->jurusan_id) {
            $query->byJurusan($this->jurusan_id);
        }

        $this->listBidangKeahlian = $query->orderBy('nama_bidang')->get();
    }

    public function save()
    {
        if ($this->hasExistingRegistration && !$this->editMode) {
            session()->flash('error', 'Tidak dapat memproses pendaftaran karena terdapat pendaftaran aktif.');
            return redirect()->route('mahasiswa.pendaftaran.index');
        }

        $this->form->save();

        session()->flash('success', $this->editMode ? 'Pendaftaran berhasil diperbarui.' : 'Pendaftaran berhasil disimpan.');
        return redirect()->route('mahasiswa.pendaftaran.index');
    }

    public function render()
    {
        $dosensQuery = User::role('dosen')->active();

        // Scope dosen disesuaikan jika jurusan_id mahasiswa telah terdeteksi
        if (!$this->isSuperAdmin || $this->jurusan_id) {
            $dosensQuery->where('jurusan_id', $this->jurusan_id);
        }

        $dosens = $dosensQuery->get();

        return view('livewire.mahasiswa.pendaftaran-create', [
            'dosens' => $dosens,
            'listBidangKeahlian' => $this->listBidangKeahlian,
        ])->layout('components.layouts.app-auth');
    }
}
