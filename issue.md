# Dokumen Perencanaan: Import Mahasiswa, Otomatisasi Status Akun, & Dashboard Tracker

Dokumen ini berisi spesifikasi kebutuhan dan rencana implementasi tingkat tinggi untuk beberapa fitur baru pada sistem pendaftaran ujian mahasiswa. Rencana ini ditujukan untuk digunakan oleh programmer atau model AI guna diimplementasikan ke dalam codebase Laravel + Livewire + Tailwind CSS.

---

## 1. Fitur Import Mahasiswa (Kajur > Mahasiswa)

### Deskripsi Kebutuhan
Mengganti tombol **"Export"** yang ada pada menu Kajur > Mahasiswa dengan tombol **"Import"** untuk mengunggah berkas Excel berisi daftar mahasiswa baru/aktif.

### Alur Implementasi & Solusi
1. **Perubahan UI (Kajur > Mahasiswa Index)**
   - Ubah tombol Export di file `resources/views/livewire/kajur/mahasiswa-index.blade.php` menjadi tombol **Import**.
   - Tombol Import akan memicu modal baru (layout upload file) atau area dropzone file Excel.
   
2. **Validasi Skema Header Excel**
   - Sebelum memproses baris data, sistem wajib memvalidasi apakah kolom file Excel yang diunggah sesuai dengan format referensi `mahasiswa_import.xlsx`.
   - Gunakan class `HeadingRowImport` dari package `maatwebsite/excel` untuk memeriksa kecocokan array header.
   - Kolom-kolom wajib yang harus ada di baris pertama Excel:
     * `No.`, `Strata`, `Angkatan`, `NIK`, `NIM`, `Nama`, `Tanggal Lahir`, `Sex`, `Fakultas`, `Program Studi`, `Kelas`, `Tipe`, `Seleksi`, `Status Awal`, `Semester Awal Terdaftar`, `Status Aktif`.
   - Jika kolom tidak sesuai, batalkan proses import dan tampilkan pesan error: *"Format kolom berkas Excel tidak sesuai dengan template referensi."*

3. **Logika Proses & Sinkronisasi Status Aktif**
   - Lakukan iterasi pada data baris Excel.
   - Gunakan `NIM` sebagai identifier unik untuk mencari record mahasiswa (tabel `users` dengan role `mahasiswa`).
   - **Jika NIM sudah ada di database**:
     * Update data profil mahasiswa sesuai data terbaru dari Excel.
     * Periksa nilai kolom `Status Aktif` dari Excel. Jika bernilai **"Aktif"**, maka aktifkan kembali akun mahasiswa tersebut (`is_active = true`).
   - **Jika NIM belum ada di database**:
     * Buat user baru dengan role `mahasiswa`.
     * Set password default (atau hash NIM/kombinasi aman) dan simpan data profil lainnya.
     * Set status `is_active` berdasarkan nilai kolom `Status Aktif` di Excel.

### File yang Perlu Dimodifikasi/Dibuat
- `resources/views/livewire/kajur/mahasiswa-index.blade.php` (UI Tombol & Modal Import)
- `app/Livewire/Kajur/MahasiswaIndex.php` (Penanganan file upload & trigger import class)
- [NEW] `app/Imports/MahasiswaImport.php` (Class import Laravel-Excel yang mengimplementasikan `ToCollection` / `ToModel`, `WithHeadingRow`, dan `WithValidation`)

---

## 2. Otomatisasi Penonaktifan Akun Inaktif (Inactivity Logic)

### Deskripsi Kebutuhan
Sistem harus secara otomatis menonaktifkan akun mahasiswa yang tidak mengajukan atau memiliki pendaftaran ujian/sidang aktif dalam kurun waktu 5 bulan terakhir.

### Alur Implementasi & Solusi
1. **Logika Penentuan Akun Inaktif**
   - Mahasiswa dianggap **inaktif** jika:
     * Tidak memiliki data pendaftaran (tabel `pendaftarans`) sama sekali dalam 5 bulan terakhir sejak akun dibuat, ATAU
     * Pendaftaran terakhir mahasiswa tersebut (`created_at` pada tabel `pendaftarans`) sudah lebih dari 5 bulan yang lalu.
   
2. **Pembuatan Artisan Console Command**
   - Buat command baru Laravel, misalnya `php artisan mahasiswa:nonaktifkan-inaktif`.
   - Query pencarian akun mahasiswa aktif (`is_active = true`):
     * Cek relasi ke `pendaftarans`.
     * Jika pendaftaran terakhir > 5 bulan (menggunakan `now()->subMonths(5)`), atau tidak memiliki pendaftaran dan tanggal pembuatan akun (`users.created_at`) > 5 bulan, ubah `is_active = false`.
   
3. **Pendaftaran di Scheduler**
   - Daftarkan command tersebut pada `routes/console.php` menggunakan `Schedule::command()` untuk dieksekusi secara otomatis dan berkala (misal: harian atau mingguan).

### File yang Perlu Dimodifikasi/Dibuat
- [NEW] `app/Console/Commands/NonaktifkanMahasiswaInaktif.php` (Command logika inaktivitas)
- `routes/console.php` (Pendaftaran jadwal eksekusi command)

---

## 3. Tracker Status Pendaftaran Interaktif di Dashboard Mahasiswa

### Deskripsi Kebutuhan
Menampilkan representasi visual interaktif (stepper / timeline progress) mengenai alur proses pendaftaran aktif mahasiswa pada halaman dashboard utama mereka.

### Alur/Tahapan Pendaftaran
Proses pendaftaran ujian mahasiswa memiliki tahapan status berikut:
1. **Tahap 1: Verifikasi Berkas (Panitia)**
   - **Status**: `pending` (Sedang diperiksa), `disetujui_panitia` (Disetujui), `ditolak_panitia` (Ditolak).
2. **Tahap 2: Penetapan Penguji (Sekjur/Kajur)**
   - **Status**: `disetujui_sekjur` (Ditetapkan penguji oleh Sekjur), `disetujui_kajur` (Disetujui Kajur), `ditolak_sekjur`/`ditolak_kajur` (Ditolak).
3. **Tahap 3: Penjadwalan Ujian (Panitia Penjadwalan)**
   - **Status**: `dijadwalkan` (Jadwal & Ruangan diterbitkan).
4. **Tahap 4: Pelaksanaan & Selesai**
   - **Status**: `selesai`.

### Desain & Implementasi UI Dashboard
- Di file `resources/views/livewire/mahasiswa/dashboard.blade.php`, buat komponen **Stepper Horizontal atau Vertical Timeline** menggunakan kelas Tailwind CSS.
- Komponen ini hanya tampil jika terdapat `$pendaftaranAktif` pada property dashboard.
- Berikan warna/ikon interaktif yang berbeda berdasarkan status pendaftaran:
  * **Selesai (Completed)**: Lingkaran hijau dengan ikon centang (`bg-green-100 text-green-600`).
  * **Sedang Berjalan (Active/In Progress)**: Lingkaran biru dengan efek animasi/pulse (`bg-blue-100 text-blue-600 animate-pulse`).
  * **Belum Dimulai (Pending/Upcoming)**: Lingkaran abu-abu (`bg-gray-100 text-gray-400`).
  * **Ditolak/Batal (Rejected)**: Lingkaran merah dengan ikon silang (`bg-red-100 text-red-600`).
- Tampilkan detail kecil di bawah setiap tahapan (contoh: nama penguji jika sudah ditetapkan, atau waktu/ruangan jika sudah dijadwalkan).

### File yang Perlu Dimodifikasi
- `app/Livewire/Mahasiswa/Dashboard.php` (Menyediakan data status pendaftaran aktif saat ini)
- `resources/views/livewire/mahasiswa/dashboard.blade.php` (Visualisasi layout stepper/progress tracker)

---

## 4. Referensi & Integrasi Context7

Untuk pengembang yang akan melakukan implementasi fitur ini:
- Gunakan library **Laravel Excel** (`/websites/laravel-excel_3_1`) sebagai basis import data.
- Gunakan `HeadingRowImport` untuk mengecek header berkas secara dinamis sebelum di-import:
  ```php
  use Maatwebsite\Excel\HeadingRowImport;
  $headings = (new HeadingRowImport)->toArray($file);
  ```
- Seluruh manipulasi data mahasiswa wajib memperhatikan konsistensi role `'mahasiswa'` yang di-manage menggunakan `spatie/laravel-permission`.
