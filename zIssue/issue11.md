# Issue #11: Perbaikan Fitur Revisi Dosen

## Deskripsi Masalah

1. **Riwayat Revisi Tidak Menampilkan Data yang Sesuai**
   - Tab "Riwayat Revisi" pada halaman Dosen > Daftar Revisi tidak menampilkan data revisi dengan benar
   - Data yang ditampilkan mungkin tidak sesuai dengan revisi yang sebenarnya dibuat oleh dosen

2. **Fitur Review & Persetujuan Revisi Mahasiswa**
   - Perlu memastikan dosen dapat melihat hasil revisi yang diupload mahasiswa
   - Perlu memastikan dosen dapat menyetujui atau menolak revisi mahasiswa dengan alasan yang jelas

## Lingkup Pekerjaan

### 1. Perbaikan Query Riwayat Revisi
- Audit dan perbaiki query yang mengambil data `$revisiSaya` di `DaftarRevisi.php`
- Pastikan query mengambil revisi yang benar-benar dibuat oleh dosen yang sedang login
- Verifikasi relasi dan filter yang digunakan pada model `Revisi`
- Pastikan data yang ditampilkan sesuai dengan konteks (sebagai penguji)

### 2. Perbaikan Fitur Review & Persetujuan
- Verifikasi modal review & persetujuan berfungsi dengan benar
- Pastikan file revisi mahasiswa dapat diunduh dan ditampilkan
- Pastikan catatan mahasiswa ditampilkan dengan benar
- Verifikasi tombol "Setujui Revisi" dan "Minta Revisi Baru" berfungsi sesuai expected
- Pastikan status revisi diperbarui dengan benar setelah approval/rejection

### 3. Testing
- Test riwayat revisi menampilkan data yang benar
- Test fitur review & persetujuan untuk berbagai skenario
- Test flow dari revisi dibuat → mahasiswa upload → dosen review → approval/rejection

## File yang Perlu Diperiksa

- `app/Livewire/Dosen/DaftarRevisi.php` - Component logic
- `resources/views/livewire/dosen/daftar-revisi.blade.php` - View UI
- `app/Models/Revisi.php` - Model dan relasi
- `app/Models/UjianPenguji.php` - Model ujian penguji
- `app/Models/Pendaftaran.php` - Model pendaftaran

## Kriteria Sukses

1. Riwayat revisi menampilkan data yang sesuai dengan revisi yang dibuat dosen
2. Dosen dapat melihat file revisi yang diupload mahasiswa
3. Dosen dapat menyetujui revisi dengan catatan opsional
4. Dosen dapat meminta revisi baru dengan catatan wajib
5. Status revisi diperbarui dengan benar setelah tindakan dosen
6. UI menampilkan status dan informasi yang jelas

## Catatan Implementasi

- Gunakan query yang sudah ada di model `Revisi` (scope `byDosen`) jika sudah tersedia
- Pastikan relasi eager loading dilakukan untuk performa
- Pertahankan struktur UI yang sudah ada, hanya perbaiki logic di belakangnya
- Tambahkan validasi yang diperlukan untuk approval/rejection
- Berikan feedback yang jelas kepada user (flash message) setelah setiap aksi
