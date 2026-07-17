<?php

namespace App\Livewire\Panitia\Penjadwalan;

use App\Services\PermissionService;
use Livewire\Component;
use App\Models\PengaturanJadwal;

class SettingWaktu extends Component
{
    public $jamMulai = [];
    public $jamSelesai = [];
    public $labelSesi = [];
    public $editMode = false;
    public $pengaturanId;

    public function mount()
    {
        $jurusanId = PermissionService::getJurusanId();
        $pengaturan = PengaturanJadwal::where('jurusan_id', $jurusanId)->first();

        if ($pengaturan) {
            $this->editMode = true;
            $this->pengaturanId = $pengaturan->id;
            $this->jamMulai = $pengaturan->jam_mulai ?? [];
            $this->jamSelesai = $pengaturan->jam_selesai ?? [];
            $this->labelSesi = $pengaturan->label_sesi ?? [];
        } else {
            // Default 4 sesi
            $this->jamMulai = ['08:00', '10:00', '13:00', '15:00'];
            $this->jamSelesai = ['10:00', '12:00', '15:00', '17:00'];
            $this->labelSesi = ['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4'];
        }
    }

    public function addSesi()
    {
        $this->jamMulai[] = '08:00';
        $this->jamSelesai[] = '10:00';
        $this->labelSesi[] = 'Sesi ' . (count($this->jamMulai));
    }

    public function removeSesi($index)
    {
        if (count($this->jamMulai) <= 1) return;
        unset($this->jamMulai[$index]);
        unset($this->jamSelesai[$index]);
        unset($this->labelSesi[$index]);
        $this->jamMulai = array_values($this->jamMulai);
        $this->jamSelesai = array_values($this->jamSelesai);
        $this->labelSesi = array_values($this->labelSesi);
    }

    public function save()
    {
        $this->validate([
            'jamMulai.*' => 'required|date_format:H:i',
            'jamSelesai.*' => 'required|date_format:H:i|after:jamMulai.*',
            'labelSesi.*' => 'required|string|max:50',
        ], [
            'jamSelesai.*.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $jurusanId = PermissionService::getJurusanId();

        PengaturanJadwal::updateOrCreate(
            ['id' => $this->pengaturanId],
            [
                'jurusan_id' => $jurusanId,
                'jam_mulai' => $this->jamMulai,
                'jam_selesai' => $this->jamSelesai,
                'label_sesi' => $this->labelSesi,
                'is_active' => true,
            ]
        );

        session()->flash('success', 'Pengaturan waktu berhasil disimpan.');
    }

    public function resetToDefault()
    {
        $this->jamMulai = ['08:00', '10:00', '13:00', '15:00'];
        $this->jamSelesai = ['10:00', '12:00', '15:00', '17:00'];
        $this->labelSesi = ['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4'];
    }

    public function render()
    {
        return view('livewire.panitia.penjadwalan.setting-waktu')->layout('components.layouts.app-auth');
    }
}
