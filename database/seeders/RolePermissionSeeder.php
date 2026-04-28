<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Permissions
        $permissions = [
            // Manajemen Jurusan & Prodi (Super Admin)
            'view_jurusan',
            'create_jurusan',
            'edit_jurusan',
            'delete_jurusan',
            'view_prodi',
            'create_prodi',
            'edit_prodi',
            'delete_prodi',

            // Manajemen Users (Super Admin)
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'activate_users',

            // Manajemen Role (Super Admin)
            'assign_roles',
            'view_roles',

            // Data Master (Kajur)
            'view_dosen',
            'create_dosen',
            'edit_dosen',
            'delete_dosen',
            'view_mahasiswa',
            'create_mahasiswa',
            'edit_mahasiswa',
            'delete_mahasiswa',
            'view_panitia',
            'create_panitia',
            'edit_panitia',
            'delete_panitia',
            'manage_kuota_dosen',
            'manage_bidang_keahlian',
            'manage_kepakaran',

            // Verifikasi (Kajur)
            'verify_seminar_proposal',
            'verify_seminar_hasil',
            'verify_sidang_skripsi',

            // Import/Export
            'import_data',
            'export_data',

            // Penguji (Sekjur)
            'manage_penguji',
            'view_penguji',

            'verify_berkas',
            'schedule_ujian',
            'manage_jadwal',
            'generate_penguji',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ============================================
        // Buat Roles
        // ============================================

        // Super Admin
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Ketua Jurusan (Kajur)
        $kajur = Role::create(['name' => 'kajur']);
        $kajur->givePermissionTo([
            // Data Master
            'view_dosen',
            'create_dosen',
            'edit_dosen',
            'delete_dosen',
            'view_mahasiswa',
            'create_mahasiswa',
            'edit_mahasiswa',
            'delete_mahasiswa',
            'view_panitia',
            'create_panitia',
            'edit_panitia',
            'delete_panitia',
            'manage_kuota_dosen',
            'manage_bidang_keahlian',
            'manage_kepakaran',
            // Verifikasi
            'verify_seminar_proposal',
            'verify_seminar_hasil',
            'verify_sidang_skripsi',
            // Import/Export
            'import_data',
            'export_data',
        ]);

        // Sekretaris Jurusan (Sekjur)
        $sekjur = Role::create(['name' => 'sekjur']);
        $sekjur->givePermissionTo([
            // View Only Data Master
            'view_dosen',
            'view_mahasiswa',
            'view_panitia',
            // Penguji
            'manage_penguji',
            'view_penguji',
            // View Verifikasi
            'verify_seminar_proposal',
            'verify_seminar_hasil',
            'verify_sidang_skripsi',
            // Export
            'export_data',
        ]);

        // Panitia
        // Panitia Verifikasi
        $panitiaVerifikasi = Role::create(['name' => 'panitia_verifikasi']);
        $panitiaVerifikasi->givePermissionTo([
            'view_mahasiswa',
            'view_dosen',
            'verify_berkas',
        ]);

        // Panitia Penjadwalan
        $panitiaPenjadwalan = Role::create(['name' => 'panitia_penjadwalan']);
        $panitiaPenjadwalan->givePermissionTo([
            'view_mahasiswa',
            'view_dosen',
            'schedule_ujian',
            'manage_jadwal',
            'generate_penguji',
            'export_data',
        ]);

        // Panitia Administrasi (placeholder)
        $panitiaAdmin = Role::create(['name' => 'panitia_administrasi']);
        $panitiaAdmin->givePermissionTo([
            'view_mahasiswa',
        ]);

        // Dosen
        $dosen = Role::create(['name' => 'dosen']);
        $dosen->givePermissionTo([
            'view_mahasiswa',
        ]);

        // Mahasiswa
        $mahasiswa = Role::create(['name' => 'mahasiswa']);
        $mahasiswa->givePermissionTo([
            'view_dosen',
        ]);
    }
}
