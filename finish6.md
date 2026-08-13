# Feature Request: Filter Dosen Penguji & Pembimbing pada Penjadwalan Ujian

## 📌 Context & Problem Statement
Pada modul Panitia Penjadwalan (khususnya pada halaman `JadwalUjians`), panitia mengalami kendala saat menentukan jadwal ujian secara *batch*. Sulit untuk mengetahui dan mengelompokkan mahasiswa yang memiliki Dosen Penguji 1, Dosen Penguji 2, Dosen Pembimbing 1, atau Dosen Pembimbing 2 yang sama atau tertentu.

Untuk mempermudah koordinasi dan eksekusi penjadwalan *batch*, diperlukan fitur **Filter Dosen** yang memungkinkan panitia menyaring daftar pendaftaran berdasarkan kombinasi dosen penguji dan pembimbing.

---

## 🛠 Tech Stack Target
- **Framework Backend:** Laravel 13
- **Frontend / Reactive UI:** Livewire 4

---

## 🎯 High-Level Implementation Tasks

### 1. Update Component State & Lifecycle (`JadwalUjians.php`)
- **Properti Filter Baru:** Tambahkan properti publik untuk menyimpan state filter dosen:
  - `$penguji1Filter`
  - `$penguji2Filter`
  - `$pembimbing1Filter`
  - `$pembimbing2Filter`
- **URL Query Sync:** Gunakan atribut `#[Url]` pada properti filter agar state tersimpan di query URL browser dan dapat di-share/bookmark.
- **Opsi Dosen:** Sediakan daftar opsi dosen (list dosen aktif) untuk di-render pada UI select dropdown.
- **Reset Pagination:** Pastikan ketika filter dosen berubah (`updated` hook), halaman pagination otomatis di-reset (`resetPage()`).

### 2. Query Filtering Logic
- Update logika pembentukan query pendaftaran siap uji (pada method list/computed property):
  - Filter berdasarkan `penguji_1_id` / relation penguji 1 (jika diisi).
  - Filter berdasarkan `penguji_2_id` / relation penguji 2 (jika diisi).
  - Filter berdasarkan `pembimbing_1_id` / relation pembimbing 1 (jika diisi).
  - Filter berdasarkan `pembimbing_2_id` / relation pembimbing 2 (jika diisi).
- Gunakan kondisi `->when(...)` pada Eloquent query builder agar filter bersifat opsional dan fleksibel.

### 3. Update UI View (`jadwal-ujians.blade.php`)
- Tambahkan section/grid control filter dosen pada bagian filter utama (sejajar atau di bawah filter pencarian/jenis ujian).
- Gunakan komponen `<select>` dengan binding `wire:model.live` untuk tiap role dosen:
  - Select Dosen Penguji 1
  - Select Dosen Penguji 2
  - Select Dosen Pembimbing 1
  - Select Dosen Pembimbing 2
- Tambahkan tombol **Reset Filter** untuk mengosongkan semua pilihan filter dosen secara cepat.

---

## ✅ Acceptance Criteria
1. Panitia dapat menyaring daftar mahasiswa yang siap dijadwalkan berdasarkan salah satu atau kombinasi dari Dosen Penguji 1, 2 dan Dosen Pembimbing 1, 2.
2. Daftar pendaftaran dan fitur **Select All (Batch)** memperbarui data secara real-time mengikuti filter yang aktif.
3. State filter tetap terjaga pada URL saat browser di-refresh.
4. Performa query tetap optimal (menggunakan `when()` dan indexing relation ID jika diperlukan).
