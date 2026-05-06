<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\BidangKeahlian;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PendaftaranCreate extends Component
{
    use WithFileUploads;

    public $editMode = false;
    public $pendaftaranId;

    // Auto-fill dari user login
    public $nama_mahasiswa;
    public $nim;
    public $nomor_hp;
    public $email;
    public $jurusan;
    public $jurusan_id;

    // Form inputs
    public $jenis_ujian = '';
    public $judul_penelitian = '';
    public $abstrak = '';
    public $selectedBidangKeahlian = [];  // GANTI dari string ke array
    public $dosen_pembimbing_1 = '';
    public $dosen_pembimbing_2 = '';

    // File uploads
    public $file_proposal;
    public $file_skripsi;
    public $file_persetujuan;
    public $file_krs;
    public $file_transkrip;
    public $file_bukti_bimbingan;

    public $existingFiles = [];
    public $listBidangKeahlian = [];  // Daftar bidang keahlian dari DB

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

        if ($pendaftaran) {
            $this->editMode = true;
            $this->pendaftaranId = $pendaftaran;
            $this->loadData($pendaftaran);
        }
    }

    public function loadBidangKeahlian()
    {
        $this->listBidangKeahlian = BidangKeahlian::active()
            ->byJurusan($this->jurusan_id)
            ->orderBy('nama_bidang')
            ->get();
    }

    public function loadData($id)
    {
        $p = Pendaftaran::with(['dosens', 'bidangKeahlians'])->findOrFail($id);
        $this->jenis_ujian = $p->jenis_ujian;
        $this->judul_penelitian = $p->judul_penelitian;
        $this->abstrak = $p->abstrak;

        // Load selected bidang keahlian
        $this->selectedBidangKeahlian = $p->bidangKeahlians->pluck('id')->toArray();

        $this->existingFiles = [
            'file_proposal' => $p->file_proposal,
            'file_skripsi' => $p->file_skripsi,
            'file_persetujuan' => $p->file_persetujuan,
            'file_krs' => $p->file_krs,
            'file_transkrip' => $p->file_transkrip,
            'file_bukti_bimbingan' => $p->file_bukti_bimbingan,
        ];

        $this->dosen_pembimbing_1 = $p->dosens()->where('peran', 'pembimbing_1')->first()?->dosen_id;
        $this->dosen_pembimbing_2 = $p->dosens()->where('peran', 'pembimbing_2')->first()?->dosen_id;
    }

    protected function rules()
    {
        $rules = [
            'jenis_ujian' => 'required|in:seminar_proposal,seminar_hasil,sidang_skripsi',
            'judul_penelitian' => 'required|string|max:255',
            'abstrak' => 'nullable|string',
            'selectedBidangKeahlian' => 'required|array|min:1',  // GANTI validasi
            'selectedBidangKeahlian.*' => 'exists:bidang_keahlians,id',
            'dosen_pembimbing_1' => 'required|exists:users,id',
            'dosen_pembimbing_2' => 'nullable|exists:users,id|different:dosen_pembimbing_1',
        ];

        // File validation
        if (!$this->editMode || $this->file_proposal) {
            $rules['file_proposal'] = 'nullable|file|mimes:pdf|max:10240';
        }
        if ($this->jenis_ujian !== 'seminar_proposal') {
            $rules['file_skripsi'] = 'nullable|file|mimes:pdf|max:20480';
        }

        return $rules;
    }

    protected $messages = [
        'judul_penelitian.required' => 'Judul penelitian wajib diisi.',
        'selectedBidangKeahlian.required' => 'Pilih minimal satu bidang keahlian.',
        'selectedBidangKeahlian.min' => 'Pilih minimal satu bidang keahlian.',
        'dosen_pembimbing_1.required' => 'Dosen pembimbing 1 wajib dipilih.',
        'dosen_pembimbing_2.different' => 'Dosen pembimbing 2 harus berbeda.',
    ];

    public function save()
    {
        $validated = $this->validate();

        $data = [
            'mahasiswa_id' => auth()->id(),
            'jurusan_id' => auth()->user()->jurusan_id,
            'prodi_id' => auth()->user()->prodi_id,
            'jenis_ujian' => $this->jenis_ujian,
            'judul_penelitian' => $this->judul_penelitian,
            'abstrak' => $this->abstrak,
            'status' => 'pending',
        ];

        // Handle file uploads
        $fileFields = ['file_proposal', 'file_skripsi', 'file_persetujuan', 'file_krs', 'file_transkrip', 'file_bukti_bimbingan'];
        foreach ($fileFields as $field) {
            if ($this->$field) {
                if ($this->editMode && !empty($this->existingFiles[$field])) {
                    Storage::disk('public')->delete($this->existingFiles[$field]);
                }
                $data[$field] = $this->$field->store('pendaftaran/' . auth()->id(), 'public');
            } elseif (!$this->editMode) {
                $data[$field] = null;
            }
        }

        if ($this->editMode) {
            $pendaftaran = Pendaftaran::findOrFail($this->pendaftaranId);

            // JANGAN update first_registered_at saat edit
            $pendaftaran->update($data);
            $pendaftaran->dosens()->delete();
            $this->saveDosens($pendaftaran);
            $pendaftaran->bidangKeahlians()->sync($this->selectedBidangKeahlian);

            session()->flash('success', 'Pendaftaran berhasil diperbarui.');
        } else {
            // Set first_registered_at = created_at untuk pendaftaran baru
            $data['first_registered_at'] = now();
            $pendaftaran = Pendaftaran::create($data);
            $this->saveDosens($pendaftaran);
            $pendaftaran->bidangKeahlians()->attach($this->selectedBidangKeahlian);

            session()->flash('success', 'Pendaftaran berhasil disimpan.');
        }

        return redirect()->route('mahasiswa.pendaftaran.index');
    }

    private function saveDosens($pendaftaran)
    {
        if ($this->dosen_pembimbing_1) {
            $pendaftaran->dosens()->create([
                'dosen_id' => $this->dosen_pembimbing_1,
                'peran' => 'pembimbing_1',
            ]);
        }
        if ($this->dosen_pembimbing_2) {
            $pendaftaran->dosens()->create([
                'dosen_id' => $this->dosen_pembimbing_2,
                'peran' => 'pembimbing_2',
            ]);
        }
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
