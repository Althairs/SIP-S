# Penjadwalan Ujian

Dokumentasi aturan penjadwalan ujian (seminar proposal, seminar hasil, sidang skripsi) pada SIP-S.

---

## 1. Ringkasan Aturan

| Jenis Ujian | Bisa di-Grup (1 slot = banyak)? | Aturan |
|-------------|-------------------------------|--------|
| **Seminar Proposal** | ✅ Ya | Bisa digabung dalam 1 slot jika penguji identik & minimal 1 pembimbing sama |
| **Seminar Hasil** | ❌ Tidak | 1 slot = 1 ujian (eksklusif) |
| **Sidang Skripsi** | ❌ Tidak | 1 slot = 1 ujian (eksklusif) |

Definisi "slot" = kombinasi **tanggal + sesi + ruangan**.

---

## 2. Sumber Kode

| Komponen | File |
|----------|------|
| UI Penjadwalan | `app/Livewire/Panitia/Penjadwalan/JadwalUjians.php` |
| Validasi Konflik & Grouping | `app/Services/JadwalConflictService.php` |

---

## 3. Cara Kerja Validasi

`JadwalConflictService::validateSchedule()` (`jadwal-conflict-service:15`) dipanggil **setiap kali** satu ujian akan dijadwalkan, baik single maupun batch:

1. Cari pendaftaran berstatus `dijadwalkan` lain di **slot yang sama** (tanggal + sesi + ruangan)
2. Jika slot kosong → **boleh** jadwalkan
3. Jika slot terisi:
   - Ujian yang baru **hasil/sidang** → `ValidationException` (slot penuh) → **ditolak**
   - Ujian yang baru **proposal**:
     - Jika penghuni slot bukan proposal (hasil/sidang) → **ditolak**
     - Jika semua penghuni proposal → cek grouping (`canGroupProposal`)

### Syarat Grouping Proposal (`canGroupProposal`, `jadwal-conflict-service:58`)

Semua proposal dalam 1 slot harus:

1. **Penguji identik** — set `penguji_1` + `penguji_2` sama persis dengan proposal referensi
2. **Minimal 1 pembimbing sama** — irisan antara pembimbing_1/pembimbing_2 tidak kosong
3. Proposal referensi harus sudah punya penguji (minimal 1)

Jika salah satu tidak terpenuhi → `ValidationException` → ujian **tidak** dijadwalkan.

---

## 4. Alur Batch (`scheduleBatchUjian`, `jadwal-ujians:289`)

1. Panitia mencentang beberapa pendaftaran di tab **Siap** (status `disetujui_kajur`)
2. Klik **Jadwalkan Batch**, pilih tanggal/sesi/ruangan
3. Sistem memproses **satu per satu** (looping):
   - Tiap pendaftaran dicek `validateSchedule()` ke slot yang sama
   - **Proposal yang kompatibel** → berhasil digabung di slot yang sama (ber-grup)
   - **Proposal yang tidak kompatibel / hasil / sidang** (saat slot sudah terisi) → gagal, muncul pesan error per mahasiswa
4. Yang gagal **tetap berstatus** `disetujui_kajur` (tidak dijadwalkan), tinggal dijadwalkan ulang di slot lain
5. Notifikasi WhatsApp dikirim per mahasiswa & dosen penguji yang berhasil

---

## 5. Verifikasi: Apakah Sudah Sesuai Kebutuhan?

### ✅ Batching untuk Proposal — **BERHASIL**

- Batch beberapa proposal dengan penguji sama & pembimbing tumpang tindih → semua masuk **1 slot** (grup)
- Batch campuran proposal tidak kompatibel → yang tidak kompatibel gagal, yang lain tetap dijadwalkan
- Batch campuran hasil/sidang + proposal → tetap aman:
  - Proposal tidak bisa masuk slot yang sudah ada hasil/sidang
  - Hasil/sidang tidak bisa masuk slot yang sudah ada proposal

### ✅ Hanya 1 Jadwal per Sesi untuk Hasil & Sidang — **BERHASIL**

- Kode menjamin: slot hasil/sidang **tidak pernah** bisa berisi lebih dari 1 pendaftaran (`jadwal-conflict-service:31-35` melempar `ValidationException`)
- Implikasi: jika batch memilih **beberapa hasil/sidang sekaligus**, hanya yang pertama yang sukses di slot tersebut; sisanya gagal dan perlu dijadwalkan manual di slot lain

---

## 6. Catatan & Keterbatasan

| No | Catatan |
|----|---------|
| 1 | Batch hasil/sidang tidak otomatis menyebar ke ruang/sesi lain — yang bertabrakan hanya **ditolak**, bukan dialihkan |
| 2 | Tidak ada batas maksimum ukuran grup proposal (bisa lebih dari 3 mahasiswa per slot jika memenuhi syarat) |
| 3 | Durasi ujian tidak diperhitungkan (mis. proposal 30 menit vs sidang 120 menit) — hanya 1 slot per sesi |
| 4 | Referensi grouping memakai pendaftaran **pertama** di slot; anggota lain hanya dibandingkan terhadap referensi |
| 5 | Aturan H+7 (minimal 7 hari dari pendaftaran) berlaku untuk tanggal ujian |
| 6 | Ada menu "Auto Generate" (`autoGenerateJadwal`) yang memakai `rand()` — hanya untuk demo/pengujian, bukan untuk produksi |
