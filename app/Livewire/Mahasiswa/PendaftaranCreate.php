<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\BidangKeahlian;
use App\Livewire\Forms\PendaftaranForm;
use Illuminate\Support\Facades\Auth;

class PendaftaranCreate extends Component
{
    use WithFileUploads;

    public PendaftaranForm $form;

    public $editMode = false;
    public $pendaftaranId;

    // Auto-fill dari user login
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

    public function mount($pendaftaran = null)
    {
        $user = Auth::user();
        $this->nama_mahasiswa = $user->name;
        $this->nim = $user->nim;
        $this->nomor_hp = $user->nomor_hp;
        $this->email = $user->email;
        $this->jurusan = $user->jurusan?->nama_jurusan;
        $this->jurusan_id = $user->jurusan_id;

        // Load bidang keahlian berdasarkan jurusan
        $this->loadBidangKeahlian();

        // Cek existing registration untuk jenis ujian yang akan dipilih
        $this->checkExistingRegistration();

        if ($pendaftaran) {
            $this->editMode = true;
            $this->pendaftaranId = $pendaftaran;
            $p = Pendaftaran::findOrFail($pendaftaran);
            $this->form->setPendaftaran($p);

            // Reset duplicate check untuk edit mode
            $this->hasExistingRegistration = false;
        } else {
            // Jika mahasiswa sudah pernah mendaftar proposal, gunakan data proposal sebagai autofill
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

    public function checkExistingRegistration()
    {
        $user = Auth::user();

        // Status yang dianggap aktif (tidak boleh mendaftar ulang)
        $activeStatuses = ['pending', 'disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan', 'revisi'];

        // Cek apakah ada pendaftaran aktif untuk jenis ujian tertentu
        // Kita cek untuk semua jenis ujian yang mungkin
        $jenisUjianList = ['seminar_proposal', 'seminar_hasil', 'sidang_skripsi'];

        foreach ($jenisUjianList as $jenis) {
            $existing = Pendaftaran::where('mahasiswa_id', $user->id)
                ->where('jenis_ujian', $jenis)
                ->whereIn('status', $activeStatuses)
                ->first();

            if ($existing) {
                $this->hasExistingRegistration = true;
                $this->existingRegistrationType = $this->getJenisUjianLabel($jenis);
                $this->existingRegistrationStatus = $this->getStatusLabel($existing->status);
                $this->existingRegistrationId = $existing->id;

                // Set jenis ujian yang sudah ada sebagai default untuk disable
                $this->form->jenis_ujian = $jenis;

                break;
            }
        }
    }

    public function updatedFormJenisUjian($value)
    {
        // Cek apakah mahasiswa sudah pernah menyelesaikan jenis ujian yang sama
        if ($value && !$this->editMode) {
            $user = Auth::user();
            $existingCompleted = Pendaftaran::where('mahasiswa_id', $user->id)
                ->where('jenis_ujian', $value)
                ->where('status', 'selesai')
                ->exists();

            if ($existingCompleted) {
                $this->form->jenis_ujian = null;
                session()->flash('error', "Anda sudah pernah mendaftar dan menyelesaikan ujian jenis {$this->getJenisUjianLabel($value)}. Silakan memilih jenis ujian yang berbeda.");
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
        $this->listBidangKeahlian = BidangKeahlian::active()
            ->byJurusan($this->jurusan_id)
            ->orderBy('nama_bidang')
            ->get();
    }

    public function save()
    {
        // Jika ada existing registration, tolak penyimpanan
        if ($this->hasExistingRegistration && !$this->editMode) {
            session()->flash('error', 'Anda tidak dapat mendaftar karena memiliki pendaftaran aktif.');
            return redirect()->route('mahasiswa.pendaftaran.index');
        }

        $this->form->save();

        if ($this->editMode) {
            session()->flash('success', 'Pendaftaran berhasil diperbarui.');
        } else {
            session()->flash('success', 'Pendaftaran berhasil disimpan.');
        }

        return redirect()->route('mahasiswa.pendaftaran.index');
    }

    public function render()
    {
        $jurusanId = Auth::user()->jurusan_id;
        $dosens = User::role('dosen')->where('jurusan_id', $jurusanId)->active()->get();

        return view('livewire.mahasiswa.pendaftaran-create', [
            'dosens' => $dosens,
            'listBidangKeahlian' => $this->listBidangKeahlian,
        ])->layout('components.layouts.app-auth');
    }
}
