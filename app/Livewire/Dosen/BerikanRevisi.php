<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Pendaftaran;
use App\Models\Revisi;
use App\Models\UjianPenguji;
use Carbon\Carbon;

class BerikanRevisi extends Component
{
    public Pendaftaran $pendaftaran;
    public $peranDosen;

    // Form revisi
    public $isiRevisi = '';
    public $kategori = 'minor';
    public $deadlineDays = 14;
    public $existingRevisis = [];
    public $editRevisiId = null;
    public $showForm = false;

    // Untuk review file mahasiswa
    public $catatanDosenReview = '';
    public $reviewRevisiId = null;
    public $showReviewModal = false;

    public function mount($pendaftaran)
    {
        if ($pendaftaran instanceof Pendaftaran) {
            $this->pendaftaran = $pendaftaran;
        } else {
            $this->pendaftaran = Pendaftaran::with(['mahasiswa', 'revisis', 'pengujis.dosen'])->findOrFail($pendaftaran);
        }

        $peran = UjianPenguji::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->first();

        $this->peranDosen = $peran?->peran ?? 'penguji_1';
        $this->loadRevisis();
    }

    public function loadRevisis()
    {
        $this->existingRevisis = Revisi::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->orderByRaw("FIELD(status, 'diperiksa', 'pending', 'selesai', 'disetujui')")
            ->get();
    }

    // --- FORM TAMBAH/EDIT REVISI ---
    public function openForm($revisiId = null)
    {
        if ($revisiId) {
            $rev = Revisi::findOrFail($revisiId);
            $this->editRevisiId = $rev->id;
            $this->isiRevisi = $rev->isi_revisi;
            $this->kategori = $rev->kategori;
            $this->deadlineDays = $rev->deadline ? Carbon::now()->diffInDays($rev->deadline) : 14;
        } else {
            $this->editRevisiId = null;
            $this->isiRevisi = '';
            $this->kategori = 'minor';
            $this->deadlineDays = 14;
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->reset(['isiRevisi', 'kategori', 'deadlineDays', 'editRevisiId']);
    }

    public function saveRevisi()
    {
        $this->validate([
            'isiRevisi' => 'required|string|min:10',
            'kategori' => 'required|in:minor,major,kritis',
            'deadlineDays' => 'required|integer|min:1|max:90',
        ]);

        $data = [
            'pendaftaran_id' => $this->pendaftaran->id,
            'dosen_id' => auth()->id(),
            'peran_pemberi' => $this->peranDosen,
            'isi_revisi' => $this->isiRevisi,
            'kategori' => $this->kategori,
            'deadline' => now()->addDays($this->deadlineDays)->format('Y-m-d'),
        ];

        if ($this->editRevisiId) {
            Revisi::findOrFail($this->editRevisiId)->update($data);
            session()->flash('success', 'Revisi berhasil diperbarui.');
        } else {
            Revisi::create($data);
            session()->flash('success', 'Revisi berhasil ditambahkan.');
        }

        $this->closeForm();
        $this->loadRevisis();
    }

    // --- REVIEW FILE DARI MAHASISWA ---
    public function openReviewModal($revisiId)
    {
        $this->reviewRevisiId = $revisiId;
        $rev = Revisi::findOrFail($revisiId);
        $this->catatanDosenReview = $rev->catatan_dosen ?? '';
        $this->showReviewModal = true;
    }

    public function closeReviewModal()
    {
        $this->showReviewModal = false;
        $this->reset(['reviewRevisiId', 'catatanDosenReview']);
    }

    public function approveRevisi()
    {
        $revisi = Revisi::findOrFail($this->reviewRevisiId);
        $revisi->update([
            'is_approved' => true,
            'approved_at' => now(),
            'status' => 'disetujui', // Standarisasi status menjadi disetujui
            'catatan_dosen' => $this->catatanDosenReview,
        ]);

        // Cek apakah semua revisi mahasiswa ini sudah selesai
        $pendingRevisi = Revisi::where('pendaftaran_id', $revisi->pendaftaran_id)
            ->whereIn('status', ['pending', 'diperiksa'])
            ->count();

        if ($pendingRevisi == 0) {
            \App\Models\Reminder::where('pendaftaran_id', $revisi->pendaftaran_id)
                ->where('tipe', 'revisi')
                ->delete();
        }

        session()->flash('success', 'Revisi mahasiswa berhasil disetujui!');
        $this->closeReviewModal();
        $this->loadRevisis();
    }

    public function rejectRevisi()
    {
        $this->validate([
            'catatanDosenReview' => 'required|string|min:5'
        ], [
            'catatanDosenReview.required' => 'Berikan alasan mengapa revisi ditolak/diminta perbaikan ulang.'
        ]);

        $revisi = Revisi::findOrFail($this->reviewRevisiId);
        $revisi->update([
            'status' => 'pending', // Kembalikan ke pending agar mahasiswa upload ulang
            'file_revisi_mahasiswa' => null,
            'catatan_dosen' => $this->catatanDosenReview,
        ]);

        session()->flash('success', 'Revisi dikembalikan ke mahasiswa untuk diperbaiki ulang.');
        $this->closeReviewModal();
        $this->loadRevisis();
    }

    public function deleteRevisi($revisiId)
    {
        Revisi::findOrFail($revisiId)->delete();
        session()->flash('success', 'Revisi dihapus.');
        $this->loadRevisis();
    }

    public function render()
    {
        return view('livewire.dosen.berikan-revisi')->layout('components.layouts.app-auth');
    }
}
