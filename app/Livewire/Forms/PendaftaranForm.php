<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PendaftaranForm extends Form
{
    public ?Pendaftaran $pendaftaran = null;

    public $jenis_ujian = '';
    public $judul_penelitian = '';
    public $abstrak = '';
    public $selectedBidangKeahlian = [];
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

    public function setPendaftaran(Pendaftaran $pendaftaran)
    {
        $this->pendaftaran = $pendaftaran;

        $this->jenis_ujian = $pendaftaran->jenis_ujian;
        $this->judul_penelitian = $pendaftaran->judul_penelitian;
        $this->abstrak = $pendaftaran->abstrak;
        $this->selectedBidangKeahlian = $pendaftaran->bidangKeahlians->pluck('id')->toArray();

        $this->existingFiles = [
            'file_proposal' => $pendaftaran->file_proposal,
            'file_skripsi' => $pendaftaran->file_skripsi,
            'file_persetujuan' => $pendaftaran->file_persetujuan,
            'file_krs' => $pendaftaran->file_krs,
            'file_transkrip' => $pendaftaran->file_transkrip,
            'file_bukti_bimbingan' => $pendaftaran->file_bukti_bimbingan,
        ];

        $this->dosen_pembimbing_1 = $pendaftaran->dosens()->where('peran', 'pembimbing_1')->first()?->dosen_id ?? '';
        $this->dosen_pembimbing_2 = $pendaftaran->dosens()->where('peran', 'pembimbing_2')->first()?->dosen_id ?? '';
    }

    public function rules()
    {
        $rules = [
            'jenis_ujian' => 'required|in:seminar_proposal,seminar_hasil,sidang_skripsi',
            'judul_penelitian' => 'required|string|max:255',
            'abstrak' => 'nullable|string',
            'selectedBidangKeahlian' => 'required|array|min:1',
            'selectedBidangKeahlian.*' => 'exists:bidang_keahlians,id',
            'dosen_pembimbing_1' => 'required|exists:users,id',
            'dosen_pembimbing_2' => 'nullable|exists:users,id|different:dosen_pembimbing_1',
        ];

        // Validasi File
        if (!$this->pendaftaran || $this->file_proposal) {
            $rules['file_proposal'] = 'nullable|file|mimes:pdf|max:10240';
        }
        if ($this->jenis_ujian !== 'seminar_proposal') {
            $rules['file_skripsi'] = 'nullable|file|mimes:pdf|max:20480';
        }

        return $rules;
    }

    public function validationAttributes()
    {
        return [
            'judul_penelitian' => 'Judul penelitian',
            'selectedBidangKeahlian' => 'Bidang keahlian',
            'dosen_pembimbing_1' => 'Dosen pembimbing 1',
            'dosen_pembimbing_2' => 'Dosen pembimbing 2',
        ];
    }

    public function save()
    {
        $this->validate();

        // Prevent duplicate registration for the same jenis_ujian
        if (!$this->pendaftaran) {
            $activeStatuses = ['pending', 'disetujui_panitia', 'disetujui_sekjur', 'disetujui_kajur', 'dijadwalkan', 'revisi'];
            $exists = Pendaftaran::where('mahasiswa_id', Auth::id())
                ->where('jenis_ujian', $this->jenis_ujian)
                ->whereIn('status', $activeStatuses)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'jenis_ujian' => ['Anda sudah memiliki pendaftaran aktif untuk jenis ujian ini.'],
                ]);
            }

            // Prevent registration for completed exam type
            $completed = Pendaftaran::where('mahasiswa_id', Auth::id())
                ->where('jenis_ujian', $this->jenis_ujian)
                ->where('status', 'selesai')
                ->exists();

            if ($completed) {
                $labels = [
                    'seminar_proposal' => 'Seminar Proposal',
                    'seminar_hasil' => 'Seminar Hasil',
                    'sidang_skripsi' => 'Sidang Skripsi',
                ];
                $jenisLabel = $labels[$this->jenis_ujian] ?? $this->jenis_ujian;
                throw ValidationException::withMessages([
                    'jenis_ujian' => ["Anda sudah pernah mendaftar dan menyelesaikan ujian jenis {$jenisLabel}. Silakan memilih jenis ujian yang berbeda."],
                ]);
            }
        }

        $data = [
            'mahasiswa_id' => Auth::id(),
            'jurusan_id' => Auth::user()->jurusan_id,
            'prodi_id' => Auth::user()->prodi_id,
            'jenis_ujian' => $this->jenis_ujian,
            'judul_penelitian' => $this->judul_penelitian,
            'abstrak' => $this->abstrak,
            'status' => 'pending',
        ];

        // Handle file uploads
        $fileFields = ['file_proposal', 'file_skripsi', 'file_persetujuan', 'file_krs', 'file_transkrip', 'file_bukti_bimbingan'];
        foreach ($fileFields as $field) {
            if ($this->$field) {
                if ($this->pendaftaran && !empty($this->existingFiles[$field])) {
                    Storage::disk('public')->delete($this->existingFiles[$field]);
                }
                $data[$field] = $this->$field->store('pendaftaran/' . Auth::id(), 'public');
            } elseif (!$this->pendaftaran) {
                $data[$field] = null;
            }
        }

        if ($this->pendaftaran) {
            $this->pendaftaran->update($data);
            $this->pendaftaran->dosens()->delete();
            $this->saveDosens($this->pendaftaran);
            $this->pendaftaran->bidangKeahlians()->sync($this->selectedBidangKeahlian);
        } else {
            $data['first_registered_at'] = now();
            $pendaftaran = Pendaftaran::create($data);
            $this->saveDosens($pendaftaran);
            $pendaftaran->bidangKeahlians()->attach($this->selectedBidangKeahlian);
        }
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
}
