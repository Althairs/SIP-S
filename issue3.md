# Issue: Perbaikan Generate Penguji dan Tombol Test Kuota

## Ringkasan

Dokumentasi perencanaan untuk perbaikan tiga masalah utama:
1. Batas bidang keahlian maksimal 3 dan skor maksimal 100 di Generate Penguji
2. Menampilkan skor di tabel dosen tersedia saat memilih manual/auto
3. Tombol test untuk reset kuota bulanan

---

## 1. Batas Bidang Keahlian dan Skor Maksimal di Generate Penguji

### Masalah
- Sistem perlu membatasi bidang keahlian maksimal 3 per dosen
- Skor perlu dibatasi maksimal 100
- Hasil perhitungan skor perlu ditampilkan setelah generate penguji

### Lokasi File
- Component: `app/Livewire/Sekjur/GeneratePenguji.php`
- View: `resources/views/livewire/sekjur/generate-penguji.blade.php`

### Solusi High-Level
1. Tambahkan validasi untuk memastikan dosen tidak memiliki lebih dari 3 bidang keahlian
2. Modifikasi logika perhitungan skor untuk membatasi maksimal 100
3. Tampilkan detail perhitungan skor di UI setelah generate

### Implementasi
- Di `GeneratePenguji.php`:
  - Tambahkan validasi di `loadAvailableDosens()` untuk filter dosen dengan bidang keahlian > 3
  - Modifikasi method perhitungan skor untuk cap di 100
  - Tambahkan property untuk menyimpan detail perhitungan skor (misal: `scoreBreakdown`)
- Di view:
  - Tampilkan detail perhitungan skor di card penguji yang sudah di-generate
  - Format tampilan: breakdown per komponen skor (bidang keahlian, kuota, dll)

---

## 2. Menampilkan Skor di Tabel Dosen Tersedia

### Masalah
- Skor di tabel "Dosen Tersedia" hilang saat mode berubah (manual/auto)
- Skor perlu tetap terlihat untuk membantu user memilih dosen

### Lokasi File
- Component: `app/Livewire/Sekjur/GeneratePenguji.php`
- View: `resources/views/livewire/sekjur/generate-penguji.blade.php`

### Solusi High-Level
1. Pastikan score property tetap ada di `availableDosens` collection
2. Jangan re-query database yang akan menghilangkan score dinamis
3. Gunakan score dari collection yang sudah ada

### Implementasi
- Di `GeneratePenguji.php`:
  - Pastikan `availableDosens` collection tidak di-reload saat mode berubah
  - Score harus dihitung sekali di `loadAvailableDosens()` dan disimpan
  - Hindari `toArray()` yang menghilangkan property dinamis
- Di view:
  - Tampilkan kolom skor di tabel dosen tersedia
  - Pastikan skor tetap terlihat baik di mode auto maupun manual

---

## 3. Tombol Test Reset Kuota Bulanan

### Masalah
- Perlu tombol test untuk memverifikasi bahwa scheduled task reset kuota berfungsi
- Tombol ini akan menjalankan reset manual untuk testing

### Lokasi File
- Component: `app/Livewire/Kajur/KuotaDosen.php`
- View: `resources/views/livewire/kajur/kuota-dosen.blade.php`

### Status Saat Ini
- Method `resetKuotaBulanan()` sudah ada di component
- Command `kuota:reset-bulanan` sudah ada
- Scheduled task sudah di-setup di `routes/console.php`

### Solusi High-Level
1. Tambahkan tombol test di UI Kuota Dosen
2. Tombol akan menjalankan method `resetKuotaBulanan()` yang sudah ada
3. Tampilkan feedback success/failure dengan jumlah dosen yang di-reset

### Implementasi
- Di `KuotaDosen.php`:
  - Method `resetKuotaBulanan()` sudah ada, cukup dipanggil dari tombol
  - Pastikan flash message menampilkan jumlah dosen yang di-reset
- Di view:
  - Tambahkan tombol "Test Reset Kuota Bulanan" di section action
  - Styling tombol berbeda dari tombol lain (misal: warna biru/abu)
  - Tampilkan konfirmasi sebelum execute (optional)
- Testing:
  - Klik tombol test
  - Verifikasi kuota dosen reset ke default (20)
  - Cek log untuk memastikan scheduled task akan berjalan dengan benar

---

## Prioritas

1. **High**: Batas bidang keahlian dan skor maksimal (kritis untuk logika seleksi)
2. **High**: Menampilkan skor di tabel dosen tersedia (UX improvement)
3. **Medium**: Tombol test reset kuota (testing/validation)

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Livewire v4 dan best practices
- Test setiap perubahan secara menyeluruh
- Pastikan backward compatibility jika ada perubahan besar
- Dokumentasikan perubahan di changelog jika perlu
