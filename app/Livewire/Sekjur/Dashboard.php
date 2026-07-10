<?php

namespace App\Livewire\Sekjur;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class Dashboard extends Component
{
    public array $stats = [];
    public $upcoming;
    public $recent;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $now = Carbon::now();

        $jurusanId = Auth::user()->jurusan_id;

        $baseQuery = Pendaftaran::where('jurusan_id', $jurusanId);

        $this->stats = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'disetujui_panitia' => (clone $baseQuery)->where('status', 'disetujui_panitia')->count(),
            'menunggu_penguji' => (clone $baseQuery)->where('status', 'disetujui_panitia')->doesntHave('pengujis')->count(),
            'dijadwalkan' => (clone $baseQuery)->where('status', 'dijadwalkan')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'revisi' => (clone $baseQuery)->where('status', 'revisi')->count(),
        ];

        $this->upcoming = (clone $baseQuery)
            ->where('status', 'dijadwalkan')
            ->whereBetween('tanggal_ujian', [$now, $now->copy()->addDays(7)])
            ->orderBy('tanggal_ujian')
            ->limit(5)
            ->get();

        $this->recent = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.sekjur.dashboard', [
            'stats' => $this->stats,
            'upcoming' => $this->upcoming,
            'recent' => $this->recent,
        ])->layout('components.layouts.app-auth');
    }
}
