<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class PendaftaranIndex extends Component
{
    public function render()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');

        // Load relasi mahasiswa, dosen, dan bidang keahlian
        $query = Pendaftaran::with(['mahasiswa.jurusan', 'dosens.dosen', 'bidangkeahlians']);

        // Jika bukan Super Admin, filter pendaftaran berdasarkan ID mahasiswa yang login
        if (!$isSuperAdmin) {
            $query->where('mahasiswa_id', $user->id);
        }

        $pendaftarans = $query->latest()->get();

        return view('livewire.mahasiswa.pendaftaran-index', [
            'pendaftarans' => $pendaftarans,
            'isSuperAdmin' => $isSuperAdmin,
        ])->layout('components.layouts.app-auth');
    }

    public function deletePendaftaran($id)
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            $pendaftaran = Pendaftaran::findOrFail($id);
        } else {
            $pendaftaran = Pendaftaran::where('mahasiswa_id', $user->id)->findOrFail($id);
        }

        // Super admin bisa menghapus pendaftaran, mahasiswa biasa hanya pendaftaran bernilai 'draft'
        if ($user->hasRole('super_admin') || $pendaftaran->status === 'draft') {
            $pendaftaran->delete();
            session()->flash('success', 'Pendaftaran berhasil dihapus.');
        }
    }
}
