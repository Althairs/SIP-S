# Rencana Implementasi: Restrukturisasi UI, & Konsistensi Warna

## Ringkasan
Dokumen ini berisi panduan perencanaan tingkat tinggi (*high-level planning*) untuk merestrukturisasi merombak tata letak interaksi modal, menyeragamkan skema warna aplikasi, serta memperbaiki diksi di dashboard penjadwalan. Rencana ini ditujukan untuk programmer atau model AI agar dapat diimplementasikan dengan mudah.


## 1. Standardisasi Modal Menjadi Tampilan Halaman (View/Inline Layout) dengan Breadcrumb
### Konsep Utama
* Mengubah interaksi berbasis pop-up modal (floating overlay dengan latar belakang gelap) menjadi tampilan terintegrasi langsung di dalam halaman (*inline view* atau panel card) agar layout lebih rapi dan nyaman dibaca.
* Menambahkan baris navigasi remah roti (*breadcrumb*) di bagian atas area konten untuk mempermudah navigasi kembali.

### Panduan Implementasi (High-Level)
* **Langkah-langkah:**
  1. Cari semua implementasi `@if($showModal)` atau sejenisnya yang merender pembungkus modal melayang (`fixed inset-0 z-50 bg-black/50`).
  2. Desain ulang pembungkus tersebut agar formulir (Create/Edit) atau panel detail dirender menggantikan tabel utama (misalnya dengan menggunakan percabangan `@if($showForm)` untuk menyembunyikan tabel dan menampilkan form secara inline).
  3. Tambahkan komponen *breadcrumb* dinamis di bagian atas layout tersebut. Contoh format:
     * `Dashboard / Mahasiswa / Tambah Mahasiswa`
     * `Dashboard / Penjadwalan / Detail Jadwal`
  4. Pastikan transisi antara daftar data dan form/detail berjalan mulus menggunakan Livewire.

---

## 2. Konsistensi Warna dengan Landing Page (Tema Hijau Fakultas Pertanian)
### Konsep Utama
* Menyelaraskan seluruh aksen warna UI aplikasi agar konsisten dengan warna utama Fakultas Pertanian yang digunakan pada halaman beranda publik.

### Panduan Implementasi (High-Level)
* **Warna Acuan:** Skema warna hijau (AgriSched style) yang terdapat pada `resources/views/public/index.blade.php`.
  * *Primary Green:* `green-700` (`#15803d`), `green-800` (untuk hover/aktif), `green-50` / `green-100` (untuk background/lencana ringan).
* **Langkah-langkah:**
  1. Telusuri file blade di bawah folder `resources/views/livewire/` dan template layout.
  2. Ganti warna aksen yang tidak konsisten seperti Purple (`bg-purple-700`), Teal (`bg-teal-700`), Cyan (`from-cyan-600`), Indigo, dan Amber dengan padanan warna Hijau (`green`).
  3. Pastikan tombol aksi utama, header kartu, lencana status, dan efek hover navigasi menggunakan warna hijau yang seragam untuk menciptakan branding yang kuat dan bersih.

---

## Referensi Teknis (Gunakan Context7)
* Selalu gunakan perintah **Context7** (`resolve-library-id` dan `query-docs`) untuk mengambil dokumentasi terkini apabila membutuhkan referensi terkait:
  * Blade directives Spatie Laravel Permission (seperti penanganan multi-role/permission).
  * Struktur penulisan breadcrumb dengan Tailwind CSS yang memenuhi standar aksesibilitas.
  * Pola penukaran view (*view swapping*) pada Livewire untuk menggantikan modal.
