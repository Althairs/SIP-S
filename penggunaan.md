# Penggunaan

Daftar isi:

- [Reset Kuota Dosen Bulanan](#reset-kuota-dosen-bulanan)
- [Nonaktif Otomatis Akun Mahasiswa Inaktif](#nonaktif-otomatis-akun-mahasiswa-inaktif)

---

## Reset Kuota Dosen Bulanan

### Tujuan

Mengembalikan kuota pembimbing dan penguji setiap dosen ke nilai default setiap awal bulan.

### Cara Kerja

1. **Command**: `kuota:reset-bulanan`
2. **Jadwal**: Otomatis setiap tanggal 1 pukul 00:00 (dijadwalkan di `routes/console.php:13`)
3. **Proses** (`app/Services/KuotaDosenService.php`):
   - Ambil semua dosen per jurusan
   - Setiap dosen di-`updateOrCreate` data kuotanya:
     - `kuota_pembimbing` dan `kuota_penguji` diisi dari nilai default jurusan (`jurusan.default_kuota_pembimbing` / `default_kuota_penguji`), fallback ke 20
     - `terpakai_pembimbing` dan `terpakai_penguji` direset ke 0
   - Kolom `kuota_last_reset_at` di jurusan diupdate ke waktu sekarang
4. **Opsi**: `php artisan kuota:reset-bulanan --jurusan={id}` untuk reset satu jurusan saja (tanpa `--jurusan` = semua jurusan)

### Default Value

| Peran | Default |
|-------|---------|
| Pembimbing | 20 per bulan |
| Penguji | 20 per bulan |

Default bisa disesuaikan per jurusan melalui kolom `default_kuota_pembimbing` dan `default_kuota_penguji` di tabel `jurusans` (diatur lewat menu Data Master → Atur Atribut Dosen).

### Logging

- Sukses: `Log::info('Kuota dosen bulanan berhasil direset pada ...')`
- Gagal: `Log::error('Gagal mereset kuota dosen bulanan pada ...')`

---

## Nonaktif Otomatis Akun Mahasiswa Inaktif

### Tujuan

Menonaktifkan akun mahasiswa yang sudah tidak aktif mendaftar ujian selama 5 bulan.

### Cara Kerja

1. **Command**: `mahasiswa:nonaktifkan-inaktif`
2. **Jadwal**: Otomatis setiap hari pukul 00:00 (dijadwalkan di `routes/console.php:24`)
3. **Proses** (`app/Console/Commands/NonaktifkanMahasiswaInaktif.php`):
   - Ambil semua user dengan role `mahasiswa` yang `is_active = true`
   - Cek pendaftaran terakhir masing-masing:
     - **Pernah mendaftar**: Jika `created_at` pendaftaran terakhir lebih dari 5 bulan lalu → nonaktifkan
     - **Tidak pernah mendaftar**: Jika `created_at` akun lebih dari 5 bulan lalu → nonaktifkan
   - Update `is_active` menjadi `false`

### Kriteria Nonaktif

| Kondisi | Waktu Tunggu | Aksi |
|---------|-------------|------|
| Pernah mendaftar | 5 bulan sejak pendaftaran terakhir | `is_active = false` |
| Belum pernah mendaftar | 5 bulan sejak akun dibuat | `is_active = false` |

### Dampak

- Akun yang dinonaktifkan tidak bisa login (dicek oleh middleware `active` di `routes/web.php:107`)
- Data mahasiswa tetap ada, hanya status akun yang diubah
- Admin/Kajur bisa mengaktifkan kembali secara manual lewat halaman Data Mahasiswa

### Logging

- Setiap mahasiswa yang dinonaktifkan dicatat: `Log::info("Akun mahasiswa {nama} (NIM: {nim}) dinonaktifkan otomatis...")`
- Total di akhir: `Log::info("Selesai memeriksa mahasiswa inaktif. Total dinonaktifkan: {count}")`
