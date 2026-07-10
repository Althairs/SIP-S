# Issue: Perbaikan Tampilan Revisi Mahasiswa & Modal Detail Penjadwalan

## Ringkasan

Perencanaan high-level untuk memperbaiki dua area utama:
1. **Mahasiswa > Revisi** - daftar revisi tidak menampilkan revisi dari dosen penguji padahal penguji sudah input revisi
2. **Penjadwalan > Jadwal Ujians** - detail modal layout terlalu kecil dan perlu diperbesar

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Laravel, Livewire, Alpine.js, dan TailwindCSS yang relevan.

---

## Konteks Sistem

### Mahasiswa > Revisi
- Route: `mahasiswa.revisi` → `App\Livewire\Mahasiswa\DaftarRevisi`
- View: `resources/views/livewire/mahasiswa/daftar-revisi.blade.php`
- Saat ini ada filter yang mungkin terlalu ketat sehingga revisi dari penguji tidak tampil
- Revisi bisa diberikan oleh pembimbing (pembimbing_1, pembimbing_2) atau penguji (penguji_1, penguji_2)
- Mahasiswa seharusnya bisa melihat SEMUA revisi yang diberikan oleh dosen-dosen terkait pendaftarannya

### Penjadwalan > Jadwal Ujians
- Route: `panitia.penjadwalan.jadwal` → `App\Livewire\Panitia\Penjadwalan\JadwalUjians`
- View: `resources/views/livewire/panitia/penjadwalan/jadwal-ujians.blade.php`
- Modal detail saat ini menggunakan `max-w-2xl` yang mungkin terlalu kecil untuk menampilkan semua informasi
- Modal menampilkan informasi lengkap: judul, mahasiswa, pembimbing, penguji, bidang keahlian, jadwal, status

---

## Scope High-Level

### 1. Perbaiki Filter Revisi Mahasiswa

- Review logic filter di `DaftarRevisi.php` method `loadRevisis()`
- Pastikan query mengambil SEMUA revisi dari pendaftaran mahasiswa yang login, tanpa memfilter berdasarkan peran pemberi
- Hilangkan atau sesuaikan filter `peran_pemberi` yang mungkin terlalu ketat
- Verifikasi bahwa revisi dari pembimbing DAN penguji keduanya tampil di daftar revisi mahasiswa
- Pastikan tidak ada revisi yang hilang dari tampilan mahasiswa

### 2. Perbesar Layout Modal Detail Penjadwalan

- Ubah ukuran modal detail dari `max-w-2xl` ke ukuran yang lebih besar (misalnya `max-w-4xl` atau `max-w-5xl`)
- Sesuaikan layout internal modal agar informasi tampil lebih lega dan mudah dibaca
- Pertimbangkan untuk menggunakan grid layout yang lebih baik untuk menampilkan informasi pembimbing dan penguji
- Pastikan modal tetap responsif di berbagai ukuran layar
- Optimasi spacing dan padding untuk keterbacaan yang lebih baik

---

## Acceptance Criteria

### Mahasiswa > Revisi
- Daftar revisi menampilkan SEMUA revisi dari dosen pembimbing dan penguji
- Revisi dari penguji tampil ketika penguji sudah menginput revisi
- Tidak ada revisi yang hilang dari tampilan mahasiswa
- Informasi peran pemberi (pembimbing/penguji) tetap ditampilkan untuk clarity
- Mahasiswa bisa melihat dan merespon semua revisi yang diberikan

### Penjadwalan > Modal Detail
- Modal detail menggunakan ukuran yang lebih besar dan lebih lega
- Informasi di dalam modal tampil dengan spacing yang cukup
- Layout modal responsif dan mudah dibaca di berbagai ukuran layar
- Informasi pembimbing dan penguji tampil dengan jelas
- Tidak ada informasi yang terpotong atau sulit dibaca karena layout terlalu kecil

---

## Lokasi File Utama

| Area | File |
|------|------|
| Mahasiswa Revisi Component | `app/Livewire/Mahasiswa/DaftarRevisi.php` |
| Mahasiswa Revisi View | `resources/views/livewire/mahasiswa/daftar-revisi.blade.php` |
| Penjadwalan Component | `app/Livewire/Panitia/Penjadwalan/JadwalUjians.php` |
| Penjadwalan View | `resources/views/livewire/panitia/penjadwalan/jadwal-ujians.blade.php` |
| Model terkait | `App\Models\Revisi`, `App\Models\Pendaftaran` |

---

## Urutan Implementasi (Disarankan)

1. **Perbaiki filter revisi mahasiswa** - review dan sesuaikan query agar semua revisi tampil
2. **Perbesar modal detail** - ubah ukuran modal dan optimasi layout internal
3. **Test manual end-to-end** - verifikasi kedua perbaikan berfungsi sesuai requirement
4. **Update test yang ada** - sesuaikan test revisi flow jika perlu mengikuti perubahan logic

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Livewire query optimization dan TailwindCSS responsive design
- Untuk filter revisi, pastikan untuk memahami alur bisnis: apakah mahasiswa memang perlu melihat revisi dari penguji atau hanya pembimbing
- Untuk modal, pertimbangkan untuk menggunakan `max-w-4xl` atau `max-w-5xl` dan sesuaikan grid layout
- Test dengan skenario: mahasiswa dengan revisi dari pembimbing saja, mahasiswa dengan revisi dari penguji saja, dan mahasiswa dengan revisi dari keduanya
- Pastikan perubahan tidak merusak fitur yang sudah ada di kedua halaman tersebut
