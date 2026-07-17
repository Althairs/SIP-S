# Planning Perbaikan UI dan Hak Akses

## 1. Perbaikan Layout Modal Menjadi View dengan Breadcrumb

**Tujuan:** Ubah semua modal yang ada menjadi layout view biasa agar tampilan lebih enak dilihat, dan tambahkan breadcrumb untuk navigasi.

**Langkah-langkah High Level:**
- Identifikasi semua modal yang ada di seluruh aplikasi (cari di semua file blade admin,kajur,sekjur,mahasiswa,dosen,panitia penjadwalan,panitia verifikasi,panitia administrasi)
- Ubah setiap modal menjadi view/page terpisah dengan layout yang konsisten
- Tambahkan breadcrumb component di bagian atas setiap view baru
- Pastikan breadcrumb menunjukkan hierarki navigasi yang jelas (misal: Home > Modul > Sub-modul)
- Sesuaikan routing agar view baru dapat diakses
- Update semua link yang sebelumnya membuka modal untuk mengarah ke view baru
- Pastikan responsive design tetap terjaga

**Catatan:** Gunakan pattern yang sama untuk semua view agar konsistensi terjaga.

---

## 2. Refactor Sistem Role & Permission
**Tujuan**

Refactor sistem Role & Permission agar seluruh hak akses dikelola secara dinamis melalui halaman Role & Akses. Super Admin dapat mencentang permission tertentu untuk setiap role sehingga seluruh menu, halaman, controller, route, dan action mengikuti permission yang diberikan.

**Langkah-Langkah High Level**
1. Review Permission
Review seluruh Role dan Permission yang ada.
Hilangkan pengecekan berbasis nama role (@role, hasRole(), dll) apabila digunakan untuk membatasi fitur.
Gunakan Permission sebagai sumber utama otorisasi (can, permission, middleware permission).
2. Standarisasi Permission

Pastikan seluruh permission berikut tersedia.

- Jurusan
view jurusan
create jurusan
edit jurusan
delete jurusan

- Program Studi
view prodi
create prodi
edit prodi
delete prodi

- Users
view users
create users
edit users
delete users
activate users

- Role & Permission
view roles
assign roles

- Dosen
view dosen
create dosen
edit dosen
delete dosen

-Mahasiswa
view mahasiswa
create mahasiswa
edit mahasiswa
delete mahasiswa

-Panitia
view panitia
create panitia
edit panitia
delete panitia

-Kuota Dosen
manage kuota dosen

-Bidang Keahlian
manage bidang keahlian

-Kepakaran
manage kepakaran
Verifikasi Seminar
verify seminar proposal
verify seminar hasil
Verifikasi Sidang
verify sidang skripsi

-Data
import data
export data

-Penguji
view penguji
manage penguji
generate penguji

-Berkas
verify berkas

-Penjadwalan Ujian
schedule ujian

-Jadwal
manage jadwal

3. Perbarui Halaman Role & Akses
Kelompokkan permission berdasarkan menu/sidebar.
Tampilkan permission dalam bentuk checkbox.
Permission CRUD ditampilkan berurutan:
View
Create
Edit
Delete
Permission non-CRUD seperti Verify, Generate, Schedule, Import, Export, Manage tetap ditampilkan pada grup yang sesuai.
Tambahkan fitur Select All per kategori.
Tambahkan fitur Select All seluruh permission.
Permission yang sudah dimiliki role harus otomatis tercentang.
4. Sidebar

Perbarui seluruh sidebar agar menggunakan permission, bukan role.

Contoh:

Jurusan muncul jika memiliki view jurusan
Prodi muncul jika memiliki view prodi
Users muncul jika memiliki view users
Dosen muncul jika memiliki view dosen
Mahasiswa muncul jika memiliki view mahasiswa
Panitia muncul jika memiliki view panitia
Penguji muncul jika memiliki view penguji
Role & Akses muncul jika memiliki view roles
Jadwal muncul jika memiliki manage jadwal
Verifikasi Proposal muncul jika memiliki verify seminar proposal
Verifikasi Hasil muncul jika memiliki verify seminar hasil
Verifikasi Sidang muncul jika memiliki verify sidang skripsi
Verifikasi Berkas muncul jika memiliki verify berkas

Jangan lagi mengandalkan @role(...) untuk menentukan menu yang tampil, kecuali benar-benar diperlukan untuk identitas pengguna.

5. Route & Middleware

Semua route harus menggunakan middleware permission yang sesuai.

Contoh:

index → view permission
create/store → create permission
edit/update → edit permission
destroy → delete permission

Seluruh endpoint harus terlindungi walaupun URL diakses secara langsung.

6. Controller

Setiap controller harus melakukan authorization menggunakan permission.

Contoh:

view → view ...
create → create ...
edit → edit ...
delete → delete ...

Permission harus divalidasi pada seluruh aksi CRUD maupun aksi khusus seperti Verify, Generate, Import, Export, dan Schedule.

7. Blade View
Tombol Tambah hanya muncul jika memiliki permission Create.
Tombol Edit hanya muncul jika memiliki permission Edit.
Tombol Hapus hanya muncul jika memiliki permission Delete.
Tombol Import, Export, Generate, Verify, Schedule, dan aksi lainnya hanya muncul jika memiliki permission terkait.
8. Seeder Permission

Perbarui PermissionSeeder agar seluruh permission di atas otomatis dibuat apabila belum tersedia.

Pastikan proses seeder bersifat idempotent sehingga aman dijalankan berulang kali.

9. Super Admin

Role Super Admin tetap memiliki seluruh permission secara default.

Namun Super Admin tetap dapat mengelola permission untuk role lain melalui halaman Role & Akses tanpa pembatasan.

10. Testing

Pastikan seluruh skenario berikut berhasil.

Sidebar hanya menampilkan menu sesuai permission.
Route tidak dapat diakses tanpa permission.
Controller menolak request tanpa permission.
Tombol aksi mengikuti permission.
CRUD berjalan sesuai hak akses.
Permission Verify, Generate, Import, Export, Schedule, dan Manage bekerja dengan benar.
Tidak ada halaman yang masih menggunakan pengecekan role apabila seharusnya menggunakan permission.

---

## 3. Perbaikan Terminologi di Dashboard Penjadwalan

**Tujuan:** Mengganti kata-kata yang tidak jelas atau kurang umum di penjadwalan/dashboard.blade.php dengan terminologi web yang lebih standar.

**Langkah-langkah High Level:**
- Review semua teks/label di dashboard penjadwalan
- Identifikasi kata-kata yang kurang jelas atau terlalu teknis (misal: "konteks project")
- Ganti dengan terminologi yang lebih umum dan mudah dipahami (misal: "status", "proses", "tahapan", dll)
- Pastikan perubahan konsisten di seluruh dashboard
- Update label button, heading, dan deskripsi agar lebih user-friendly
- Pastikan perubahan tidak merusak fungsionalitas yang ada

**Contoh perubahan yang mungkin diperlukan:**
- "Konteks project" → "Status" atau "Tahapan"
- Kata-kata teknis lainnya → istilah yang lebih umum

---

## 4. Penerapan Context7

**Tujuan:** Menggunakan context7 dalam implementasi perubahan di atas.

**Langkah-langkah High Level:**
- Review dan pahami context7 yang sudah ada di project
- Terapkan context7 secara konsisten di semua perubahan yang dilakukan
- Pastikan style, pattern, dan convention mengikuti context7
- Jika ada konflik dengan implementasi baru, sesuaikan agar mengikuti context7
- Validasi bahwa semua perubahan sudah sesuai dengan context7

---

## Prioritas Pengerjaan

1. **High Priority:** Perbaikan Terminologi (Task 3) - relatif cepat dan impact langsung ke UX
2. **Medium Priority:** Perbarui Hak Akses Super Admin (Task 2) - penting untuk security
3. **Medium Priority:** Perbaikan Layout Modal (Task 1) - butuh lebih banyak waktu
4. **Continuous:** Context7 (Task 4) - diterapkan bersamaan dengan task lain

## Catatan Umum

- Pastikan backup sebelum melakukan perubahan besar
- Testing setiap perubahan sebelum merge
- Jika ada kendala atau ambigu, diskusikan dahulu sebelum implementasi
- Fokus pada high level implementation, detail teknis dapat disesuaikan saat implementasi
