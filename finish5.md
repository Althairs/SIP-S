# [Feature Planning] Implementation of Bulk Action & Multi-Select (Livewire 4 & Laravel 13)

## 1. Overview & Objective
Dokumen ini berisi panduan perencanaan tingkat tinggi (*high-level planning*) untuk mengimplementasikan fitur **Bulk Action / Multi-Select** pada tabel-tabel data di aplikasi. 

Fitur ini bertujuan untuk mengatasi inefisiensi pengoperasian data ketika pengguna harus menghapus atau merubah ribuan baris data satu per satu (1/1). Dengan fitur ini, pengguna dapat memilih (*select*) beberapa atau seluruh baris data sekaligus untuk dilakukan aksi massal (seperti *Bulk Delete* atau *Bulk Edit*).

---

## 2. Cakupan & Kriteria Pengecualian (Scope & Exclusion Criteria)

### A. Modul yang Wajib Menggunakan Bulk Action:
- Modul/tabel dengan volume data sedang hingga besar (ratusan hingga ribuan data), misalnya: data pendaftaran, data transkrip, log aktivitas, data mahasiswa, dll.

### B. Kriteria Pengecualian (TIDAK Diimplementasikan):
- **Tabel Master Berkapasitas Kecil**: Modul/tabel master yang jumlah entrinya umumnya kurang dari 10 data (contoh: **Jabatan Fungsional**, Master Role, Status Referensi ringkas).
- Modul-modul kecil ini cukup mempertahankan tombol aksi individual (*delete/edit 1-per-1*) untuk kesederhanaan antarmuka dan keamanan data.

---

## 3. Arsitektur & Strategi Solusi (High-Level Strategy)

### A. Framework & Core Tech
- **Backend Framework**: Laravel 13
- **Frontend/Reactive Component**: Livewire 4
- **Dokumentasi & Standar**: Mengacu pada standar Livewire 4 & Laravel 13 (via Context7).

### B. State Management (Livewire 4)
- **Selection State**: Menggunakan properti reactive (seperti `$selectedIds = []`) untuk menyimpan daftar ID data yang dipilih.
- **Select-All Strategy**:
  - *Select All Page*: Memilih semua data yang tampil pada halaman pagination saat ini.
  - *Select All Matching Query* (Opsional untuk data > 1.000): Opsi memilih seluruh baris hasil pencarian lintas halaman (*cross-page selection*) tanpa memuat seluruh ID ke memori browser.

### C. Database & Security Operations
- **Batch Processing**: Operasi penghapusan atau pembaruan massal dilakukan di tingkat database menggunakan `whereIn()` atau batch chunking dalam `DB::transaction()`.
- **Authorization**: Memastikan setiap pemanggilan metode aksi massal melewati otorisasi (*Policy / Gate check*) untuk mencegah pengubahan data tanpa hak akses.
- **Audit & Safety**: Penggunaan konfirmasi ulang (modal dialog) sebelum eksekusi aksi destruktif (*mass delete*).

---

## 4. Alur UI/UX (User Experience Flow)

1. **Checkbox Master (Header Tabel)**:
   - Memilih/membatalkan pilihan semua baris data di halaman aktif.
2. **Checkbox Row (Setiap Baris Data)**:
   - Memilih baris data individual.
3. **Floating / Top Bulk Action Toolbar**:
   - Muncul secara dinamis ketika minimal ada 1 data yang dipilih (`count($selectedIds) > 0`).
   - Menampilkan indikator jumlah data terpilih (contoh: *"12 data dipilih"*).
   - Menyediakan tombol aksi massal: **Hapus Terpilih (*Bulk Delete*)** dan/atau **Ubah Status Terpilih (*Bulk Edit*)**.
4. **Loading & Feedback State**:
   - Penggunaan indikator *loading* (`wire:loading`) saat proses batch berjalan agar UX tetap responsif.
   - Pesan notifikasi sukses (*flash notification*) setelah operasi selesai, diikuti dengan pembatalan (*reset*) pilihan checklist.

---

## 5. Instruksi Langkah Implementasi untuk Programmer / Model Sub-Agent

Berikut adalah tahapan kerja tingkat tinggi yang dapat diikuti oleh programmer atau model pengembang:

1. **Audit Komponen Tabel Existing**:
   - Identifikasi semua komponen tabel Livewire di proyek.
   - Tandai mana tabel berkapasitas besar (terapkan bulk action) dan mana tabel kecil seperti *Jabatan Fungsional* atau data < 10 record (abaikan).

2. **Pembuatan Trait Reusable (Livewire 4)**:
   - Buat Trait (misal: `WithBulkActions`) untuk menampung logika umum:
     - Handling properti `$selectedIds` dan `$selectAll`.
     - Method `updatedSelectAll()`, `resetSelection()`.
     - Utility query helper untuk eksekusi batch.

3. **Integrasi ke Component Livewire**:
   - Attach Trait `WithBulkActions` pada komponen Livewire yang membutuhkan.
   - Tambahkan method aksi massal (contoh: `deleteSelected()`) yang dibungkus `DB::transaction()`.

4. **Penyesuaian Template Blade**:
   - Tambahkan elemen checkbox pada `<th>` dan `<td>`.
   - Tambahkan elemen Toolbar Aksi Massal di atas tabel dengan pengondisian visual saat data terpilih.
   - Tambahkan modal konfirmasi aksi massal.

5. **Pengujian & Verifikasi**:
   - Uji pemulihan state saat perpindahan halaman (*pagination*).
   - Uji performa pemrosesan batch pada dataset besar.
   - Pastikan tabel master kecil (< 10 data) tidak terdampak.
