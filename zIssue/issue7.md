# Issue: Perbaikan Daftar Revisi Mahasiswa & Penjadwalan

## Ringkasan

Perencanaan high-level untuk memperbaiki dua area utama:
1. **Mahasiswa > Revisi** — daftar revisi seharusnya tidak menampilkan revisi dari penguji
2. **Penjadwalan** — tambahkan informasi dosen pembimbing pada bagian siap dijadwalkan, terjadwal, dan selesai, serta tambahkan fitur lihat detail

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Laravel, Livewire, Alpine.js, dan TailwindCSS yang relevan.

---

## Konteks Sistem

### Mahasiswa > Revisi
- Route: `mahasiswa.revisi` → `App\Livewire\Mahasiswa\DaftarRevisi`
- View: `resources/views/livewire/mahasiswa/daftar-revisi.blade.php`
- Saat ini menampilkan semua revisi (dari pembimbing dan penguji)
- Harusnya hanya menampilkan revisi dari dosen pembimbing

### Penjadwalan
- Route: `penjadwalan.index` → `App\Livewire\Penjadwalan\Index`
- View: `resources/views/livewire/penjadwalan/index.blade.php`
- Terdapat tiga bagian: siap dijadwalkan, terjadwal, selesai
- Saat ini tidak menampilkan dosen pembimbing, menyebabkan potensi kesalahan saat pemilihan batch jadwal
- Tidak ada fitur lihat detail untuk informasi lebih lanjut

---

## Scope High-Level

### 1. Filter Revisi Mahasiswa (Hanya dari Pembimbing)

- Ubah query di `DaftarRevisi.php` untuk memfilter revisi berdasarkan role dosen
- Pastikan hanya revisi dari dosen pembimbing yang ditampilkan di tabel
- Relasi yang mungkin perlu dicek: revisi → dosen → role (pembimbing vs penguji)
- Update view jika perlu untuk menyesuaikan dengan data yang sudah difilter

### 2. Tambahkan Dosen Pembimbing di Penjadwalan

- Update data yang diambil di `Index.php` untuk menyertakan informasi dosen pembimbing
- Tampilkan nama dosen pembimbing di ketiga bagian (siap dijadwalkan, terjadwal, selesai)
- Pastikan tampilan rapi dan informatif (bisa dalam card atau tabel sesuai existing design)
- Informasi ini penting untuk menghindari kesalahan saat memilih batch jadwal

### 3. Tambahkan Fitur Lihat Detail di Penjadwalan

- Tambahkan tombol/detail trigger di setiap item (siap dijadwalkan, terjadwal, selesai)
- Buat modal atau detail view yang menampilkan informasi lengkap tentang item tersebut
- Informasi yang sebaiknya ditampilkan: data mahasiswa, judul, dosen pembimbing, status, dan informasi relevan lainnya
- Pastikan UX konsisten dengan pattern lihat detail yang sudah ada di sistem

---

## Acceptance Criteria

### Mahasiswa > Revisi
- Daftar revisi hanya menampilkan revisi dari dosen pembimbing
- Revisi dari penguji tidak muncul di halaman mahasiswa
- Filter berfungsi untuk semua status revisi
- Tidak ada data revisi yang hilang (semua revisi pembimbing tetap tampil)

### Penjadwalan
- Dosen pembimbing ditampilkan di semua bagian (siap dijadwalkan, terjadwal, selesai)
- Informasi dosen pembimbing jelas dan mudah dibaca
- Tombol/detail "Lihat Detail" tersedia untuk setiap item
- Modal/detail view menampilkan informasi yang relevan dan lengkap
- Tidak ada kesalahan saat memilih batch jadwal karena informasi pembimbing sudah jelas

---

## Lokasi File Utama

| Area | File |
|------|------|
| Mahasiswa Revisi Component | `app/Livewire/Mahasiswa/DaftarRevisi.php` |
| Mahasiswa Revisi View | `resources/views/livewire/mahasiswa/daftar-revisi.blade.php` |
| Penjadwalan Component | `app/Livewire/Penjadwalan/Index.php` |
| Penjadwalan View | `resources/views/livewire/penjadwalan/index.blade.php` |
| Model terkait | `App\Models\Revisi`, `App\Models\Pendaftaran`, `App\Models\Dosen` |

---

## Urutan Implementasi (Disarankan)

1. **Filter revisi mahasiswa** — perbaiki query untuk hanya menampilkan revisi dari pembimbing
2. **Tambahkan dosen pembimbing di penjadwalan** — update data dan view untuk menampilkan pembimbing
3. **Tambahkan fitur lihat detail** — implementasikan tombol detail dan modal view
4. **Test manual end-to-end** — verifikasi kedua fitur berfungsi sesuai requirement

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Livewire query filtering dan Alpine.js modal pattern
- Cek relasi antara model Revisi, Dosen, dan Pendaftaran untuk memahami cara memfilter berdasarkan role dosen
- Ikuti pattern UI yang sudah ada di sistem untuk konsistensi (modal, card layout, typography)
- Test dengan skenario: mahasiswa dengan revisi dari pembimbing saja, mahasiswa dengan revisi dari pembimbing dan penguji
- Untuk penjadwalan, test dengan data yang memiliki dosen pembimbing berbeda-beda untuk memastikan informasi tampil dengan benar
- Pastikan tidak ada regresi pada fitur yang sudah ada di kedua halaman tersebut
