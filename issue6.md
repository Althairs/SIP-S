# Issue: Perbaikan Tab Jadwal Ujian Mahasiswa

## Ringkasan

Perencanaan high-level untuk memperbaiki halaman **Mahasiswa > Jadwal Ujian**, khususnya tab **Akan Datang** dan **Riwayat** yang saat ini tidak berfungsi sempurna.

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Laravel, Livewire, Alpine.js, dan TailwindCSS yang relevan.

---

## Konteks Sistem

- Route: `mahasiswa.jadwal` → `App\Livewire\Mahasiswa\JadwalUjian`
- View: `resources/views/livewire/mahasiswa/jadwal-ujian.blade.php`
- Halaman menampilkan dua tab:
  - **Akan Datang** — ujian yang belum lewat (`status = dijadwalkan`, tanggal ≥ sekarang)
  - **Riwayat** — ujian selesai atau ujian terjadwal yang sudah lewat
- Data diambil dari model `Pendaftaran` milik mahasiswa yang login

---

## Masalah

Tab **Akan Datang** dan **Riwayat** tidak bekerja sempurna — kemungkinan besar tab tidak bisa berganti dengan benar, sehingga konten tidak tampil sesuai pilihan user.

Indikasi dari kode yang ada:
- Navigasi tab dan konten tab menggunakan **scope Alpine.js terpisah** (`x-data` duplikat), sehingga state tab tidak tersinkron
- Pola serupa pernah terjadi di halaman revisi dosen dan sudah diperbaiki dengan menyatukan `x-data` parent

Selain bug UI tab, perlu dicek juga:
- Apakah query pemisahan data **Akan Datang** vs **Riwayat** sudah sesuai alur status pendaftaran
- Apakah badge/status di tabel riwayat akurat (mis. ujian lewat tapi belum `selesai` masih ditampilkan sebagai "Selesai")

---

## Scope High-Level

### 1. Perbaikan Tab Navigation (UI)

- Satukan state tab Alpine.js ke satu parent wrapper
- Pastikan klik tab **Akan Datang** / **Riwayat** menampilkan/menyembunyikan konten yang benar
- Tambahkan `x-cloak` jika perlu agar tidak ada flash konten saat load
- Ikuti pola tab yang sudah berhasil diperbaiki di halaman lain (mis. daftar revisi dosen)

### 2. Verifikasi Query Data

- Review logika query di `JadwalUjian.php`:
  - **Akan Datang:** pendaftaran `dijadwalkan` dengan `tanggal_ujian >= now()`
  - **Riwayat:** pendaftaran `selesai` ATAU `dijadwalkan` dengan `tanggal_ujian < now()`
- Pastikan tidak ada data yang hilang atau muncul di tab yang salah
- Sesuaikan dengan alur status pendaftaran yang berlaku di sistem (`pending` → ... → `dijadwalkan` → `selesai`)

### 3. Perbaikan Tampilan Riwayat

- Tampilkan status yang akurat per item (bukan hardcode "Selesai" untuk semua baris)
- Pastikan nilai/grade ditampilkan jika tersedia
- Tombol **Lihat Detail** dan modal detail tetap berfungsi di kedua tab

---

## Acceptance Criteria

- Tab **Akan Datang** dan **Riwayat** dapat dibergantikan dengan benar
- Konten yang tampil sesuai tab yang aktif
- Data ujian mendatang hanya muncul di tab Akan Datang
- Data ujian selesai/lewat hanya muncul di tab Riwayat
- Badge status di riwayat mencerminkan kondisi data sebenarnya
- Modal detail ujian tetap berfungsi dari kedua tab
- Tidak ada regresi pada tampilan card jadwal dan tabel riwayat

---

## Lokasi File Utama

| Area | File |
|------|------|
| Livewire component | `app/Livewire/Mahasiswa/JadwalUjian.php` |
| View | `resources/views/livewire/mahasiswa/jadwal-ujian.blade.php` |
| Route | `routes/web.php` → `mahasiswa.jadwal` |
| Model terkait | `App\Models\Pendaftaran` |

---

## Urutan Implementasi (Disarankan)

1. **Perbaiki tab navigation** — fix scope Alpine.js (quick win, root cause UI)
2. **Verifikasi & sesuaikan query** — pastikan pemisahan data Akan Datang vs Riwayat benar
3. **Perbaiki tampilan status riwayat** — badge/status dinamis
4. **Test manual end-to-end** — login sebagai mahasiswa dengan berbagai status pendaftaran

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Alpine.js tab pattern dan Livewire + Alpine integration
- Cek bug serupa yang sudah diperbaiki di `resources/views/livewire/dosen/daftar-revisi.blade.php` sebagai referensi pola tab yang benar
- Test dengan skenario: mahasiswa tanpa jadwal, dengan jadwal mendatang, dengan ujian lewat belum selesai, dan dengan ujian selesai
- Tambahkan feature test jika memungkinkan untuk mencegah regresi tab navigation
