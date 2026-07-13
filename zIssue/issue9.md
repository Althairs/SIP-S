# Issue: Fitur Upload Berkas Revisi & Perbaikan Tampilan Detail Revisi

## Ringkasan

Perencanaan high-level untuk mengembangkan dan memperbaiki dua area utama:
1. **Mahasiswa > Revisi** - tambahkan fitur upload berkas revisi agar dosen penguji dan pembimbing bisa review perbaikan
2. **Mahasiswa > Revisi > Detail** - perbaiki tampilan data yang tidak menampilkan dengan benar

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Laravel, Livewire, Alpine.js, dan TailwindCSS yang relevan.

---

## Konteks Sistem

### Mahasiswa > Revisi
- Route: `mahasiswa.revisi` → `App\Livewire\Mahasiswa\DaftarRevisi`
- View: `resources/views/livewire/mahasiswa/daftar-revisi.blade.php`
- Saat ini mahasiswa bisa melihat daftar revisi dari dosen
- Belum ada fitur upload berkas sebagai respon terhadap revisi
- Dosen perlu bisa melihat dan review berkas yang diupload mahasiswa

### Mahasiswa > Revisi > Detail
- Route: `mahasiswa.revisi.detail` → `App\Livewire\Mahasiswa\DetailRevisi`
- View: `resources/views/livewire/mahasiswa/detail-revisi.blade.php`
- Data di halaman detail tidak tampil dengan benar
- Perlu investigasi data apa yang tidak tampil dan perbaikan logic

---

## Scope High-Level

### 1. Tambahkan Fitur Upload Berkas Revisi

**Backend:**
- Tambahkan kolom untuk menyimpan path/nama file berkas revisi di tabel revisi atau tabel terpisah
- Buat method untuk handle file upload di Livewire component
- Implementasi validasi file (tipe, ukuran maksimal)
- Simpan file di storage yang sesuai (misal: storage/app/public/revisi)
- Update logika approval: jika dosen setujui, status berubah; jika minta revisi, mahasiswa harus upload berkas baru

**Frontend:**
- Tambahkan tombol/form upload berkas di halaman daftar revisi atau detail revisi
- Tampilkan daftar berkas yang sudah diupload untuk setiap revisi
- Berikan indikator visual untuk status berkas (pending, reviewed, approved)
- Pastikan UI konsisten dengan pattern upload yang sudah ada di sistem

**Flow:**
- Dosen memberikan revisi → mahasiswa upload berkas perbaikan → dosen review berkas → dosen setujui atau minta revisi lagi (upload berkas baru)

### 2. Perbaiki Tampilan Detail Revisi

**Investigasi:**
- Identifikasi data apa yang tidak tampil dengan benar di halaman detail
- Cek apakah masalah di query data, passing data ke view, atau rendering view
- Review relasi antar model yang digunakan di halaman detail

**Perbaikan:**
- Perbaiki query atau logic data fetching di component
- Pastikan semua data yang diperlukan tersedia dan di-pass ke view
- Perbaiki rendering di blade view jika ada issue dengan conditional atau looping
- Test dengan berbagai skenario data untuk memastikan tampilan konsisten

---

## Acceptance Criteria

### Fitur Upload Berkas Revisi
- Mahasiswa bisa upload berkas sebagai respon terhadap revisi dari dosen
- Dosen bisa melihat dan download berkas yang diupload mahasiswa
- Dosen bisa memberikan approval atau minta revisi baru setelah review berkas
- Jika minta revisi baru, mahasiswa harus upload berkas baru (bukan revisi yang sama)
- File upload memiliki validasi tipe dan ukuran yang sesuai
- UI upload intuitif dan konsisten dengan sistem
- Status revisi mencerminkan apakah berkas sudah diupload, direview, atau disetujui

### Perbaikan Detail Revisi
- Halaman detail menampilkan semua data yang diperlukan dengan benar
- Tidak ada data yang kosong atau tidak tampil seharusnya
- Informasi ditampilkan dengan layout yang jelas dan mudah dibaca
- Berbagai skenario data (revisi dari pembimbing, penguji, status berbeda) tampil dengan benar

---

## Lokasi File Utama

| Area | File |
|------|------|
| Mahasiswa Revisi Component | `app/Livewire/Mahasiswa/DaftarRevisi.php` |
| Mahasiswa Revisi View | `resources/views/livewire/mahasiswa/daftar-revisi.blade.php` |
| Detail Revisi Component | `app/Livewire/Mahasiswa/DetailRevisi.php` |
| Detail Revisi View | `resources/views/livewire/mahasiswa/detail-revisi.blade.php` |
| Model Revisi | `App\Models\Revisi` |
| Migration Revisi | `database/migrations/xxxx_xx_xx_create_revisi_table.php` |

---

## Urutan Implementasi (Disarankan)

1. **Investigasi dan perbaiki detail revisi** - selesaikan issue tampilan data terlebih dahulu
2. **Desain database untuk upload berkas** - tentukan struktur tabel untuk menyimpan file
3. **Implementasi upload backend** - buat method upload, validasi, dan storage
4. **Implementasi upload frontend** - tambah UI upload dan tampilan daftar file
5. **Implementasi flow approval** - logika review dan approval berdasarkan berkas
6. **Test end-to-end** - verifikasi flow dari revisi dosen → upload → review → approval/revisi baru

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Livewire file upload, Laravel Storage, dan Alpine.js UI pattern
- Untuk upload berkas, pertimbangkan apakah perlu tabel terpisah atau cukup tambah kolom di tabel revisi
- Pastikan file storage aman dan tidak bisa diakses langsung tanpa auth
- Gunakan naming convention yang konsisten untuk file yang diupload
- Untuk perbaikan detail revisi, debug dengan dd() atau logging untuk melihat data apa yang tersedia
- Test flow dengan skenario: revisi tunggal, multiple revisi, revisi dari pembimbing vs penguji
- Pastikan UX upload smooth (progress indicator, error handling, success message)
- Pertimbangkan untuk menampilkan history upload jika ada multiple revisi untuk satu item revisi
