<?php

namespace App\Livewire\Kajur;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftaran;

// Sama, ganti jenis_ujian jadi 'sidang_skripsi'
class VerifikasiSidangSkripsi extends VerifikasiSeminarProposal
{
    public function render()
    {
        $jurusanId = auth()->user()->jurusan_id;

        $pendaftarans = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pembimbing1.dosen', 'pembimbing2.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->where('jenis_ujian', 'sidang_skripsi')
            ->whereIn('status', ['disetujui_sekjur', 'disetujui_kajur', 'ditolak_kajur'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul_penelitian', 'like', '%' . $this->search . '%')
                      ->orWhereHas('mahasiswa', function ($mq) {
                          $mq->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('nim', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.kajur.verifikasi-index', [
            'pendaftarans' => $pendaftarans,
            'title' => 'Verifikasi Sidang Skripsi',
            'jenisUjian' => 'sidang_skripsi',
        ])->layout('components.layouts.app-auth');
    }
}
