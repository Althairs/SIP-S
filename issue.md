# Issue: Modul Laporan PDF & Kuota Dosen Bulanan

## Ringkasan

Menambahkan fitur unduh laporan PDF untuk **Panitia Administrasi** dan perbaikan alur **kuota dosen** di panel **Kajur**. Semua laporan digenerate dengan **DomPDF**, memakai kop surat standar universitas.

---

## Konteks Sistem (Yang Sudah Ada)

- Panel Panitia Administrasi sudah ada (`/panitia/administrasi`) — sidebar masih placeholder "Dalam Pengembangan"
- Modul kuota dosen Kajur sudah ada (`KuotaDosen`, halaman `kajur/kuota-dosen`)
- Prototype PDF sudah ada di `resources/views/testlaporan.blade.php` dan acuan visual di `contoh-laporan.pdf`
- Data utama tersedia di model: `Pendaftaran`, `Penilaian`, `KuotaDosen`, `User`

---

## 1. UI Laporan — Panitia Administrasi

**Tujuan:** Halaman khusus untuk mengunduh semua jenis laporan.

**Yang perlu dibuat:**
- Tambah menu **Laporan** di sidebar panitia administrasi (ganti placeholder "Dalam Pengembangan")
- Halaman Livewire berisi daftar 4 jenis laporan (lihat bagian 4)
- Setiap laporan punya tombol **Download PDF**
- Filter umum (opsional tapi disarankan): periode/tahun akademik, prodi, jenis ujian
- Scope data: hanya jurusan milik user yang login (`jurusan_id`)

**Acceptance criteria:**
- Hanya role `panitia_administrasi` yang bisa akses
- Setiap tombol download menghasilkan file PDF valid
- UI konsisten dengan dashboard panitia administrasi yang sudah ada

---

## 2. Kuota Dosen Bulanan — Kajur

**Tujuan:** Kuota dosen terisi otomatis setiap bulan, dengan default **20/bulan**, dan bisa diubah manual oleh Kajur.

**Perilaku yang diharapkan:**
- Setiap awal bulan (atau mekanisme reset bulanan), kuota dosen di jurusan terkait diisi ulang ke nilai default
- Default global: **20** (untuk kuota yang relevan — tentukan apakah 20 untuk pembimbing, penguji, atau keduanya; sesuaikan dengan kebutuhan bisnis)
- Kajur bisa mengubah default bulanan per jurusan (mis. via setting di halaman kuota dosen)
- Kajur tetap bisa override kuota per dosen secara manual (fitur edit yang sudah ada)
- Counter `terpakai_*` direset atau dihitung ulang sesuai kebijakan bisnis yang dipilih

**Yang perlu ditinjau/ditambah:**
- Apakah perlu kolom/tabel baru untuk **setting default kuota bulanan** per jurusan
- Apakah perlu **scheduled job** (Laravel scheduler) untuk reset otomatis tiap bulan
- Sesuaikan nilai default lama (saat ini 5/10) dengan kebijakan baru (20)

**Acceptance criteria:**
- Kuota ter-reset otomatis tiap bulan tanpa intervensi manual
- Kajur bisa mengatur angka default selain 20
- Override per dosen tetap berfungsi

---

## 3. Infrastruktur PDF (DomPDF)

**Tujuan:** Satu cara standar untuk generate semua laporan PDF.

**Yang perlu dibuat:**
- Install & konfigurasi package DomPDF untuk Laravel (atau adaptasi dari prototype `testlaporan.blade.php`)
- Buat **layout/template PDF reusable** dengan kop surat
- Buat **service atau controller** terpusat untuk generate & stream/download PDF
- Pisahkan: **template Blade untuk PDF** (bukan view Livewire biasa)
- Logo universitas via base64 (sudah dicontohkan di prototype)

**Struktur direktori (saran):**
```
resources/views/pdf/
  ├── layouts/kop.blade.php      ← partial kop surat
  ├── laporan-pendaftaran.blade.php
  ├── laporan-kuota-dosen.blade.php
  ├── laporan-nilai.blade.php
  └── laporan-ujian-selesai.blade.php
```

**Acceptance criteria:**
- Semua laporan memakai layout kop yang sama
- Font, margin, dan ukuran kertas konsisten (A4 portrait)
- File PDF bisa diunduh langsung dari browser

---

## 4. Jenis Laporan

### 4.1 Laporan Pendaftaran Mahasiswa
- Data dari `Pendaftaran` (+ relasi mahasiswa, prodi, jenis ujian, status)
- Isi: daftar mahasiswa yang mendaftar ujian, periode, status pendaftaran

### 4.2 Laporan Kuota Dosen (Membimbing & Menguji)
- Data dari `KuotaDosen` (+ relasi dosen, prodi)
- Isi: nama dosen, kuota pembimbing, terpakai, sisa, kuota penguji, terpakai, sisa

### 4.3 Laporan Nilai Mahasiswa
- Data dari `Pendaftaran` / `Penilaian` yang sudah selesai dinilai
- Isi: mahasiswa, jenis ujian, nilai total, grade, penguji/pembimbing

### 4.4 Laporan Mahasiswa yang Sudah Ujian
- Mahasiswa dengan status selesai per jenis ujian: **proposal**, **hasil**, **skripsi**
- Bisa ditampilkan per jenis atau digabung dengan kolom jenis ujian
- Isi: identitas mahasiswa, judul, tanggal ujian, nilai (jika ada)

**Catatan umum semua laporan:**
- Sertakan judul laporan, tanggal cetak, dan filter yang dipakai
- Format tabel sederhana (bukan desain kompleks)
- Data difilter per `jurusan_id` user yang login

---

## 5. Kop Surat

**Acuan:** `contoh-laporan.pdf` dan implementasi di `testlaporan.blade.php`

**Spesifikasi kop:**
- Tabel 3 kolom: logo (kiri) | teks institusi (tengah) | spacer (kanan)
- Logo UNG di kolom kiri
- Teks kop: Kementerian, Universitas, Fakultas, alamat, telepon, email, laman
- Garis pemisah ganda di bawah kop
- Judul laporan di bawah kop (bukan surat rekomendasi — sesuaikan per jenis laporan)

Buat sebagai **Blade partial** agar dipakai ulang di semua template PDF.

---

## Urutan Implementasi (Disarankan)

1. Setup DomPDF + layout kop surat (partial reusable)
2. Buat 1 laporan sebagai proof of concept (mis. laporan pendaftaran)
3. Buat UI halaman laporan di Panitia Administrasi + route
4. Implementasi 3 laporan sisanya
5. Fitur kuota dosen bulanan (migration/setting + scheduler + UI Kajur)
6. Testing end-to-end per role

---

## Hal di Luar Scope (Untuk Issue Terpisah)

- Tanda tangan digital / QR code BSrE (ada di contoh surat, belum wajib untuk laporan rekap)
- Export Excel/CSV
- Laporan untuk role selain Panitia Administrasi
- Nomor surat resmi per laporan

---

## Referensi File

| File | Keterangan |
|------|------------|
| `contoh-laporan.pdf` | Acuan visual kop surat |
| `resources/views/testlaporan.blade.php` | Prototype DomPDF + struktur HTML/CSS kop |
| `app/Livewire/Panitia/Administrasi/Dashboard.php` | Dashboard panitia administrasi |
| `app/Livewire/Kajur/KuotaDosen.php` | Manajemen kuota dosen |
| `app/Models/KuotaDosen.php` | Model kuota |
| `app/Models/Pendaftaran.php` | Model pendaftaran ujian |
