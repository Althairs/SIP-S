<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    protected $jurusanId;

    public function __construct($jurusanId)
    {
        $this->jurusanId = $jurusanId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip if NIM or Nama is empty
            $nim = isset($row['nim']) ? trim($row['nim']) : null;
            $nama = isset($row['nama']) ? trim($row['nama']) : null;

            if (empty($nim) || empty($nama)) {
                continue;
            }

            // Resolve Program Studi / Prodi
            $prodiName = isset($row['program_studi']) ? trim($row['program_studi']) : (isset($row['prodi']) ? trim($row['prodi']) : null);
            $prodiId = null;

            if ($prodiName) {
                $prodi = Prodi::where('nama_prodi', 'like', '%' . $prodiName . '%')->first();
                if ($prodi) {
                    $prodiId = $prodi->id;
                }
            }

            // Check if user already exists
            $user = User::where('nim', $nim)->first();

            // Determine active status from Excel column
            $statusAktifCol = isset($row['status_aktif']) ? strtolower(trim($row['status_aktif'])) : 'aktif';
            $isActive = ($statusAktifCol === 'aktif');

            $data = [
                'name' => $nama,
                'nim' => $nim,
                'jurusan_id' => $this->jurusanId,
                'prodi_id' => $prodiId,
                'is_active' => $isActive,
                'nomor_hp' => isset($row['nomor_hp']) ? trim($row['nomor_hp']) : (isset($row['kontak']) ? trim($row['kontak']) : null),
                'alamat' => isset($row['alamat']) ? trim($row['alamat']) : null,
                'angkatan' => isset($row['angkatan']) ? trim($row['angkatan']) : (isset($row['angkatan']) ? trim($row['angkatan']) : null),
            ];

            if ($user) {
                // If student exists, update and activate if status is active
                if ($isActive) {
                    $data['is_active'] = true;
                }
                $user->update($data);
            } else {
                // Generate email from NIM if not exists
                $data['email'] = $nim . '@student.unigo.ac.id';
                $data['password'] = Hash::make($nim); // Default password as NIM
                $newUser = User::create($data);
                $newUser->assignRole('mahasiswa');
            }
        }
    }
}
