<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kepakaran;
use App\Models\KuotaDosen;
use App\Models\User;

class KepakaranKuotaSeeder extends Seeder
{
    public function run(): void
    {
        $kepakarans = [
            ['nama' => 'Profesor', 'level' => 1, 'deskripsi' => 'Guru Besar'],
            ['nama' => 'Kepala Lektor (S3)', 'level' => 2, 'deskripsi' => 'Lektor Kepala dengan Doktor'],
            ['nama' => 'Kepala Lektor (S2)', 'level' => 3, 'deskripsi' => 'Lektor Kepala dengan Magister'],
            ['nama' => 'Lektor (S3)', 'level' => 4, 'deskripsi' => 'Lektor dengan Doktor'],
            ['nama' => 'Lektor (S2)', 'level' => 5, 'deskripsi' => 'Lektor dengan Magister'],
            ['nama' => 'Ahli Bidang (S3)', 'level' => 6, 'deskripsi' => 'Asisten Ahli dengan Doktor'],
            ['nama' => 'Ahli Bidang (S2)', 'level' => 7, 'deskripsi' => 'Asisten Ahli dengan Magister'],
        ];

        foreach ($kepakarans as $k) {
            Kepakaran::create([
                'nama_kepakaran' => $k['nama'],
                'hierarki_level' => $k['level'],
                'deskripsi' => $k['deskripsi'],
                'is_active' => true,
            ]);
        }

        // Assign kepakaran ke dosen yang sudah ada
        $dosens = User::role('dosen')->get();
        $kepakaranList = Kepakaran::all();

        foreach ($dosens as $index => $dosen) {
            // Assign kepakaran secara bergantian
            $kepakaran = $kepakaranList[$index % $kepakaranList->count()];
            $dosen->update(['kepakaran_id' => $kepakaran->id]);

            // Buat kuota
            KuotaDosen::create([
                'dosen_id' => $dosen->id,
                'jurusan_id' => $dosen->jurusan_id,
                'kuota_pembimbing' => rand(3, 8),
                'kuota_penguji' => rand(5, 15),
                'terpakai_pembimbing' => rand(0, 3),
                'terpakai_penguji' => rand(0, 5),
            ]);
        }

        echo "Kepakaran & Kuota seeder selesai!\n";
    }
}
