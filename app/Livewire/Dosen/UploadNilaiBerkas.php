<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\UjianPenguji;

class UploadNilaiBerkas extends Component
{
    use WithFileUploads;

    public Pendaftaran $pendaftaran;
    public $peranDosen;
    public $existingPenilaian;

    public $filePenilaian;
    public $catatan = '';

    public function mount($pendaftaran)
    {
        if ($pendaftaran instanceof Pendaftaran) {
            $this->pendaftaran = $pendaftaran;
        } else {
            $this->pendaftaran = Pendaftaran::with('mahasiswa')->findOrFail($pendaftaran);
        }

        $peran = UjianPenguji::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->first();

        $this->peranDosen = $peran?->peran ?? 'penguji_1';

        $this->existingPenilaian = Penilaian::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->where('tipe_input', 'berkas')
            ->first();

        if ($this->existingPenilaian) {
            $this->catatan = $this->existingPenilaian->catatan ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'filePenilaian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'catatan' => 'nullable|string',
        ], [
            'filePenilaian.required' => 'File penilaian wajib diupload.',
            'filePenilaian.mimes' => 'Format file harus PDF, JPG, atau PNG.',
            'filePenilaian.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $filePath = $this->filePenilaian->store('penilaian/' . $this->pendaftaran->id, 'public');

        $data = [
            'pendaftaran_id' => $this->pendaftaran->id,
            'dosen_id' => auth()->id(),
            'peran_pemberi' => $this->peranDosen,
            'tipe_input' => 'berkas',
            'file_penilaian' => $filePath,
            'catatan' => $this->catatan,
            'status' => 'selesai',
            'submitted_at' => now(),
        ];

        if ($this->existingPenilaian) {
            // Hapus file lama
            if ($this->existingPenilaian->file_penilaian) {
                \Storage::disk('public')->delete($this->existingPenilaian->file_penilaian);
            }
            $this->existingPenilaian->update($data);
            session()->flash('success', 'Berkas nilai berhasil diperbarui.');
        } else {
            Penilaian::create($data);
            session()->flash('success', 'Berkas nilai berhasil diupload ke Panitia Administrasi.');
        }

        return redirect()->route('dosen.nilai.index');
    }

    public function render()
    {
        return view('livewire.dosen.upload-nilai-berkas')->layout('components.layouts.app-auth');
    }
}
