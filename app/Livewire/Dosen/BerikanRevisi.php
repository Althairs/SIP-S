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

    public function mount($pendaftaran)
    {
        $this->pendaftaran = Pendaftaran::with(['mahasiswa', 'revisis', 'pengujis.dosen'])->findOrFail($pendaftaran);

        // Cari peran dosen ini di ujian ini
        $peran = UjianPenguji::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->first();

        $this->peranDosen = $peran?->peran ?? 'penguji_1';

        // Load existing revisi dari dosen ini
        $this->existingRevisis = Revisi::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->get();
    }

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

        // Refresh
        $this->existingRevisis = Revisi::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->get();
    }

    public function approveRevisi($revisiId)
    {
        $revisi = Revisi::findOrFail($revisiId);
        $revisi->update([
            'is_approved' => true,
            'approved_at' => now(),
            'status' => 'selesai',
        ]);

        // Hapus reminder terkait revisi ini jika sudah selesai semua
        $pendingRevisi = Revisi::where('pendaftaran_id', $revisi->pendaftaran_id)
            ->where('status', 'pending')
            ->count();

        if ($pendingRevisi == 0) {
            // Semua revisi selesai, hapus reminder
            \App\Models\Reminder::where('pendaftaran_id', $revisi->pendaftaran_id)
                ->where('tipe', 'revisi')
                ->delete();
        }

        session()->flash('success', 'Revisi mahasiswa disetujui.');
        $this->existingRevisis = Revisi::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->get();
    }

    public function deleteRevisi($revisiId)
    {
        Revisi::findOrFail($revisiId)->delete();
        session()->flash('success', 'Revisi dihapus.');
        $this->existingRevisis = Revisi::where('pendaftaran_id', $this->pendaftaran->id)
            ->where('dosen_id', auth()->id())
            ->get();
    }

    public function render()
    {
        return view('livewire.dosen.berikan-revisi')->layout('components.layouts.app-auth');
    }
}
