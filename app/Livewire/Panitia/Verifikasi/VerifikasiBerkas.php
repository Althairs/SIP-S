<?php

namespace App\Livewire\Panitia\Verifikasi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftaran;

class VerifikasiBerkas extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending';

    public function updateStatus($id, $status)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => $status,
            'approved_at' => $status === 'disetujui_panitia' ? now() : null, // UPDATE
        ]);

        session()->flash('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $pendaftarans = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'dosens.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->when($this->search, function ($query) {
                $query->whereHas('mahasiswa', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nim', 'like', '%' . $this->search . '%');
                })->orWhere('judul_penelitian', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.panitia.verifikasi.verifikasi-berkas', [
            'pendaftarans' => $pendaftarans,
        ])->layout('components.layouts.app-auth');
    }
}
