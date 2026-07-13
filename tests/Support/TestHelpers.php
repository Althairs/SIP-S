<?php

namespace Tests\Support;

use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDosen;
use App\Models\Prodi;
use App\Models\UjianPenguji;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestHelpers
{
    public static function seedRoles(): void
    {
        if (Role::where('name', 'super_admin')->exists()) {
            return;
        }

        (new RolePermissionSeeder)->run();
    }

    public static function createJurusanProdi(): array
    {
        $suffix = substr(uniqid(), -6);

        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Informatika Test '.$suffix,
            'kode_jurusan' => 'TI-'.$suffix,
            'is_active' => true,
        ]);

        $prodi = Prodi::create([
            'jurusan_id' => $jurusan->id,
            'nama_prodi' => 'S1 Informatika Test '.$suffix,
            'kode_prodi' => 'IF-'.$suffix,
            'is_active' => true,
        ]);

        return [$jurusan, $prodi];
    }

    public static function createUser(string $role, ?Jurusan $jurusan = null, ?Prodi $prodi = null, array $overrides = []): User
    {
        self::seedRoles();

        [$defaultJurusan, $defaultProdi] = $jurusan && $prodi
            ? [$jurusan, $prodi]
            : self::createJurusanProdi();

        $user = User::create(array_merge([
            'name' => ucfirst(str_replace('_', ' ', $role)).' Test',
            'email' => $role.'_'.uniqid().'@test.com',
            'password' => Hash::make('password'),
            'jurusan_id' => $defaultJurusan->id,
            'prodi_id' => $defaultProdi->id,
            'nomor_hp' => '081234567890',
            'is_active' => true,
        ], $overrides));

        $user->assignRole($role);

        return $user;
    }

    public static function createPendaftaran(User $mahasiswa, string $jenisUjian = 'seminar_proposal', array $overrides = []): Pendaftaran
    {
        return Pendaftaran::create(array_merge([
            'mahasiswa_id' => $mahasiswa->id,
            'jurusan_id' => $mahasiswa->jurusan_id,
            'prodi_id' => $mahasiswa->prodi_id,
            'jenis_ujian' => $jenisUjian,
            'judul_penelitian' => 'Judul Penelitian Test',
            'abstrak' => 'Abstrak test',
            'status' => 'dijadwalkan',
            'tanggal_ujian' => now()->addWeek(),
            'ruangan' => 'Ruang Seminar 1',
            'sesi' => 1,
        ], $overrides));
    }

    public static function attachPenguji(Pendaftaran $pendaftaran, User $penguji1, ?User $penguji2 = null): void
    {
        UjianPenguji::create([
            'pendaftaran_id' => $pendaftaran->id,
            'dosen_id' => $penguji1->id,
            'peran' => 'penguji_1',
        ]);

        if ($penguji2) {
            UjianPenguji::create([
                'pendaftaran_id' => $pendaftaran->id,
                'dosen_id' => $penguji2->id,
                'peran' => 'penguji_2',
            ]);
        }
    }

    public static function attachPembimbing(Pendaftaran $pendaftaran, User $pembimbing1, ?User $pembimbing2 = null): void
    {
        PendaftaranDosen::create([
            'pendaftaran_id' => $pendaftaran->id,
            'dosen_id' => $pembimbing1->id,
            'peran' => 'pembimbing_1',
        ]);

        if ($pembimbing2) {
            PendaftaranDosen::create([
                'pendaftaran_id' => $pendaftaran->id,
                'dosen_id' => $pembimbing2->id,
                'peran' => 'pembimbing_2',
            ]);
        }
    }
}
