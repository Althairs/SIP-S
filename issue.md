# Issue: Housekeeping Kode — Komentar Informatif & Konsistensi Struktur

## Konteks

Proyek SIP-S adalah sistem manajemen akademik (pendaftaran, verifikasi, penjadwalan sidang/seminar) berbasis Laravel 13 + Livewire 4, dengan role: super_admin, admin, kajur, sekjur, dosen, panitia_*, mahasiswa. Komponen Livewire terorganisir per role di `app/Livewire/` dengan views-nya di `resources/views/livewire/`.

Sebelum pengembangan fitur berikutnya, kode perlu dibereskan agar mudah dipahami dan dirawat oleh programmer lain/model AI. Dokumen ini adalah panduan high-level — implementer bebas memilih detail teknis selama tujuan tercapai.

## Tujuan

### 1. Perbaiki Komentar di Seluruh Kode (Tree Files)

- Audit SEMUA file PHP di `app/` (terutama `app/Livewire/**`, `app/Models/**`, `app/Services/**`, `app/Imports/**`, `app/Jobs/**`) serta file blade di `resources/views/`.
- Tiap method, property, atau blok logika yang tidak jelas tujuannya diberi komentar **singkat tapi informatif** — jawab pertanyaan "mengapa/apa yang dilakukan", bukan sekadar mengulang nama method.
- Komentar wajib dalam Bahasa Indonesia (konsisten dengan kode yang sudah ada).
- JANGAN mengubah logika bisnis, nama method/property, atau perilaku aplikasi — murni penambahan/pembersihan komentar.
- Hapus komentar yang mati (kode ter-komentar, komentar tidak relevan lagi) kecuali memang menjelaskan keputusan historis penting.

### 2. Audit Konsistensi Struktur (Kode & Alur)

Periksa apakah seluruh komponen mengikuti pola yang sama. Jika ditemukan ketidakkonsistenan, **standardisasi** (refactor kecil), misalnya:

- **Pola state form**: semua komponen CRUD harus konsisten (misal `$showForm` + breadcrumb + hide tabel vs modal vs inline) — ikuti pola terbaik yang sudah ada di proyek ini.
- **Penamaan**: konsistensi penamaan method Livewire (`render`, `store`, `update`, `delete`, `resetForm`, dll) dan penamaan view/komponen (kebab-case vs PascalCase).
- **Alur akses data**: pastikan pola query (dengan `WhenLoaded`, eager loading, scoping per role) seragam di komponen yang sejenis.
- **Traits bersama** (`app/Livewire/Traits/`): dipakai di semua komponen yang membutuhkan, bukan di-copy-paste.
- **View/komponen**: pemanfaatan komponen Blade bersama (`<x-*>`) untuk elemen berulang (tabel, tombol, modal) — jangan duplikasi markup.
- Buat catatan hasil audit (file `audit-notes.md` opsional) berisi daftar inkonsistensi yang ditemukan dan yang sudah diperbaiki. Jangan ubah API/alur bisnis.

### 3. Verifikasi Stack: Livewire 4 + Laravel 13

- Pastikan proyek benar-benar berjalan di **Laravel 13** (`laravel/framework ^13`) dan **Livewire 4** (`livewire/livewire ^4`) — sudah tercantum di `composer.json`, verifikasi versi terinstall di `composer.lock` dan tidak ada API Livewire 2/3 yang deprecated dipakai.
- Pastikan `livewire/flux` dipakai sesuai versi yang kompatibel dengan Livewire 4.
- Jika ditemukan pemakaian API lama, catat saja dalam laporan — jangan upgrade besar-besaran tanpa persetujuan.

### 4. Gunakan Context7 untuk Referensi API

- Saat menulis/memeriksa kode yang menyentuh API framework atau library (Laravel, Livewire, Spatie Permission, DomPDF, Excel import, WhatsApp Cloud API), implementer WAJIB mengecek dokumentasi versi terkini melalui **Context7 MCP** (atau sumber resmi lain jika Context7 tidak tersedia).
- Tujuannya: memastikan pola yang digunakan valid untuk versi yang terinstall (mis. API Livewire 4, fitur baru Laravel 13) dan bukan pola usang.

## Definisi Selesai (Definition of Done)

1. Semua file utama sudah punya komentar informatif-singkat; tidak ada perubahan logika (verify lewat `git diff` hanya menyentuh komentar + refactor kecil standardisasi).
2. Hasil audit konsistensi terdokumentasi dan inkonsistensi yang layak diperbaiki sudah di-standardisasi.
3. Stack terkonfirmasi Laravel 13 + Livewire 4 di `composer.lock`.
4. Aplikasi tetap jalan: `composer run lint` (Pint) lolos dan test suite (`composer test` / `php artisan test`) tetap hijau, atau minimal tidak ada error PHP (`php artisan about`, cek log).

## Catatan untuk Implementer

- **Jangan over-engineer**: refactor hanya yang benar-benar tidak konsisten. Jika ragu, tulis catatan di laporan, jangan dipaksakan.
- Kerjakan urut: audit → komentar → standardisasi → verifikasi stack → dokumentasi.
- Gunakan `git diff` / `git log` untuk memahami perubahan terbaru agar tidak konflik dengan pekerjaan yang sedang berjalan.
