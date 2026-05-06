<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengaturanJadwal;
use App\Models\Ruangan;
use App\Models\Jurusan;

class PengaturanJadwalSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = Jurusan::active()->get();

        foreach ($jurusans as $jurusan) {
            // Pengaturan Waktu Default
            PengaturanJadwal::create([
                'jurusan_id' => $jurusan->id,
                'jam_mulai' => ['08:00', '10:00', '13:00', '15:00'],
                'jam_selesai' => ['10:00', '12:00', '15:00', '17:00'],
                'label_sesi' => ['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4'],
                'is_active' => true,
            ]);

            // Ruangan Default per Jurusan
            $ruanganData = [
                ['kode' => $jurusan->kode_jurusan . '-R01', 'nama' => 'Ruang Seminar 1', 'lokasi' => 'Lt. 2 Gedung Utama', 'kapasitas' => 30],
                ['kode' => $jurusan->kode_jurusan . '-R02', 'nama' => 'Ruang Seminar 2', 'lokasi' => 'Lt. 2 Gedung Utama', 'kapasitas' => 25],
                ['kode' => $jurusan->kode_jurusan . '-R03', 'nama' => 'Ruang Sidang', 'lokasi' => 'Lt. 3 Gedung Utama', 'kapasitas' => 20],
                ['kode' => $jurusan->kode_jurusan . '-A01', 'nama' => 'Aula ' . $jurusan->nama_jurusan, 'lokasi' => 'Lt. 1 Gedung Utama', 'kapasitas' => 50],
            ];

            foreach ($ruanganData as $r) {
                Ruangan::create([
                    'jurusan_id' => $jurusan->id,
                    'kode_ruangan' => $r['kode'],
                    'nama_ruangan' => $r['nama'],
                    'lokasi' => $r['lokasi'],
                    'kapasitas' => $r['kapasitas'],
                    'is_active' => true,
                ]);
            }
        }

        echo "Pengaturan Jadwal & Ruangan seeder selesai!\n";
    }
}
