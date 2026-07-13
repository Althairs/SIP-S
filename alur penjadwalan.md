# Alur Penjadwalan Batch

## Ringkasan

Dokumentasi alur penjadwalan batch ujian oleh Panitia Penjadwalan. Alur ini mencakup proses dari pemilihan pendaftaran yang siap dijadwalkan hingga pembentukan jadwal ujian dan pengiriman notifikasi.

---

## Prasyarat

Pendaftaran harus memiliki status `disetujui_kajur` untuk bisa dijadwalkan. Status ini berarti:
- Pendaftaran sudah melalui verifikasi panitia
- Penguji sudah ditentukan oleh Sekjur
- Sudah disetujui oleh Kajur

---

## Komponen Utama

| Komponen | File | Fungsi |
|----------|------|--------|
| UI Penjadwalan | `app/Livewire/Panitia/Penjadwalan/JadwalUjians.php` | Interface panitia untuk penjadwalan |
| Validasi Konflik | `app/Services/JadwalConflictService.php` | Cek konflik jadwal (ruangan, sesi, penguji) |
| Model Pendaftaran | `app/Models/Pendaftaran.php` | Data pendaftaran ujian |
| Model UjianPenguji | `app/Models/UjianPenguji.php` | Data penguji yang ditugaskan |
| Notifikasi WA | `app/Jobs/SendWhatsAppNotification.php` | Kirim notifikasi jadwal |

---

## Alur Penjadwalan Batch

### 1. Persiapan di Panel Panitia

**Lokasi:** Panel Panitia → Penjadwalan → Tab "Siap"

**Aksi:**
- Panitia melihat daftar pendaftaran dengan status `disetujui_kajur`
- Panitia memilih satu atau lebih pendaftaran menggunakan checkbox
- Panitia klik tombol "Jadwalkan Batch"

### 2. Validasi Awal

**Fungsi:** `openBatchModal()` di `JadwalUjians.php`

**Validasi:**
- Pastikan minimal satu pendaftaran dipilih
- Pastikan semua pendaftaran yang dipilih memiliki status `disetujui_kajur`
- Jika ada yang tidak siap, tampilkan error dan batalkan proses

**Perhitungan Tanggal Minimal:**
- Ambil tanggal pendaftaran terlama (`first_registered_at`)
- Tanggal minimal ujian = tanggal pendaftaran + 7 hari
- Jika tanggal minimal sudah lewat, gunakan tanggal besok

### 3. Input Parameter Jadwal

**Modal Batch:** Panitia menginput:
- **Tanggal ujian** (minimal tanggal yang dihitung di langkah 2)
- **Ruangan** (dari daftar ruangan aktif jurusan)
- **Sesi** (1, 2, 3, atau 4 - sesuai pengaturan jadwal)

**Pengaturan Sesi:** Diambil dari `PengaturanJadwal` per jurusan:
- Jam mulai per sesi
- Jam selesai per sesi
- Label sesi (misal: "Sesi 1", "Sesi 2")

### 4. Validasi Konflik Jadwal

**Fungsi:** `scheduleBatchUjian()` → `JadwalConflictService::validateSchedule()`

**Untuk setiap pendaftaran:**

**Cek Konflik Ruangan & Sesi:**
- Cek apakah sudah ada jadwal di tanggal, ruangan, dan sesi yang sama
- Jika ada, lanjut ke pengecekan jenis ujian

**Aturan Konflik berdasarkan Jenis Ujian:**
- **Seminar Hasil / Sidang Skripsi:** Tidak boleh ada jadwal lain di ruangan & sesi yang sama
- **Seminar Proposal:** Boleh digrup jika memenuhi syarat:
  - Penguji 1 dan Penguji harus identik dengan jadwal lain di slot tersebut
  - Minimal 1 pembimbing harus sama

**Jika konflik:** Pendaftaran tersebut dilewati, error dicatat untuk ditampilkan di akhir

### 5. Update Data Pendaftaran

**Untuk setiap pendaftaran yang lolos validasi:**

Update field di tabel `pendaftarans`:
- `tanggal_ujian`: tanggal + jam mulai sesi
- `ruangan`: ruangan yang dipilih
- `sesi`: nomor sesi (1-4)
- `status`: diubah menjadi `dijadwalkan`
- `scheduled_at`: timestamp saat ini

### 6. Pengiriman Notifikasi WhatsApp

**Untuk setiap pendaftaran yang berhasil dijadwalkan:**

**Notifikasi ke Mahasiswa:**
- Penerima: nomor HP mahasiswa
- Isi: Nama mahasiswa, tanggal ujian, ruangan

**Notifikasi ke Dosen Penguji:**
- Penerima: semua dosen penguji (penguji_1 dan penguji_2)
- Isi: Nama dosen, tanggal ujian, nama mahasiswa, ruangan

**Mekanisme:**
- Notifikasi dikirim via queue job (`SendWhatsAppNotification`)
- Tidak blocking proses utama
- Gagal kirim tidak membatalkan jadwal

### 7. Feedback ke User

**Setelah proses selesai:**

**Jika ada yang berhasil:**
- Tampilkan pesan success: "X ujian berhasil dijadwalkan pada [tanggal]"
- Reset selection (checkbox)
- Tutup modal

**Jika ada yang gagal:**
- Tampilkan pesan error: "Gagal menjadwalkan Y ujian: [daftar error]"
- Error berisi nama mahasiswa dan alasan gagal (konflik, dll)

---

## Alur Visual

```
┌─────────────────────────────────────────────────────────────┐
│  1. Panel Panitia - Tab "Siap"                               │
│     - Tampilkan pendaftaran status: disetujui_kajur         │
│     - User select pendaftaran (checkbox)                    │
│     - Klik "Jadwalkan Batch"                                │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  2. Validasi Awal (openBatchModal)                          │
│     - Cek: minimal 1 pendaftaran dipilih                     │
│     - Cek: semua status = disetujui_kajur                   │
│     - Hitung tanggal minimal (pendaftaran + 7 hari)         │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  3. Input Parameter Jadwal (Modal)                         │
│     - Tanggal ujian (≥ tanggal minimal)                     │
│     - Ruangan (dari opsi ruangan aktif)                     │
│     - Sesi (1-4, sesuai pengaturan)                        │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  4. Validasi Konflik (JadwalConflictService)               │
│     Loop setiap pendaftaran:                                │
│     - Cek jadwal existing di slot (tgl, ruang, sesi)        │
│     - Cek aturan konflik per jenis ujian                    │
│     - Proposal: cek kompatibilitas grouping                │
│     - Catat error jika konflik                              │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  5. Update Data Pendaftaran                                 │
│     Loop pendaftaran yang lolos:                            │
│     - Set tanggal_ujian, ruangan, sesi                      │
│     - Update status = dijadwalkan                           │
│     - Set scheduled_at = now()                              │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  6. Kirim Notifikasi WhatsApp (Queue Job)                   │
│     Loop pendaftaran yang berhasil:                         │
│     - Dispatch WA ke mahasiswa                              │
│     - Dispatch WA ke setiap penguji                          │
└─────────────────────────┬───────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  7. Feedback ke User                                        │
│     - Tampilkan count success & error                      │
│     - Reset selection                                      │
│     - Tutup modal                                           │
└─────────────────────────────────────────────────────────────┘
```

---

## Aturan Konflik Detail

### Jenis Ujian: Seminar Hasil / Sidang Skripsi

**Aturan:** Eksklusif - satu pendaftaran per slot (tanggal, ruangan, sesi)

**Alasan:** Ujian ini membutuhkan fokus penuh dan durasi lebih panjang

### Jenis Ujian: Seminar Proposal

**Aturan:** Boleh grouping jika kompatibel

**Syarat Kompatibilitas:**
1. **Penguji identik:** Penguji 1 dan Penguji 2 harus sama persis dengan pendaftaran lain di slot
2. **Pembimbing overlap:** Minimal 1 pembimbing harus sama antar pendaftaran

**Alasan:** Proposal bisa digabung untuk efisiensi jika penguji sama

---

## Error Handling

### Error yang Mungkin Terjadi

| Error | Penyebab | Solusi |
|-------|----------|--------|
| "Pilih minimal satu pendaftaran" | User tidak memilih checkbox | User harus pilih minimal 1 |
| "Beberapa pendaftaran belum siap" | Ada pendaftaran status bukan `disetujui_kajur` | User hanya pilih yang siap |
| "Tanggal ujian harus minimal..." | Tanggal < H+7 dari pendaftaran | User pilih tanggal yang valid |
| "Terdapat konflik jadwal" | Slot sudah terisi (jenis ujian non-proposal) | Pilih tanggal/ruangan/sesi lain |
| "Grup Proposal tidak kompatibel" | Penguji/pembimbing tidak match | Pilih slot lain atau jadwalkan terpisah |

### Partial Success

- Jika beberapa pendaftaran gagal, yang tetap berhasil akan dijadwalkan
- Error ditampilkan di akhir dengan detail per pendaftaran
- User bisa mencoba ulang untuk yang gagal dengan parameter berbeda

---

## Integrasi dengan Fitur Lain

### Auto-completion Ujian

**Fungsi:** `checkCompletedUjian()` di `JadwalUjians.php`

**Trigger:** Setiap kali panel dibuka

**Logika:**
- Cari pendaftaran status `dijadwalkan` dengan tanggal ujian < sekarang
- Update status menjadi `selesai`
- Set `completed_at` = now()

### Penjadwalan Single (Non-Batch)

**Fungsi:** `scheduleUjian()` di `JadwalUjians.php`

**Perbedaan dengan Batch:**
- Hanya satu pendaftaran
- Modal berbeda (bukan batch modal)
- Flow validasi dan update sama

---

## Catatan untuk Implementor

- Semua validasi konflik dilakukan sebelum update data
- Notifikasi WA bersifat async via queue job
- Error tidak membatalkan seluruh proses batch
- Gunakan **Context7** untuk referensi Laravel/Livewire jika perlu modifikasi
- Pastikan pengaturan jadwal (sesi, jam) sudah di-set sebelum penjadwalan
