# PRD — Sistem Informasi Pengelolaan Skripsi (SIP-S)

## 1. Tujuan

Sistem untuk mengelola **skripsi mahasiswa** mulai dari pendaftaran, penjadwalan ujian, hingga penilaian. Berbasis web dengan role: Mahasiswa, Dosen, Panitia, Sekjur, Kajur, Super Admin.

---

## 2. Ruang Lingkup (Scope)

### 2.1 Manajemen Skripsi

| Fitur | Deskripsi |
|-------|-----------|
| Pendaftaran | Mahasiswa daftar ujian (seminar proposal / seminar hasil / sidang skripsi) lengkap dengan berkas |
| Alur Verifikasi | Panitia Verifikasi → Sekjur (assign penguji) → Kajur (setujui) |
| Riwayat & Status | Lacak status pendaftaran: draft → pending → disetujui_panitia → disetujui_sekjur → disetujui_kajur → dijadwalkan → selesai |

### 2.2 Penjadwalan (Scheduling)

| Fitur | Deskripsi |
|-------|-----------|
| Jadwal Tunggal | Panitia Penjadwalan jadwalkan 1 ujian ke ruang + sesi tertentu |
| Jadwal Banyak (Batch) | Panitia pilih beberapa pendaftaran, jadwalkan sekaligus |
| **Grouping (Seminar Proposal)** | Beberapa seminar proposal bisa dijadwalkan di **slot yang sama** (grup) jika memenuhi syarat |
| Aturan Grouping | Penguji harus identik **dan** minimal 1 pembimbing tumpang tindih |
| Konflik | Deteksi otomatis jika dosen atau ruang bentrok |
| Ruangan | CRUD ruang per jurusan, kapasitas menampung grup |
| Sesi Waktu | Atur sesi per hari (pagi/siang/sore), maks 4 sesi |
| Reschedule & Batal | Jadwal bisa diubah atau dibatalkan |

> **Catatan:** Grouping **hanya** untuk seminar proposal. Seminar hasil & sidang skripsi bersifat **eksklusif** (1 slot = 1 mahasiswa).

### 2.3 Penilaian (Assessment)

| Fitur | Deskripsi |
|-------|-----------|
| Input Nilai | Dosen (penguji) input nilai via **sistem** (7 komponen terbobot) atau **berkas** (unggah file) |
| 7 Komponen Penilaian | Presentasi (10%), Penguasaan Materi (15%), Cara Menjawab (10%), Daya Deskripsi (10%), Daya Analisis (20%), Daya Menyimpulkan (15%), Daya Implikasi (20%) |
| Konversi Otomatis | Nilai akhir → A (>85), B (70-85), C (55-70), D (50-55), E (<50) |
| Rata-rata | Sistem rata-rata nilai dari penguji 1 dan penguji 2 |
| Status Nilai | draft → selesai → diverifikasi |
| Lihat Nilai | Mahasiswa bisa lihat rincian nilai per penguji |
| Laporan PDF | Panitia Administrasi download laporan nilai |

> **Catatan:** Penilai adalah **penguji 1 & penguji 2**. Pembimbing **tidak** memberi nilai saat ini.

---

## 3. Role & Hak Akses

| Role | Tanggung Jawab |
|------|----------------|
| **Super Admin** | Kelola jurusan, prodi, user, role, pengaturan sistem |
| **Kajur** | Verifikasi akhir pendaftaran, data master jurusan |
| **Sekjur** | Atur & generate penguji, data master (view) |
| **Panitia Verifikasi** | Verifikasi berkas pendaftaran |
| **Panitia Penjadwalan** | Atur jadwal, ruangan, sesi waktu |
| **Panitia Administrasi** | Kelola nilai berkas, laporan PDF |
| **Dosen** | Input nilai, revisi, lihat jadwal & kuota |
| **Mahasiswa** | Daftar ujian, lihat nilai, revisi, jadwal |

---

## 4. Alur Utama

### 4.1 Alur Seminar Proposal

```
Mahasiswa daftar (proposal + berkas)
  → Panitia Verifikasi periksa berkas
  → Sekjur assign penguji (auto-generate)
  → Kajur setujui
  → Panitia Penjadwalan buat jadwal (bisa grouping)
  → Ujian berlangsung
  → Penguji input nilai & revisi
```

### 4.2 Alur Seminar Hasil & Sidang Skripsi

```
Mahasiswa daftar (data dari proposal diisi otomatis)
  → Panitia Verifikasi periksa berkas
  → Sekjur assign penguji
  → Kajur setujui
  → Panitia Penjadwalan buat jadwal (eksklusif, no grouping)
  → Ujian berlangsung
  → Penguji input nilai & revisi
```

---

## 5. Batasan Sistem

1. **Grouping** hanya untuk seminar proposal — hasil dan sidang wajib slot sendiri
2. Penilaian hanya dari **penguji**, bukan pembimbing
3. Tidak ada REST API — sistem monolithic Livewire
4. Tidak ada integrasi SIAKAD / akademik eksternal
5. Kelompok (grup) adalah konsep runtime, tidak ada entitas grup di database

---

## 6. Non-Functional

- Framework: Laravel + Livewire + Flux + Tailwind
- Database: MySQL
- Auth: Spatie Laravel-Permission (8 role)
- Notifikasi: WhatsApp (Fonnte / Netflie)
- Queue: Database driver

---

## 7. Glossary

| Istilah | Arti |
|---------|------|
| Pendaftaran | Registrasi ujian skripsi oleh mahasiswa |
| Penguji | Dosen yang ditugaskan menilai (penguji 1 & 2) |
| Pembimbing | Dosen pembimbing skripsi (pembimbing 1 & 2) |
| Grouping | Beberapa seminar proposal digabung dalam 1 slot |
| Kuota Dosen | Batas maksimal bimbingan & penguji per bulan |
| Kepakaran | Level/jabatan dosen (Profesor, Lektor, dll) |
| Bidang Keahlian | Area keahlian dosen yang cocok dengan topik skripsi |
