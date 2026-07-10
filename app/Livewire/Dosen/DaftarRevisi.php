<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Revisi;
use App\Models\UjianPenguji;
use App\Models\Pendaftaran;

class DaftarRevisi extends Component
{
    public $selectedRevisiId = null;
    public $catatan_dosen = '';

    public function selectRevisi($id)
    {
        $this->selectedRevisiId = $id;
        $revisi = Revisi::find($id);
        if ($revisi) {
            $this->catatan_dosen = $revisi->catatan_dosen ?? '';
        }
    }

    public function approveRevisi()
    {
        $revisi = Revisi::findOrFail($this->selectedRevisiId);

        $revisi->update([
            'status' => 'disetujui',
            'is_approved' => true,
            'approved_at' => now(),
            'catatan_dosen' => $this->catatan_dosen,
        ]);

        $this->reset(['selectedRevisiId', 'catatan_dosen']);
        session()->flash('success', 'Revisi berhasil disetujui.');
    }

    public function requestRevisiBaru()
    {
        $this->validate([
            'catatan_dosen' => 'required|string',
        ], [
            'catatan_dosen.required' => 'Catatan wajib diisi saat meminta revisi baru.',
        ]);

        $revisi = Revisi::findOrFail($this->selectedRevisiId);

        $revisi->update([
            'status' => 'pending',
            'is_approved' => false,
            'approved_at' => null,
            'catatan_dosen' => $this->catatan_dosen,
        ]);

        $this->reset(['selectedRevisiId', 'catatan_dosen']);
        session()->flash('success', 'Mahasiswa diminta untuk mengunggah revisi baru.');
    }

    public function render()
    {
        $dosenId = auth()->id();

        // Ujian yang sudah selesai di mana dosen ini jadi penguji
        $ujianSelesai = UjianPenguji::where('dosen_id', $dosenId)
            ->whereHas('pendaftaran', function($q) {
                $q->where('status', 'selesai');
            })
            ->with(['pendaftaran.mahasiswa', 'pendaftaran.revisis' => function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            }])
            ->get();

        // Revisi yang sudah dibuat (sebagai penguji) - hanya yang sudah disetujui/selesai
        $revisiSaya = Revisi::byDosen($dosenId)
            ->where(function($q) {
                $q->where('is_approved', true)
                  ->orWhereIn('status', ['disetujui', 'selesai']);
            })
            ->with('pendaftaran.mahasiswa')
            ->latest()
            ->get();

        // Ujian bimbingan (sebagai pembimbing)
        $bimbinganUjian = Pendaftaran::where(function($q) use ($dosenId) {
                $q->whereHas('pembimbing1', function($q2) use ($dosenId) {
                    $q2->where('dosen_id', $dosenId);
                })->orWhereHas('pembimbing2', function($q2) use ($dosenId) {
                    $q2->where('dosen_id', $dosenId);
                });
            })
            ->where('status', 'selesai')
            ->with(['mahasiswa', 'revisis.dosen'])
            ->latest()
            ->get();

        return view('livewire.dosen.daftar-revisi', [
            'ujianSelesai' => $ujianSelesai,
            'revisiSaya' => $revisiSaya,
            'bimbinganUjian' => $bimbinganUjian,
        ])->layout('components.layouts.app-auth');
    }
}
