# Dokumen Perencanaan: Riwayat Revisi & Validasi Pendaftaran Ujian

## Gambaran Umum

Dokumen ini berisi perencanaan tingkat tinggi untuk memperbaiki permasalahan pada tampilan riwayat revisi serta menambahkan validasi pendaftaran berdasarkan jenis ujian.

---

# Permasalahan 1: Revisi yang Disetujui Tidak Muncul pada Riwayat

## Deskripsi Permasalahan

Ketika dosen menyetujui revisi melalui halaman **"Berikan Revisi"**, revisi yang telah disetujui tidak muncul pada tab **"Riwayat Revisi"** di halaman **"Daftar Revisi"**.

## Analisis Penyebab

Terdapat ketidakkonsistenan nilai status yang digunakan saat proses persetujuan revisi, yaitu:

- Method `BerikanRevisi::approveRevisi()` mengubah status menjadi `'selesai'`.
- Method `DaftarRevisi::approveRevisi()` mengubah status menjadi `'disetujui'`.

Akibatnya:

- Query pada halaman riwayat kemungkinan hanya mengambil salah satu status.
- Bisa juga terdapat proses penyaringan (filtering) pada query atau Blade View yang menyebabkan revisi dengan status tertentu tidak ditampilkan.

## Solusi Tingkat Tinggi

1. **Standarisasi status persetujuan**
   - Pastikan seluruh proses persetujuan revisi menggunakan nilai status yang sama.
   - Disarankan menggunakan status `'disetujui'` agar konsisten.

2. **Perbarui query riwayat revisi**
   - Sesuaikan query pada `DaftarRevisi::render()` agar menampilkan revisi yang:
     - memiliki `is_approved = true`, atau
     - memiliki status `disetujui` maupun `selesai`.

3. **Lakukan pengujian pada kedua alur persetujuan**
   - Pastikan revisi yang disetujui melalui halaman **Berikan Revisi** maupun **Daftar Revisi** sama-sama muncul pada tab **Riwayat Revisi**.

## File yang Perlu Dimodifikasi

- `app/Livewire/Dosen/BerikanRevisi.php`
  - Method `approveRevisi()`

- `app/Livewire/Dosen/DaftarRevisi.php`
  - Method `render()`
  - dan/atau `approveRevisi()`

- `resources/views/livewire/dosen/daftar-revisi.blade.php`
  - Jika proses filtering dilakukan pada tampilan.

---

# Permasalahan 2: Mencegah Pendaftaran Jenis Ujian yang Sama

## Deskripsi Permasalahan

Mahasiswa masih dapat mendaftar **jenis ujian yang sama** meskipun sebelumnya sudah pernah mengikuti dan menyelesaikan jenis ujian tersebut.

Hal ini harus dicegah agar tidak terjadi duplikasi data.

## Solusi Tingkat Tinggi

1. **Tambahkan validasi sebelum proses pendaftaran**
   - Sebelum data disimpan, sistem harus memeriksa apakah mahasiswa telah memiliki data pendaftaran dengan:
     - `jenis_ujian` yang sama, dan
     - status `'selesai'`.

2. **Tampilkan pesan peringatan**
   - Jika kondisi tersebut terpenuhi, proses pendaftaran dibatalkan dan sistem menampilkan pesan yang mudah dipahami pengguna.

3. **Lokasi implementasi**
   - Validasi ditambahkan pada proses submit formulir pendaftaran, baik di komponen Livewire maupun Form Request yang digunakan.

## Logika Validasi

```php
// Contoh pseudocode

$existingCompleted = Pendaftaran::where('mahasiswa_id', auth()->id())
    ->where('jenis_ujian', $request->jenis_ujian)
    ->where('status', 'selesai')
    ->exists();

if ($existingCompleted) {
    // Tampilkan pesan error
}
```

## File yang Perlu Dimodifikasi

- Komponen Livewire pendaftaran mahasiswa
  - (kemungkinan berada pada `app/Livewire/Mahasiswa/`)

atau

- Form Request yang menangani validasi pendaftaran
  - (kemungkinan berada pada `app/Http/Requests/`)

- Halaman Blade formulir pendaftaran
  - untuk menampilkan pesan peringatan kepada pengguna.

## Pesan yang Ditampilkan

> **"Anda sudah pernah mendaftar dan menyelesaikan ujian jenis [JENIS_UJIAN]. Silakan memilih jenis ujian yang berbeda."**

---

# Permasalahan 3: Integrasi Context7

## Latar Belakang

Implementasi harus mengikuti standar dan pola yang digunakan oleh **Context7**, baik dari sisi struktur kode maupun praktik implementasi yang telah diterapkan pada proyek.

## Langkah Implementasi

- Meninjau pola implementasi Context7 yang sudah ada pada proyek.
- Mengikuti konvensi Context7 saat mengimplementasikan kedua perbaikan di atas.
- Menjaga konsistensi struktur kode, gaya penulisan, dan arsitektur sesuai standar Context7.

---

# Prioritas Implementasi

1. **Prioritas Tinggi**
   - Memperbaiki tampilan **Riwayat Revisi** agar seluruh revisi yang telah disetujui dapat ditampilkan dengan benar.

2. **Prioritas Menengah**
   - Menambahkan validasi agar mahasiswa tidak dapat mendaftar kembali pada jenis ujian yang telah diselesaikan.

3. **Pendukung**
   - Menerapkan pola dan konvensi **Context7** pada seluruh proses implementasi.

---

# Daftar Pengujian

- [ ] Persetujuan revisi melalui halaman **Berikan Revisi** berhasil muncul pada **Riwayat Revisi**.
- [ ] Persetujuan revisi melalui halaman **Daftar Revisi** berhasil muncul pada **Riwayat Revisi**.
- [ ] Mahasiswa tidak dapat mendaftar kembali pada jenis ujian yang sudah berstatus **selesai**.
- [ ] Mahasiswa tetap dapat mendaftar jenis ujian yang berbeda.
- [ ] Seluruh implementasi mengikuti standar dan pola **Context7**.
