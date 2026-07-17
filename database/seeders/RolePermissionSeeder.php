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

        // Buat Permissions (granular: view/create/edit/delete per subject)
        $permissions = [
            // ── Manajemen Jurusan & Prodi ──
            'view_jurusan', 'create_jurusan', 'edit_jurusan', 'delete_jurusan',
            'view_prodi', 'create_prodi', 'edit_prodi', 'delete_prodi',

            // ── Manajemen Users ──
            'view_users', 'create_users', 'edit_users', 'delete_users', 'activate_users',

            // ── Manajemen Role ──
            'view_roles', 'assign_roles',

            // ── Data Master Dosen ──
            'view_dosen', 'create_dosen', 'edit_dosen', 'delete_dosen',

            // ── Data Master Mahasiswa ──
            'view_mahasiswa', 'create_mahasiswa', 'edit_mahasiswa', 'delete_mahasiswa',

            // ── Data Master Panitia ──
            'view_panitia', 'create_panitia', 'edit_panitia', 'delete_panitia',

            // ── Kuota Dosen ──
            'view_kuota_dosen', 'create_kuota_dosen', 'edit_kuota_dosen', 'delete_kuota_dosen',

            // ── Atribut Dosen ──
            'view_atribut_dosen', 'create_atribut_dosen', 'edit_atribut_dosen', 'delete_atribut_dosen',

            // ── Bidang Keahlian ──
            'view_bidang_keahlian', 'create_bidang_keahlian', 'edit_bidang_keahlian', 'delete_bidang_keahlian',

            // ── Kepakaran ──
            'view_kepakaran', 'create_kepakaran', 'edit_kepakaran', 'delete_kepakaran',

            // ── Pengaturan Reminder ──
            'view_pengaturan_reminder', 'create_pengaturan_reminder', 'edit_pengaturan_reminder', 'delete_pengaturan_reminder',

            // ── Penguji ──
            'view_penguji', 'create_penguji', 'edit_penguji', 'delete_penguji',

            // ── Seminar Proposal ──
            'view_seminar_proposal', 'verify_seminar_proposal',

            // ── Seminar Hasil ──
            'view_seminar_hasil', 'verify_seminar_hasil',

            // ── Sidang Skripsi ──
            'view_sidang_skripsi', 'verify_sidang_skripsi',

            // ── Verifikasi Berkas ──
            'view_verify_berkas', 'verify_berkas',

            // ── Penjadwalan ──
            'view_jadwal', 'manage_jadwal', 'schedule_ujian', 'generate_penguji',

            // ── Pendaftaran ──
            'view_pendaftaran', 'create_pendaftaran', 'edit_pendaftaran', 'delete_pendaftaran',

            // ── Revisi ──
            'view_revisi', 'create_revisi', 'edit_revisi', 'delete_revisi',

            // ── Nilai ──
            'view_nilai', 'create_nilai', 'edit_nilai', 'delete_nilai',

            // ── Dosen specific ──
            'view_jadwal_dosen', 'view_kuota_saya',

            // ── Panitia Administrasi ──
            'view_nilai_berkas', 'manage_nilai_berkas',

            // ── Laporan ──
            'view_laporan', 'export_laporan',

            // ── Notifikasi ──
            'view_notification_settings', 'manage_notification_settings',

            // ── Import/Export ──
            'import_data', 'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // ============================================
        // Buat Roles
        // ============================================

        // Super Admin
        $superAdmin = Role::findOrCreate('super_admin');
        $superAdmin->givePermissionTo(Permission::all());

        // Ketua Jurusan (Kajur)
        $kajur = Role::findOrCreate('kajur');
        $kajur->givePermissionTo([
            // Data Master - Full CRUD
            'view_dosen', 'create_dosen', 'edit_dosen', 'delete_dosen',
            'view_mahasiswa', 'create_mahasiswa', 'edit_mahasiswa', 'delete_mahasiswa',
            'view_panitia', 'create_panitia', 'edit_panitia', 'delete_panitia',
            'view_kuota_dosen', 'create_kuota_dosen', 'edit_kuota_dosen', 'delete_kuota_dosen',
            'view_atribut_dosen', 'create_atribut_dosen', 'edit_atribut_dosen', 'delete_atribut_dosen',
            'view_bidang_keahlian', 'create_bidang_keahlian', 'edit_bidang_keahlian', 'delete_bidang_keahlian',
            'view_kepakaran', 'create_kepakaran', 'edit_kepakaran', 'delete_kepakaran',
            'view_pengaturan_reminder', 'create_pengaturan_reminder', 'edit_pengaturan_reminder', 'delete_pengaturan_reminder',
            // Verifikasi
            'view_seminar_proposal', 'verify_seminar_proposal',
            'view_seminar_hasil', 'verify_seminar_hasil',
            'view_sidang_skripsi', 'verify_sidang_skripsi',
            // Import/Export
            'import_data', 'export_data',
        ]);

        // Sekretaris Jurusan (Sekjur)
        $sekjur = Role::findOrCreate('sekjur');
        $sekjur->givePermissionTo([
            // View Only Data Master
            'view_dosen', 'view_mahasiswa', 'view_panitia',
            'view_kuota_dosen', 'view_bidang_keahlian', 'view_kepakaran',
            // Penguji - Full CRUD
            'view_penguji', 'create_penguji', 'edit_penguji', 'delete_penguji',
            // View Verifikasi
            'view_seminar_proposal', 'verify_seminar_proposal',
            'view_seminar_hasil', 'verify_seminar_hasil',
            'view_sidang_skripsi', 'verify_sidang_skripsi',
            // Export
            'export_data',
        ]);

        // Panitia Verifikasi
        $panitiaVerifikasi = Role::findOrCreate('panitia_verifikasi');
        $panitiaVerifikasi->givePermissionTo([
            'view_mahasiswa', 'view_dosen',
            'view_verify_berkas', 'verify_berkas',
        ]);

        // Panitia Penjadwalan
        $panitiaPenjadwalan = Role::findOrCreate('panitia_penjadwalan');
        $panitiaPenjadwalan->givePermissionTo([
            'view_mahasiswa', 'view_dosen',
            'view_jadwal', 'manage_jadwal', 'schedule_ujian', 'generate_penguji',
            'export_data',
        ]);

        // Panitia Administrasi
        $panitiaAdmin = Role::findOrCreate('panitia_administrasi');
        $panitiaAdmin->givePermissionTo([
            'view_mahasiswa',
            'view_nilai_berkas', 'manage_nilai_berkas',
            'view_laporan', 'export_laporan',
            'export_data',
        ]);

        // Dosen
        $dosen = Role::findOrCreate('dosen');
        $dosen->givePermissionTo([
            'view_mahasiswa', 'view_dosen',
            'view_revisi', 'create_revisi',
            'view_nilai', 'create_nilai',
            'view_jadwal_dosen', 'view_kuota_saya',
        ]);

        // Mahasiswa
        $mahasiswa = Role::findOrCreate('mahasiswa');
        $mahasiswa->givePermissionTo([
            'view_dosen',
            'view_pendaftaran', 'create_pendaftaran', 'edit_pendaftaran',
            'view_jadwal',
            'view_revisi', 'create_revisi',
            'view_nilai',
        ]);
    }
}
