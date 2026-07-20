# Issue: Refactoring Hak Akses Super Admin & Penambahan Modal Detail

## Latar Belakang

Saat ini sistem memiliki beberapa permasalahan terkait hak akses **Super Admin** (`super_admin`):

1. **Super Admin tidak bisa melihat/mengedit/menghapus data milik mahasiswa** di beberapa fitur karena query masih di-hardcode dengan `where('mahasiswa_id', Auth::id())` tanpa pengecualian.
2. **Filter jurusan** (`PermissionService::jurusanScope()`) pada modul Panitia/Kajur/Sekjur mengunci data berdasarkan jurusan user, sehingga Super Admin tidak bisa melihat lintas jurusan.
3. **Tidak ada modal detail** pada halaman list/index di beberapa modul (Kajur verifikasi, Sekjur penguji, Dosen nilai, dll) sehingga Super Admin dan role yang bersangkutan sulit meninjau berkas secara _inline_.
4. **Tampilan (Blade View)** belum menampilkan identitas mahasiswa (Nama, NIM, Jurusan/Prodi) saat diakses oleh Super Admin.

## Tujuan

- Super Admin dapat **melihat, mengedit, dan menghapus** seluruh data milik mahasiswa di SEMUA fitur.
- Mahasiswa biasa hanya bisa mengakses datanya sendiri.
- Super Admin bisa melewati filter jurusan (`jurusanScope`) untuk melihat data lintas jurusan.
- Setiap halaman list/index yang relevan memiliki **modal detail** untuk meninjau berkas secara _inline_.

---

## 1. Refactoring Query Filter (Aturan Eloquent)

### Lokasi: `app/Livewire/Mahasiswa/`

| File | Masalah | Solusi |
|------|---------|--------|
| `Dashboard.php` | `where('mahasiswa_id', $userId)` hardcode, `where('user_id', $userId)` pada Reminder | Ubah ke `when(!$isSuperAdmin, ...)` pattern |
| `Nilai.php` | `where('mahasiswa_id', $userId)` hardcode di mount() dan showDetailNilai() | Ubah ke `when(!$isSuperAdmin, ...)` pattern |
| `DaftarRevisi.php` | `whereHas('pendaftaran', fn => where('mahasiswa_id', auth()->id()))` | Ubah ke `when(!$isSuperAdmin, ...)` conditonal di loadRevisis() |

### Lokasi: `app/Livewire/Dosen/`

| File | Masalah | Solusi |
|------|---------|--------|
| `BerikanNilai.php` | `where('dosen_id', $dosenId)` hardcode | Super Admin bisa bypass filter dosen_id, lihat semua ujian |
| `BerikanRevisi.php` | `where('dosen_id', auth()->id())` | Super Admin bypass |
| `DaftarRevisi.php` | `where('dosen_id', $dosenId)` dan `byDosen()` scope | Super Admin bypass |
| `JadwalMenguji.php` | `where('dosen_id', $dosenId)` hardcode | Super Admin bypass |
| `InputNilaiSistem.php` | `where('dosen_id', auth()->id())` | Super Admin bypass |
| `UploadNilaiBerkas.php` | `where('dosen_id', auth()->id())` | Super Admin bypass |

### Lokasi: `app/Livewire/Panitia/`, `app/Livewire/Kajur/`, `app/Livewire/Sekjur/`

| File | Masalah | Solusi |
|------|---------|--------|
| `VerifikasiBerkas.php` | `PermissionService::jurusanScope()` membatasi jurusan | Super Admin bypass jurusanScope (return null) |
| `JadwalUjians.php` | `PermissionService::jurusanScope()` + `getJurusanId()` | Super Admin bypass |
| `KelolaNilaiBerkas.php` | `PermissionService::jurusanScope()` di whereHas | Super Admin bypass |
| `VerifikasiSeminarProposal.php` | `PermissionService::jurusanScope()` | Super Admin bypass |
| `VerifikasiSeminarHasil.php` | (extends VerifikasiSeminarProposal) | Sama |
| `VerifikasiSidangSkripsi.php` | (extends VerifikasiSeminarProposal) | Sama |
| `PengujiIndex.php` | `PermissionService::jurusanScope()` | Super Admin bypass |

### Perubahan pada `PermissionService.php`

Tambahkan method `jurusanScopeForSuperAdmin()` atau ubah `jurusanScope()` dan `getJurusanId()` agar menerima parameter `$user` sehingga:
- Jika `$user->hasRole('super_admin')`, return `null` (tanpa filter jurusan)
- Jika tidak, return filter jurusan seperti biasa

---

## 2. Penambahan Modal Detail

### Daftar halaman yang perlu ditambahkan modal detail:

| Modul | Halaman | Komponen yang Diubah | View yang Diubah |
|-------|---------|---------------------|------------------|
| **Pendaftaran Index** | `/mahasiswa/pendaftaran` | `Mahasiswa\PendaftaranIndex.php` | `mahasiswa/pendaftaran-index.blade.php` |
| **Verifikasi Proposal (Kajur)** | `/kajur/verifikasi/seminar-proposal` | `Kajur\VerifikasiSeminarProposal.php` | `kajur/verifikasi-index.blade.php` |
| **Verifikasi Hasil (Kajur)** | `/kajur/verifikasi/seminar-hasil` | `Kajur\VerifikasiSeminarHasil.php` | `kajur/verifikasi-index.blade.php` |
| **Verifikasi Skripsi (Kajur)** | `/kajur/verifikasi/sidang-skripsi` | `Kajur\VerifikasiSidangSkripsi.php` | `kajur/verifikasi-index.blade.php` |
| **Dosen Nilai** | `/dosen/nilai` | `Dosen\BerikanNilai.php` | `dosen/berikan-nilai.blade.php` |
| **Panitia Penjadwalan** | `/panitia/penjadwalan/jadwal` | `Panitia\Penjadwalan\JadwalUjians.php` | `panitia/penjadwalan/jadwal-ujians.blade.php` |
| **Panitia Verifikasi** | `/panitia/verifikasi/berkas` | `Panitia\Verifikasi\VerifikasiBerkas.php` | `panitia/verifikasi/verifikasi-berkas.blade.php` |
| **Sekjur Penguji Index** | `/sekjur/data-master/penguji` | `Sekjur\PengujiIndex.php` | `sekjur/penguji-index.blade.php` |

### Spesifikasi Modal Detail

Setiap modal harus menampilkan informasi berikut:
1. **Judul Penelitian** (lengkap)
2. **Identitas Mahasiswa**: Nama, NIM, Jurusan/Prodi, Email, No HP
3. **Status Pendaftaran** dengan badge warna
4. **Dosen Pembimbing 1 & 2** (Nama, NIP)
5. **Dosen Penguji** (jika sudah ditentukan)
6. **Bidang Keahlian** (dalam bentuk tag/chip)
7. **Berkas yang diupload**: Link untuk preview/download (File Proposal, KRS, Transkrip, Bukti Bimbingan, dll)
8. **Jadwal Ujian** (jika sudah dijadwalkan): Tanggal, Sesi, Ruangan
9. **Nilai** (jika sudah selesai): Nilai total dan Grade

Implementasi:
- Gunakan `$showDetail` boolean + `$selectedPendaftaran` property di komponen
- Method `showDetail($id)` dan `closeDetail()`
- Tampilkan sebagai modal/card _full-width_ yang menggantikan list, atau modal _overlay_ menggunakan Livewire `wire:click`

---

## 3. Refactoring Blade Views

### Aturan Umum:

1. **Setiap view yang menampilkan list data mahasiswa** harus menerima variabel `$isSuperAdmin` dari komponen.
2. **Jika `$isSuperAdmin` true**, tampilkan kolom/badge tambahan:
   - Nama Mahasiswa
   - NIM
   - Jurusan / Prodi
3. **Empty state** dibedakan:
   - Super Admin: "Belum ada data pendaftaran dari mahasiswa."
   - Mahasiswa: "Anda belum mendaftarkan ujian apapun."
4. **Tombol aksi** (Edit/Hapus/Detail) harus tetap terbuka untuk Super Admin meskipun status bukan 'draft'.
5. **Super Admin badge** atau indikator di header halaman.

### Daftar View yang Diubah:

| View | Perubahan |
|------|-----------|
| `mahasiswa/pendaftaran-index.blade.php` | ✅ **Sudah** memenuhi syarat (sudah ada `$isSuperAdmin`, mahasiswa info, dynamic actions) |
| `mahasiswa/dashboard.blade.php` | Tambah `$isSuperAdmin`, tampilkan nama mahasiswa di setiap kartu pendaftaran |
| `mahasiswa/nilai.blade.php` | Tambah `$isSuperAdmin`, tampilkan identitas mahasiswa |
| `mahasiswa/jadwal-ujian.blade.php` | ✅ **Sudah** memenuhi syarat |
| `mahasiswa/daftar-revisi.blade.php` | Tambah `$isSuperAdmin`, tampilkan identitas mahasiswa |
| `dosen/berikan-nilai.blade.php` | Tambah `$isSuperAdmin`, sesuaikan tampilan |
| `dosen/daftar-revisi.blade.php` | Tambah `$isSuperAdmin`, sesuaikan tampilan |
| `dosen/jadwal-menguji.blade.php` | Tambah `$isSuperAdmin`, tampilkan identitas mahasiswa |
| `kajur/verifikasi-index.blade.php` | Tambah `$isSuperAdmin + modal detail`, tampilkan identitas mahasiswa lebih lengkap |
| `panitia/verifikasi/verifikasi-berkas.blade.php` | ✅ **Sudah** ada modal detail yang baik, hanya perlu `$isSuperAdmin` untuk bypass jurusan |
| `panitia/penjadwalan/jadwal-ujians.blade.php` | ✅ **Sudah** ada modal detail, hanya perlu `$isSuperAdmin` |
| `panitia/administrasi/kelola-nilai-berkas.blade.php` | Tambah `$isSuperAdmin`, sesuaikan tampilan |
| `sekjur/penguji-index.blade.php` | Tambah `$isSuperAdmin + modal detail` |
| `sekjur/generate-penguji.blade.php` | Tambah `$isSuperAdmin` |

---

## 4. Refactoring Aksi Edit/Hapus (Delete)

Pastikan semua method `deletePendaftaran()` dan sejenisnya mengikuti pattern:

```
if ($isSuperAdmin) {
    $model = Model::findOrFail($id); // tanpa filter
} else {
    $model = Model::where('mahasiswa_id', $user->id)->findOrFail($id);
}
```

Atau versi ringkas:

```
$model = Model::when(!$isSuperAdmin, fn($q) => $q->where('mahasiswa_id', $user->id))
    ->findOrFail($id);
```

Super Admin dapat menghapus data **tanpa pengecekan status** (draft/revisi), sedangkan mahasiswa biasa hanya bisa menghapus data berstatus 'draft'.

---

## 5. Prioritas Eksekusi

### Fase 1 — Core (High Priority)
1. `Mahasiswa/Dashboard.php` — super_admin bypass
2. `Mahasiswa/Nilai.php` — super_admin bypass
3. `Mahasiswa/DaftarRevisi.php` — super_admin bypass
4. `PermissionService.php` — fix `jurusanScope()` & `getJurusanId()` untuk super_admin
5. `Panitia/Verifikasi/VerifikasiBerkas.php` — super_admin bypass jurusan
6. `Panitia/Penjadwalan/JadwalUjians.php` — super_admin bypass jurusan
7. `Kajur/VerifikasiSeminarProposal.php` (dan turunannya) — super_admin bypass jurusan
8. `Sekjur/PengujiIndex.php` — super_admin bypass jurusan
9. `Panitia/Administrasi/KelolaNilaiBerkas.php` — super_admin bypass jurusan

### Fase 2 — Dosen & View (Medium Priority)
10. `Dosen/BerikanNilai.php` — super_admin bypass
11. `Dosen/BerikanRevisi.php` — super_admin bypass
12. `Dosen/DaftarRevisi.php` — super_admin bypass
13. `Dosen/JadwalMenguji.php` — super_admin bypass
14. `Dosen/InputNilaiSistem.php` — super_admin bypass
15. `Dosen/UploadNilaiBerkas.php` — super_admin bypass

### Fase 3 — Modal Detail & Blade Views (Medium Priority)
16. Tambah modal detail di Kajur verifikasi (`verifikasi-index.blade.php`)
17. Tambah modal detail di Sekjur penguji index
18. Tambah modal detail di Dosen nilai
19. Update semua blade views sesuai Aturan Antarmuka

### Fase 4 — Testing & Validasi (Low Priority)
20. Uji coba akses Super Admin di setiap fitur
21. Uji coba akses Mahasiswa (hanya data sendiri)
22. Uji coba bypass jurusanScope
23. Uji coba modal detail

---

## Catatan Teknis

- **Framework**: Livewire 4 + Laravel 13
- **Pattern**: Gunakan `Auth::user()->hasRole('super_admin')` + `Query Builder when()` untuk conditional filtering
- **Eager Loading**: Selalu gunakan `with(['mahasiswa', 'mahasiswa.jurusan', 'mahasiswa.prodi'])` pada query list/index
- **Modal Detail**: Implementasi bisa menggunakan `$showDetail` state + render kondisional (bukan modal JS overlay, tapi inline card yang menggantikan list — konsisten dengan pattern yang sudah ada di `VerifikasiBerkas`)
- **Pengiriman State**: Selalu kirimkan `$isSuperAdmin` dari komponen ke view melalui parameter `render()`
- **Tidak membuat Policy file baru** — gunakan Gate::before yang sudah ada di `AppServiceProvider`
