<?php

namespace App\Livewire\Kajur;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

// Sama persis seperti VerifikasiSeminarProposal, hanya ganti jenis_ujian jadi 'seminar_hasil'
class VerifikasiSeminarHasil extends VerifikasiSeminarProposal
{
    public function render()
    {
        $jurusanId = Auth::user()->jurusan_id;

        $pendaftarans = Pendaftaran::with(['mahasiswa', 'bidangKeahlians', 'pengujis.dosen', 'pembimbing1.dosen', 'pembimbing2.dosen'])
            ->where('jurusan_id', $jurusanId)
            ->where('jenis_ujian', 'seminar_hasil')
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
            'title' => 'Verifikasi Seminar Hasil',
            'jenisUjian' => 'seminar_hasil',
        ])->layout('components.layouts.app-auth');
    }
}
