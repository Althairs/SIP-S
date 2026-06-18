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

    public $listBidangKeahlian = [];  // Daftar bidang keahlian dari DB

    public function mount($pendaftaran = null)
    {
        $this->form = new PendaftaranForm();

        $user = Auth::user();
        $this->nama_mahasiswa = $user->name;
        $this->nim = $user->nim;
        $this->nomor_hp = $user->nomor_hp;
        $this->email = $user->email;
        $this->jurusan = $user->jurusan?->nama_jurusan;
        $this->jurusan_id = $user->jurusan_id;

        // Load bidang keahlian berdasarkan jurusan
        $this->loadBidangKeahlian();

        if ($pendaftaran) {
            $this->editMode = true;
            $this->pendaftaranId = $pendaftaran;
            $p = Pendaftaran::findOrFail($pendaftaran);
            $this->form->setPendaftaran($p);
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

    public function loadBidangKeahlian()
    {
        $this->listBidangKeahlian = BidangKeahlian::active()
            ->byJurusan($this->jurusan_id)
            ->orderBy('nama_bidang')
            ->get();
    }

    public function save()
    {
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
