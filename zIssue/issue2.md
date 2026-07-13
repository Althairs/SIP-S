# Issue: Perbaikan Fitur Sekjur & Penjadwalan Batch

## Ringkasan

Perbaikan tiga isu utama di panel Sekjur dan dokumentasi alur penjadwalan batch:
1. Card "Menunggu Penguji" tidak menampilkan jumlah pendaftaran yang sesuai
2. Bagian skor hilang saat generate penguji (otomatis/manual)
3. Dokumentasi alur penjadwalan batch

---

## Konteks Sistem

- Panel Sekjur: mengelola generate penguji untuk pendaftaran ujian
- Status pendaftaran: `pending` → `disetujui_panitia` → `disetujui_sekjur` → `disetujui_kajur` → `dijadwalkan` → `selesai`
- Fitur generate penguji: mode otomatis dan manual
- Penjadwalan batch: proses penjadwalan massal oleh panitia

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Laravel/Livewire yang relevan.

---

## 1. Perbaikan Card "Menunggu Penguji"

**Masalah:**
Card di panel Sekjur yang menampilkan jumlah pendaftaran "menunggu penguji" tidak menampilkan data yang sesuai dengan jumlah pendaftaran yang seharusnya.

**Scope high-level:**
- Identifikasi query yang menghitung pendaftaran dengan status "menunggu penguji"
- Pastikan filter status yang digunakan sesuai dengan alur bisnis
- Verifikasi bahwa card menampilkan jumlah real-time dari database
- Pastikan tidak ada caching yang menyebabkan data tidak update

**Acceptance criteria:**
- Card menampilkan jumlah pendaftaran yang akurat sesuai status "menunggu penguji"
- Data terupdate secara real-time saat ada perubahan status pendaftaran
- Konsisten dengan data yang tampil di tabel/list pendaftaran

---

## 2. Perbaikan Hilangnya Skor saat Generate Penguji

**Masalah:**
Di bagian Sekjur > Generate Penguji, kolom/bagian skor hilang saat memilih mode otomatis maupun manual.

**Scope high-level:**
- Identifikasi komponen Livewire yang menangani generate penguji
- Pastikan field/kolom skor selalu ditampilkan baik di mode otomatis maupun manual
- Cek apakah ada conditional rendering yang salah menyembunyikan skor
- Verifikasi data skor tersimpan dengan benar di database setelah generate

**Acceptance criteria:**
- Kolom skor selalu terlihat di UI generate penguji (baik otomatis/manual)
- Data skor tersimpan dan ditampilkan dengan benar
- Tidak ada perbedaan tampilan skor antara mode otomatis dan manual

---

## 3. Dokumentasi Alur Penjadwalan Batch

**Tujuan:**
Buat dokumentasi alur penjadwalan batch di file `alur penjadwalan.md` setelah melakukan pemeriksaan fungsi penjadwalan batch yang ada.

**Scope high-level:**
- Telusuri dan pahami fungsi penjadwalan batch yang sudah ada
- Identifikasi semua komponen yang terlibat: model, controller, Livewire component, job/queue
- Dokumentasikan alur dari awal (pendaftaran siap dijadwalkan) sampai selesai (jadwal terbentuk)
- Sertakan diagram atau langkah-langkah proses batch scheduling

**Acceptance criteria:**
- Dokumentasi lengkap alur penjadwalan batch di `alur penjadwalan.md`
- Alur dokumentasi sesuai dengan implementasi kode yang ada
- Mudah dipahami oleh programmer atau model lain untuk implementasi/perbaikan

---

## Urutan Implementasi (Disarankan)

1. **Perbaikan card "Menunggu Penguji"** — investigasi query dan filter status
2. **Perbaikan hilangnya skor** — investigasi conditional rendering di generate penguji
3. **Pemeriksaan fungsi penjadwalan batch** — telusuri kode yang ada
4. **Dokumentasi alur penjadwalan batch** — buat `alur penjadwalan.md`

---

## Referensi File

| Area | File |
|------|------|
| Panel Sekjur Dashboard | `app/Livewire/Sekjur/Dashboard.php` |
| Generate Penguji | `app/Livewire/Sekjur/GeneratePenguji.php` |
| Penjadwalan Batch | `app/Livewire/Panitia/Penjadwalan/JadwalUjians.php` |
| Conflict Service | `app/Services/JadwalConflictService.php` |
| Model Pendaftaran | `app/Models/Pendaftaran.php` |
| Model UjianPenguji | `app/Models/UjianPenguji.php` |

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Laravel/Livewire saat implementasi
- Fokus pada investigasi root cause sebelum melakukan perbaikan
- Dokumentasi penjadwalan batch harus high-level, bukan detail kode
- Pastikan perbaikan tidak merusak fitur yang sudah berjalan
