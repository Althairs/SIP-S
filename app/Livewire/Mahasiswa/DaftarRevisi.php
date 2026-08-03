<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Revisi;
use Livewire\Attributes\Rule;

class DaftarRevisi extends Component
{
    use WithFileUploads;

    public $revisis;
    public $isSuperAdmin = false;

    #[Rule('required|file|mimes:pdf,doc,docx|max:10240')]
    public $file_revisi;

    public $catatan_mahasiswa = '';
    public $selectedRevisiId = null;

    public function mount()
    {
        $this->isSuperAdmin = auth()->user()->hasRole('super_admin');
        $this->loadRevisis();
    }

    public function loadRevisis()
    {
        $this->revisis = Revisi::with(['pendaftaran', 'pendaftaran.mahasiswa', 'pendaftaran.mahasiswa.jurusan', 'pendaftaran.mahasiswa.prodi', 'dosen', 'ujianPenguji'])
            ->when(!$this->isSuperAdmin, function ($query) {
                $query->whereHas('pendaftaran', function ($q) {
                    $q->where('mahasiswa_id', auth()->id());
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function selectRevisi($id)
    {
        $this->selectedRevisiId = $id;
        $this->reset(['file_revisi', 'catatan_mahasiswa']);
        $this->resetErrorBag();
    }

    public function closeDetail()
    {
        $this->selectedRevisiId = null;
        $this->reset(['file_revisi', 'catatan_mahasiswa']);
        $this->resetErrorBag();
    }

    public function uploadRevisi()
    {
        $this->validate();

        $revisi = Revisi::findOrFail($this->selectedRevisiId);

        $path = $this->file_revisi->store('revisi_mahasiswa', 'public');

        $revisi->update([
            'file_revisi_mahasiswa' => $path,
            'catatan_mahasiswa' => $this->catatan_mahasiswa,
            'status' => 'diperiksa',
            'uploaded_at' => now(),
        ]);

        $this->closeDetail();
        $this->loadRevisis();
        session()->flash('success', 'File revisi berhasil diunggah.');
    }

    public function render()
    {
        return view('livewire.mahasiswa.daftar-revisi', [
            'isSuperAdmin' => $this->isSuperAdmin,
        ])->layout('components.layouts.app-auth');
    }
}
