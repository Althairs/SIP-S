# Issue: Perbaikan UI/UX dan Penyediaan Dokumentasi PDF
 
## Ringkasan
 
Dokumentasi perencanaan untuk perbaikan UI/UX dan penyediaan dokumentasi:
1. Perbaikan pesan batch penjadwalan agar tidak hilang otomatis
2. Perbaikan tampilan public/index dengan statistik dari database
3. Konversi panduan pengguna ke file PDF untuk setiap role
4. Update logo di semua sidebar menggunakan logo_ung.png
5. Penggantian emoji dengan icon di seluruh sistem
 
---
 
## 1. Perbaikan Pesan Batch Penjadwalan
 
### Masalah
Saat batch penjadwalan, pesan error/success hilang otomatis sehingga user tidak melihat apa yang salah.
 
### Lokasi File
- Component: `app/Livewire/Panitia/Penjadwalan/JadwalUjians.php`
- View: `resources/views/livewire/panitia/penjadwalan/jadwal-ujians.blade.php`
 
### Solusi High-Level
1. Ubah flash message menjadi persistent notification
2. Tambahkan tombol close (icon silang) untuk menutup pesan secara manual
3. Pesan hanya hilang saat user menekan icon silang atau refresh halaman
 
### Implementasi
- Gunakan Livewire property untuk menyimpan pesan dan status (success/error)
- Tampilkan pesan dengan tombol close di UI
- Hapus auto-hide logic jika ada
- Pastikan pesan tetap terlihat sampai user menutupnya
 
---
 
## 2. Perbaikan Tampilan Public/Index
 
### Masalah
Halaman public/index perlu lebih menarik dan menampilkan statistik dari database.
 
### Lokasi File
- View: `resources/views/welcome.blade.php` atau `resources/views/public/index.blade.php`
 
### Solusi High-Level
1. Redesign halaman public/index dengan layout modern
2. Tampilkan card statistik:
   - Jumlah Mahasiswa
   - Jumlah Dosen
   - Jumlah Jurusan
   - Jumlah Ujian (berdasarkan status)
3. Data diambil dari database secara real-time
 
### Implementasi
- Buat Livewire component untuk public/index jika belum ada
- Query data dari models: User, Jurusan, Pendaftaran
- Tampilkan statistik dalam card dengan desain modern
- Gunakan TailwindCSS untuk styling
- Tambahkan animasi atau visual yang menarik
 
---
 
## 3. Konversi Panduan Pengguna ke PDF
 
### Masalah
Panduan pengguna saat ini dalam format yang perlu diubah ke PDF untuk setiap role.
 
### Lokasi File
- View panduan pengguna saat ini (perlu dicek lokasinya)
- File PDF baru akan disimpan di `public/docs/` atau `storage/app/docs/`
 
### Solusi High-Level
1. Buat file PDF untuk panduan pengguna setiap role:
   - Mahasiswa
   - Dosen
   - Panitia (Verifikasi, Penjadwalan, Administrasi)
   - Kajur
   - Sekjur
2. Ganti link panduan pengguna di UI untuk menampilkan PDF
3. PDF berisi alur penggunaan sistem lengkap untuk role tersebut
 
### Implementasi
- Gunakan library PDF generation (dompdf, snappy, atau laravel-pdf)
- Buat template HTML untuk setiap role
- Generate PDF dari template
- Simpan PDF di folder public/docs
- Update link di sidebar atau menu untuk menampilkan PDF
- Pastikan PDF dapat di-download atau di-view di browser
 
---
 
## 4. Update Logo di Sidebar
 
### Masalah
Logo di sidebar berbeda dengan logo di navbar (navbar menggunakan logo_ung.png).
 
### Lokasi File
- Sidebar files:
  - `resources/views/components/navigation/sidebar-mahasiswa.blade.php`
  - `resources/views/components/navigation/sidebar-dosen.blade.php`
  - `resources/views/components/navigation/sidebar-panitia.blade.php`
  - `resources/views/components/navigation/sidebar-kajur.blade.php`
  - `resources/views/components/navigation/sidebar-sekjur.blade.php`
  - `resources/views/components/navigation/sidebar-admin.blade.php`
 
### Solusi High-Level
1. Ganti logo di semua sidebar dengan logo_ung.png
2. Pastikan konsisten dengan navbar.blade.php
3. Gunakan path yang sama: `{{ asset('images/logo_ung.png') }}`
 
### Implementasi
- Cari semua file sidebar
- Ganti logo tag dengan `<img src="{{ asset('images/logo_ung.png') }}">`
- Pastikan styling (width, height, class) konsisten
- Test di semua role untuk memastikan logo tampil dengan benar
 
---
 
## 5. Penggantian Emoji dengan Icon
 
### Masalah
Penggunaan emoji di UI sebaiknya diganti dengan icon untuk tampilan yang lebih profesional.
 
### Lokasi File
- Semua file view yang menggunakan emoji
- Perlu search di seluruh codebase untuk menemukan emoji
 
### Solusi High-Level
1. Cari semua penggunaan emoji di codebase
2. Ganti emoji dengan icon (menggunakan SVG icon atau library seperti Heroicons/Lucide)
3. Pastikan icon memiliki makna yang sama dengan emoji yang digantikan
 
### Implementasi
- Search emoji di semua .blade.php files
- Identifikasi emoji yang digunakan dan konteksnya
- Ganti dengan SVG icon yang sesuai:
  - Success checkmark → SVG check icon
  - Warning → SVG warning icon
  - Error → SVG X icon
  - Info → SVG info icon
  - dll
- Gunakan icon library yang konsisten di seluruh aplikasi
 
---
 
## Prioritas
 
1. **High**: Update logo di sidebar (quick win, visual consistency)
2. **High**: Perbaikan pesan batch penjadwalan (UX improvement)
3. **Medium**: Perbaikan tampilan public/index (visual enhancement)
4. **Medium**: Konversi panduan pengguna ke PDF (documentation improvement)
5. **Low**: Penggantian emoji dengan icon (polish)
 
---
 
## Catatan untuk Implementor
 
- Gunakan **Context7** untuk referensi Livewire v4, TailwindCSS, dan PDF generation
- Test setiap perubahan secara menyeluruh
- Pastikan backward compatibility jika ada perubahan besar
- Dokumentasikan perubahan di changelog jika perlu
- Untuk PDF generation, pertimbangkan performance dan caching
 