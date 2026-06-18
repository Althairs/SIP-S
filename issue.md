# Rencana Perbaikan dan Pengembangan SIP-S

Dokumen ini berisi rencana high-level untuk penyempurnaan alur peran dan tampilan di aplikasi SIP-S menggunakan pendekatan `context7`.

## 1. Kajur: Tampilkan Semua Role Panitia

- Revisi logika data panitia di Kajur agar memuat semua role panitia, bukan hanya `panitia_verifikasi`.
- Pastikan `panitia_penjadwalan` dan `panitia_administrasi` juga tercantum sebagai bagian dari daftar panitia.

## 2. UI Interaktif untuk Semua Peran

- Perbarui antarmuka agar lebih dari sekadar tautan akses cepat.
- Bangun pengalaman lebih interaktif untuk peran:
  - Kajur
  - Mahasiswa
  - Dosen
  - Panitia Penjadwalan
  - Panitia Verifikasi
  - Panitia Administrasi
- Fokus pada komponen visual yang membantu pengguna memahami status tugas mereka, seperti kartu ringkasan, tabel dengan status, dan tindakan kontekstual.

## 3. Kajur: Tata Ulang Informasi Panitia

- Susun ulang dashboard atau halaman data panitia agar menonjolkan peran, status aktif, dan kapasitas tugas.
- Gunakan tampilan yang memungkinkan Kajur melihat perbandingan peran panitia secara cepat.
- Pertahankan pengalaman yang konsisten dengan peran lain di sistem.

## 4. Mahasiswa: Alur Data dan Pendaftaran

- Perbarui alur Mahasiswa supaya data pendaftaran selanjutnya memanfaatkan konteks pendaftaran sebelumnya.
- Pastikan mahasiswa tidak melihat opsi pendaftaran yang sudah tidak relevan atau duplikat.
- Gunakan konteks `context7` untuk menjaga kelancaran transisi antar tahapan pendaftaran.

## 5. Dosen: UI & Status Tugas

- Sederhanakan tampilan Dosen sehingga informasi penting seperti kuota, jadwal menguji, dan pengajuan tugas tampil lebih langsung.
- Kurangi ketergantungan pada akses cepat dengan komponen yang memvisualisasikan konteks tugas.</n

## 6. Panitia Penjadwalan, Verifikasi, dan Administrasi: UX Lebih Kuat

- Ubah halaman Panitia menjadi lebih fungsional dan informatif.
- Tambahkan elemen yang menjelaskan langkah berikutnya, prioritas, dan status pekerjaan.
- Utamakan layout yang membantu pengguna menavigasi pekerjaan mereka tanpa harus mencari tautan tersembunyi.

## 7. Konteks `context7` sebagai Panduan Desain

- Rancang setiap perubahan berdasarkan situasi pengguna di peran masing-masing.
- Pastikan data dan pengalaman penggunaan mengalir dari satu langkah ke langkah berikutnya.
- Jadikan setiap halaman sebagai bagian dari rangkaian proses yang saling terhubung.

---

Catatan: dokumen ini dimaksudkan sebagai panduan perencanaan tingkat tinggi untuk programmer atau model dengan sumber daya terbatas. Hindari detail teknis implementasi yang terlalu spesifik.
