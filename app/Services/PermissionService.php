<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class PermissionService
{
    protected static ?array $menuCache = null;

    public static function getAllMenuItems(): array
    {
        if (static::$menuCache !== null) {
            return static::$menuCache;
        }

        $items = [
            // ── Dashboard ──
            ['label' => 'Dashboard', 'route' => null, 'permission' => null, 'group' => null, 'icon' => 'dashboard'],

            // ── Manajemen Data (super_admin) ──
            ['label' => 'Jurusan', 'route' => 'admin.jurusans.index', 'permission' => 'view_jurusan', 'group' => 'Manajemen Data', 'icon' => 'jurusan'],
            ['label' => 'Program Studi', 'route' => 'admin.prodis.index', 'permission' => 'view_prodi', 'group' => 'Manajemen Data', 'icon' => 'prodi'],
            ['label' => 'Users', 'route' => 'admin.users.index', 'permission' => 'view_users', 'group' => 'Manajemen Data', 'icon' => 'users'],
            ['label' => 'Role & Akses', 'route' => 'admin.roles.index', 'permission' => 'view_roles', 'group' => 'Manajemen Data', 'icon' => 'roles'],

            // ── Data Master (shared kajur/sekjur) ──
            ['label' => 'Data Dosen', 'route' => ['kajur' => 'kajur.data-master.dosen', 'sekjur' => 'sekjur.data-master.dosen'], 'permission' => 'view_dosen', 'group' => 'Data Master', 'icon' => 'dosen'],
            ['label' => 'Data Mahasiswa', 'route' => ['kajur' => 'kajur.data-master.mahasiswa', 'sekjur' => 'sekjur.data-master.mahasiswa'], 'permission' => 'view_mahasiswa', 'group' => 'Data Master', 'icon' => 'mahasiswa'],
            ['label' => 'Data Panitia', 'route' => ['kajur' => 'kajur.data-master.panitia', 'sekjur' => 'sekjur.data-master.panitia'], 'permission' => 'view_panitia', 'group' => 'Data Master', 'icon' => 'panitia'],
            ['label' => 'Kuota Dosen', 'route' => ['kajur' => 'kajur.data-master.kuota-dosen', 'sekjur' => 'sekjur.data-master.kuota-dosen'], 'permission' => 'view_kuota_dosen', 'group' => 'Data Master', 'icon' => 'kuota'],
            ['label' => 'Atribut Dosen', 'route' => 'kajur.data-master.atur-atribut-dosen', 'permission' => 'view_atribut_dosen', 'group' => 'Data Master', 'icon' => 'atribut'],
            ['label' => 'Bidang Keahlian', 'route' => ['kajur' => 'kajur.data-master.bidang-keahlian', 'sekjur' => 'sekjur.data-master.bidang-keahlian'], 'permission' => 'view_bidang_keahlian', 'group' => 'Data Master', 'icon' => 'bidang'],
            ['label' => 'Kepakaran', 'route' => ['kajur' => 'kajur.data-master.kepakaran', 'sekjur' => 'sekjur.data-master.kepakaran'], 'permission' => 'view_kepakaran', 'group' => 'Data Master', 'icon' => 'kepakaran'],
            ['label' => 'Pengaturan Reminder', 'route' => 'kajur.data-master.pengaturan-reminder', 'permission' => 'view_pengaturan_reminder', 'group' => 'Data Master', 'icon' => 'reminder'],
            ['label' => 'Penguji', 'route' => 'sekjur.data-master.penguji', 'permission' => 'view_penguji', 'group' => 'Data Master', 'icon' => 'penguji'],

            // ── Verifikasi ──
            ['label' => 'Seminar Proposal', 'route' => ['kajur' => 'kajur.verifikasi.seminar-proposal', 'sekjur' => 'sekjur.verifikasi.seminar-proposal'], 'permission' => 'verify_seminar_proposal', 'group' => 'Verifikasi', 'icon' => 'proposal'],
            ['label' => 'Seminar Hasil', 'route' => ['kajur' => 'kajur.verifikasi.seminar-hasil', 'sekjur' => 'sekjur.verifikasi.seminar-hasil'], 'permission' => 'verify_seminar_hasil', 'group' => 'Verifikasi', 'icon' => 'hasil'],
            ['label' => 'Sidang Skripsi', 'route' => ['kajur' => 'kajur.verifikasi.sidang-skripsi', 'sekjur' => 'sekjur.verifikasi.sidang-skripsi'], 'permission' => 'verify_sidang_skripsi', 'group' => 'Verifikasi', 'icon' => 'sidang'],

            // ── Mahasiswa ──
            ['label' => 'Pendaftaran', 'route' => 'mahasiswa.pendaftaran.index', 'permission' => 'view_pendaftaran', 'group' => 'Pendaftaran', 'icon' => 'pendaftaran'],
            ['label' => 'Jadwal Ujian', 'route' => 'mahasiswa.jadwal', 'permission' => 'view_jadwal', 'group' => 'Informasi', 'icon' => 'jadwal'],
            ['label' => 'Revisi', 'route' => 'mahasiswa.revisi', 'permission' => 'view_revisi', 'group' => 'Informasi', 'icon' => 'revisi'],
            ['label' => 'Nilai', 'route' => 'mahasiswa.nilai', 'permission' => 'view_nilai', 'group' => 'Informasi', 'icon' => 'nilai'],

            // ── Dosen ──
            ['label' => 'Revisi', 'route' => 'dosen.revisi.index', 'permission' => 'view_revisi', 'group' => 'Tugas Saya', 'icon' => 'revisi'],
            ['label' => 'Nilai', 'route' => 'dosen.nilai.index', 'permission' => 'view_nilai', 'group' => 'Tugas Saya', 'icon' => 'nilai'],
            ['label' => 'Jadwal Menguji', 'route' => 'dosen.jadwal', 'permission' => 'view_jadwal_dosen', 'group' => 'Informasi', 'icon' => 'jadwal'],
            ['label' => 'Kuota Saya', 'route' => 'dosen.kuota', 'permission' => 'view_kuota_saya', 'group' => 'Informasi', 'icon' => 'kuota'],

            // ── Panitia ──
            ['label' => 'Verifikasi Berkas', 'route' => 'panitia.verifikasi.berkas', 'permission' => 'verify_berkas', 'group' => 'Verifikasi', 'icon' => 'berkas'],
            ['label' => 'Jadwal Ujian', 'route' => 'panitia.penjadwalan.jadwal', 'permission' => 'manage_jadwal', 'group' => 'Penjadwalan', 'icon' => 'jadwal'],
            ['label' => 'Waktu & Sesi', 'route' => 'panitia.penjadwalan.setting-waktu', 'permission' => 'manage_jadwal', 'group' => 'Penjadwalan', 'icon' => 'waktu'],
            ['label' => 'Ruangan', 'route' => 'panitia.penjadwalan.setting-ruangan', 'permission' => 'manage_jadwal', 'group' => 'Penjadwalan', 'icon' => 'ruangan'],

            // ── Panitia Administrasi ──
            ['label' => 'Nilai Berkas', 'route' => 'panitia.administrasi.nilai-berkas', 'permission' => 'view_nilai_berkas', 'group' => 'Administrasi', 'icon' => 'berkas'],
            ['label' => 'Laporan', 'route' => 'panitia.administrasi.laporan', 'permission' => 'view_laporan', 'group' => 'Administrasi', 'icon' => 'laporan'],

            // ── Notifikasi ──
            ['label' => 'Notifikasi Whatsapp', 'route' => 'admin.notification-settings', 'permission' => 'view_notification_settings', 'group' => 'Notifikasi', 'icon' => 'notifikasi'],

            // ── Profile & Settings ──
            ['label' => 'Profile', 'route' => null, 'permission' => null, 'group' => 'Pengaturan', 'icon' => 'profile'],
            ['label' => 'Settings', 'route' => null, 'permission' => null, 'group' => 'Pengaturan', 'icon' => 'settings'],
        ];

        static::$menuCache = $items;
        return $items;
    }

    public static function getResolvedMenuItems(): array
    {
        $user = Auth::user();
        if (!$user) return [];

        $role = $user->getRoleNames()->first();
        $items = static::getAllMenuItems();
        $resolved = [];

        foreach ($items as $item) {
            // Cek apakah user memiliki permission yang dibutuhkan
            if (isset($item['permission']) && !$user->can($item['permission'])) {
                continue;
            }

            $route = $item['route'];
            if (is_array($route)) {
                // Perbaikan Fallback: Jika role aktif tidak didefinisikan (seperti super_admin),
                // ambil route pertama yang tersedia di dalam array sebagai fallback.
                $route = $route[$role] ?? reset($route);
            }

            if (!$route && $item['label'] !== 'Dashboard' && $item['label'] !== 'Profile' && $item['label'] !== 'Settings') {
                if (is_array($item['route'])) {
                    continue;
                }
            }

            $resolved[] = array_merge($item, ['resolved_route' => $route]);
        }

        return $resolved;
    }

    public static function getMenusForCurrentUser(): array
    {
        $items = static::getResolvedMenuItems();
        $grouped = [];

        // Dashboard first
        $dashboard = null;
        $settings = [];
        $others = [];

        foreach ($items as $item) {
            if ($item['label'] === 'Dashboard') {
                $dashboard = $item;
            } elseif ($item['group'] === 'Pengaturan') {
                $settings[] = $item;
            } else {
                $others[] = $item;
            }
        }

        // Group others by group name
        foreach ($others as $item) {
            $g = $item['group'] ?? '_ungrouped';
            $grouped[$g][] = $item;
        }

        // Prepend dashboard as its own group
        $result = [];
        if ($dashboard) {
            $result['Dashboard'] = [$dashboard];
        }
        foreach ($grouped as $g => $gItems) {
            $result[$g] = $gItems;
        }
        if (!empty($settings)) {
            $result['Pengaturan'] = $settings;
        }

        return $result;
    }

    public static function jurusanScope(string $column = 'jurusan_id'): \Closure
    {
        $user = Auth::user();
        if (!$user || $user->hasRole('super_admin')) {
            return fn($query) => $query;
        }
        return fn($query) => $query->where($column, $user->jurusan_id);
    }

    public static function getJurusanId(): ?int
    {
        $user = Auth::user();
        if (!$user || $user->hasRole('super_admin')) {
            return null;
        }
        return $user->jurusan_id;
    }

    public static function permissionGroups(): array
    {
       return [
            'jurusan' => ['view_jurusan', 'create_jurusan', 'edit_jurusan', 'delete_jurusan'],
            'prodi' => ['view_prodi', 'create_prodi', 'edit_prodi', 'delete_prodi'],
            'users' => ['view_users', 'create_users', 'edit_users', 'delete_users', 'activate_users'],
            'roles' => ['view_roles', 'assign_roles'],
            'dosen' => ['view_dosen', 'create_dosen', 'edit_dosen', 'delete_dosen'],
            'mahasiswa' => ['view_mahasiswa', 'create_mahasiswa', 'edit_mahasiswa', 'delete_mahasiswa'],
            'panitia' => ['view_panitia', 'create_panitia', 'edit_panitia', 'delete_panitia'],
            'kuota_dosen' => ['view_kuota_dosen', 'create_kuota_dosen', 'edit_kuota_dosen', 'delete_kuota_dosen'],
            'atribut_dosen' => ['view_atribut_dosen', 'create_atribut_dosen', 'edit_atribut_dosen', 'delete_atribut_dosen'],
            'bidang_keahlian' => ['view_bidang_keahlian', 'create_bidang_keahlian', 'edit_bidang_keahlian', 'delete_bidang_keahlian'],
            'kepakaran' => ['view_kepakaran', 'create_kepakaran', 'edit_kepakaran', 'delete_kepakaran'],
            'pengaturan_reminder' => ['view_pengaturan_reminder', 'create_pengaturan_reminder', 'edit_pengaturan_reminder', 'delete_pengaturan_reminder'],
            'penguji' => ['view_penguji', 'create_penguji', 'edit_penguji', 'delete_penguji'],
            'seminar_proposal' => ['view_seminar_proposal', 'verify_seminar_proposal'],
            'seminar_hasil' => ['view_seminar_hasil', 'verify_seminar_hasil'],
            'sidang_skripsi' => ['view_sidang_skripsi', 'verify_sidang_skripsi'],
            'verifikasi_berkas' => ['view_verify_berkas', 'verify_berkas'],
            'jadwal' => ['view_jadwal', 'manage_jadwal', 'schedule_ujian', 'generate_penguji'],
            // Sinkronisasi pendaftaran (ditambahkan delete_pendaftaran)
            'pendaftaran' => ['view_pendaftaran', 'create_pendaftaran', 'edit_pendaftaran', 'delete_pendaftaran'],
            // Sinkronisasi revisi (ditambahkan edit_revisi, delete_revisi)
            'revisi' => ['view_revisi', 'create_revisi', 'edit_revisi', 'delete_revisi'],
            // Sinkronisasi nilai (ditambahkan edit_nilai, delete_nilai)
            'nilai' => ['view_nilai', 'create_nilai', 'edit_nilai', 'delete_nilai'],
            'jadwal_dosen' => ['view_jadwal_dosen'],
            'kuota_saya' => ['view_kuota_saya'],
            'nilai_berkas' => ['view_nilai_berkas', 'manage_nilai_berkas'],
            'laporan' => ['view_laporan', 'export_laporan'],
            'import_export' => ['import_data', 'export_data'],
            'notifikasi' => ['view_notification_settings', 'manage_notification_settings'],
        ];
    }
}
