# Issue: Sidebar Dinamis Berbasis Permission & Restrukturisasi Role/Akses

## Masalah

1. **Sidebar admin statis** — Sidebar saat ini dikontrol oleh directive `@role()` yang hardcode menu per role. Jika admin memberi permission `view_jadwal` ke role sekjur, sidebar tidak akan menampilkan menu jadwal untuk sekjur karena sidebar tidak membaca permission, melainkan hanya mengecek nama role.

2. **Permission terlalu kasar** — Permission seperti `manage_kuota_dosen` bersifat all-in-one (CRUD). Tidak bisa dipisah mana yang hanya view, create, edit, atau delete. Ini membuat admin tidak punya kontrol granular.

## Solusi

### 1. Permission Menjadi Granular (view, create, edit, delete)

Ubah semua permission yang sebelumnya `manage_*` atau tunggal menjadi 4 permission terpisah:

| Sebelum | Sesudah |
|---|---|
| `manage_kuota_dosen` | `view_kuota_dosen`, `create_kuota_dosen`, `edit_kuota_dosen`, `delete_kuota_dosen` |
| `manage_bidang_keahlian` | `view_bidang_keahlian`, `create_bidang_keahlian`, `edit_bidang_keahlian`, `delete_bidang_keahlian` |
| `manage_kepakaran` | `view_kepakaran`, `create_kepakaran`, `edit_kepakaran`, `delete_kepakaran` |
| `manage_penguji` | `view_penguji`, `create_penguji`, `edit_penguji`, `delete_penguji` |
| `verify_*` | `view_verify_*`, `verify_*` (pisahkan view dari aksi verifikasi) |
| `schedule_ujian`, `manage_jadwal`, `generate_penguji` | Dipecah sesuai pola `{view|create|edit|delete}_{subject}` |

Lakukan migrasi data: rename permission lama ke struktur baru di `RolePermissionSeeder` dan buat migration untuk rename di database.

### 2. Sidebar Berbasis Permission (Bukan Role)

Buat sistem menu terpusat yang mendefinisikan setiap menu item beserta permission yang dibutuhkan untuk menampilkannya. Contoh struktur:

```php
// config/menus.php atau service provider
$menus = [
    [
        'label' => 'Dashboard',
        'route' => 'admin.dashboard',
        'permission' => 'view_dashboard',
        'icon' => '...',
    ],
    [
        'label' => 'Jadwal',
        'route' => '...',
        'permission' => 'view_jadwal',
        'icon' => '...',
    ],
    // ...
];
```

Sidebar kemudian di-render dengan mengecek `auth()->user()->can($menu['permission'])`. Jika user punya permission `view_jadwal`, menu jadwal akan tampil — tanpa peduli role-nya apa.

Dengan ini, admin cukup centang permission `view_jadwal` di form role edit, dan sidebar otomatis menyesuaikan.

### 3. Permission Context Provider

Buat service/helper yang bisa digunakan dari mana saja (Livewire, Blade, Controller) untuk:

- Mengecek akses ke suatu fitur (`canView()`, `canCreate()`, `canEdit()`, `canDelete()`)
- Mendapatkan daftar menu yang diizinkan untuk user saat ini
- Menyediakan permission grouping (misal: subjek "Kuota Dosen" punya 4 child permission)

Implementasi bisa berupa:
- **Laravel Service class** (`PermissionService`) yang dibind ke container
- **Blade directive / helper function** untuk penggunaan di view
- **Livewire component trait** untuk komponen yang butuh pengecekan akses

### 4. Update RolePermissionSeeder

Sesuaikan seeder agar semua role mendapat permission yang sesuai dengan struktur baru. Contoh:

- `super_admin` → semua permission
- `kajur` → `view_kuota_dosen`, `create_kuota_dosen`, `edit_kuota_dosen`, `delete_kuota_dosen`, dll.
- `sekjur` → hanya `view_*` untuk sebagian besar, kecuali `manage_penguji` (dipecah jadi granular)

### 5. Update Route Protection

Tambahkan permission check di route definition menggunakan Laravel `can` middleware:

```php
Route::middleware(['auth', 'can:view_jadwal'])->get('/jadwal', ...);
```

Atau di dalam Livewire component dengan `abort_unless()`.

## File yang Perlu Diubah

| File | Perubahan |
|---|---|
| `database/seeders/RolePermissionSeeder.php` | Permission baru granular + penyesuaian assignment |
| `resources/views/components/navigation/sidebar.blade.php` | Ganti `@role()` dengan `@can()` atau helper permission-based |
| `routes/web.php` | Tambahkan middleware `can:` pada route groups |
| `app/Livewire/Admin/RoleCreate.php` | Sesuaikan form untuk menampilkan permission granular (view/create/edit/delete per subjek) |
| `app/Livewire/Admin/RoleEdit.php` | Sama seperti di atas |
| `resources/views/livewire/admin/role-create.blade.php` | Tampilkan permission dalam grup: per subjek dengan 4 checkbox (view/create/edit/delete) |
| `resources/views/livewire/admin/role-edit.blade.php` | Sama |
| `app/Providers/AppServiceProvider.php` | Jika perlu register helper/view composer untuk sidebar |
| `config/menus.php` (baru) | Definisi menu terpusat (jika pakai pendekatan config) |
| `app/Services/PermissionService.php` (baru) | Service untuk pengecekan permission terpusat |

## Catatan Implementasi

- Jangan buang permission lama langsung saat migrasi. Buat transisi dengan rename atau duplikasi agar tidak merusak data yang sudah ada (user_role_has_permissions di database).
- Sidebar harus tetap fast — cache permission user di session atau pakai Spatie cache yang sudah ada.
- Form role edit harus tetap user-friendly: permission ditampilkan dalam grup subjek, bukan flat list panjang.
