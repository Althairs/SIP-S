# RENCANA IMPLEMENTASI (PLANNING GUIDE)

## Baseline Teknologi
- **Framework:** Laravel 13
- **Frontend / Livewire:** Livewire v4
- **RBAC (Role-Based Access Control):** Spatie Laravel Permission v7 (Context7)

---

## 1. Perbaikan Checklist Hak Akses (Permissions) di Role Admin

### Deskripsi Masalah
Beberapa hak akses (seperti dosen, mahasiswa, bidang keahlian, dan lain-lain) tidak muncul pada sidebar.

### Langkah Solusi (High-Level)
- Periksa manajemen grup permission (biasanya didefinisikan di `app/Services/PermissionService.php` atau file konfigurasi sejenis).
- Daftarkan grup permission baru (dosen, mahasiswa, bidang_keahlian, kepakaran, dll.) ke dalam fungsi pengelompokan checklist dinamis agar dapat dibaca oleh view form Role.
- Pastikan nama permission yang ada di seeder/database sesuai dengan kategori kelompok checklist di view.

---

## 2. Penanganan State "Tanpa Jurusan" untuk Super Admin pada Master Data

### Deskripsi Masalah
Super Admin tidak memiliki `jurusan_id` (bernilai `null`). Akibatnya, halaman master data seperti `data-master/bidang-keahlian` dan lainnya kosong karena query di-scope ketat berdasarkan jurusan user aktif.

### Langkah Solusi (High-Level)
- Sesuaikan helper global scoping atau query builder di komponen master data.
- Jika pengguna aktif memiliki role `super_admin`, abaikan kondisi `where('jurusan_id', ...)` agar semua data dari seluruh jurusan dapat ditampilkan secara menyeluruh.

---

## 3. Pengisian Jurusan Dinamis saat Pembuatan Data Baru oleh Super Admin

### Deskripsi Masalah
Super Admin tidak dapat menambahkan data master baru (seperti bidang keahlian, dll.) karena sistem secara otomatis mengisi `jurusan_id` dari user aktif yang bernilai `null` / tidak valid.

### Langkah Solusi (High-Level)
- Pada form tambah/edit data master (misal: BidangKeahlian, Prodi, User, dll.):
  - Jika yang login adalah **Super Admin**, tampilkan input dropdown **Pilihan Jurusan** secara manual .
  - Jika yang login adalah user bersangkutan dengan jurusan (Kajur/Sekjur/Panitia), sembunyikan dropdown tersebut dan tetapkan `jurusan_id` secara otomatis dari user bersangkutan.
- Lakukan validasi input jurusan berdasarkan kondisi role di atas sebelum proses penyimpanan ke database.

---

## 4. Penanganan Bidang Keahlian pada Pendaftaran oleh Super Admin

### Deskripsi Masalah
Di form pendaftaran, muncul pesan error *"Belum ada bidang keahlian tersedia untuk jurusan Anda"* ketika diakses menggunakan akun Super Admin.

### Langkah Solusi (High-Level)
- Deteksi role pengguna di form pendaftaran. Jika diakses oleh Super Admin, tampilkan input pemilihan jurusan terlebih dahulu atau sediakan fallback daftar semua bidang keahlian yang aktif secara global dan **Pilih Mahasiswa** agar super admin bisa menambahkan pendaftaran mahasiswa.
- Jangan mengunci daftar bidang keahlian ke `Auth::user()->jurusan_id` secara mutlak jika role pengguna adalah Super Admin.

---

## 5. Sinkronisasi Tampilan Verifikasi Kajur

### Deskripsi Masalah
Menu/tampilan verifikasi seminar proposal, semhas, dan sidang skripsi bagian Kajur tidak tampil meskipun permission terkait sudah di-checklist di konfigurasi role.

### Langkah Solusi (High-Level)
- Periksa pengecekan hak akses di komponen verifikasi terkait (bisa berupa direktif `@can`, `@role`, atau middleware pada file routes).
- Pastikan semua verifikasi menggunakan permission granular Spatie v7 (misal: `view_verifikasi_seminar_proposal`, dsb.) dan bukan menggunakan pengecekan berbasis role statis (`Kajur`).
- Terapkan scope query dinamis (seperti poin 2) pada data verifikasi agar Super Admin dapat melihat dan memproses pengajuan verifikasi dari semua jurusan.

---
