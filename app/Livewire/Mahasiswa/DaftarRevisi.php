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

    #[Rule('required|file|mimes:pdf,doc,docx|max:10240')]
    public $file_revisi;

    public $catatan_mahasiswa = '';
    public $selectedRevisiId = null;

    public function mount()
    {
        $this->loadRevisis();
    }

    public function loadRevisis()
    {
        $this->revisis = Revisi::with(['pendaftaran', 'dosen', 'ujianPenguji'])
            ->whereHas('pendaftaran', function ($query) {
                $query->where('mahasiswa_id', auth()->id());
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

    public function closeModal()
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

        $this->closeModal();
        $this->loadRevisis();
        session()->flash('success', 'File revisi berhasil diunggah.');
    }

    public function render()
    {
        return view('livewire.mahasiswa.daftar-revisi')->layout('components.layouts.app-auth');
    }
}
