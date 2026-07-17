# Issue: Restrukturisasi Label "Hierarki" & Redesign Dashboard Kajur

## Context
Aplikasi SIP-S saat ini menggunakan istilah "Hierarki" untuk merujuk pada level jabatan fungsional dosen (Profesor, Lektor Kepala, Lektor, dll). Label ini perlu diganti ke "Jabatan Fungsional" agar lebih sesuai dengan konteks akademik. Selain itu, Dashboard Kajur perlu didesain ulang agar lebih menarik dan interaktif tanpa section "Akses Cepat".

---

## Task 1: Ganti Label "Hierarki" → "Jabatan Fungsional"

### Scope
- **Hanya mengubah teks/label di view Blade** (`*.blade.php`)
- **TIDAK mengubah** nama kolom database (`hierarki_level`), model PHP, atau logic apapun
- Field `hierarki_level` di database tetap seperti adanya

### File yang perlu diubah

| File | Perubahan |
|------|-----------|
| `resources/views/livewire/kajur/kepakaran.blade.php` | "Hierarki Kepakaran" → "Jabatan Fungsional", "Visualisasi Hierarki" → "Visualisasi Jabatan Fungsional", "Level Hierarki" → "Level Jabatan Fungsional", label input form |
| `resources/views/livewire/kajur/dosen-index.blade.php` | "Kepakaran (Hierarki Dosen)" → "Kepakaran (Jabatan Fungsional)", "Level X" → "Jabatan Fungsional X" |
| `resources/views/livewire/kajur/atur-atribut-dosen.blade.php` | "Lv.X" → "Jabatan Fungsional X" pada display |
| `resources/views/livewire/sekjur/generate-penguji.blade.php` | "Hierarki Tertinggi" → "Jabatan Fungsional Tertinggi" |

### Catatan
- `wireframe.blade.php` adalah file desain/draft, **tidak perlu diubah**
- Variabel `$k->hierarki_level` tetap dipanggil apa adanya dari PHP (jangan diganti)

---

## Task 2: Redesign Dashboard Kajur

### File target
- `resources/views/livewire/kajur/dashboard.blade.php` (view)
- `app/Livewire/Kajur/Dashboard.php` (component - mungkin perlu tambah data)

### Data yang sudah tersedia di Dashboard.php
```
$totalDosen, $totalMahasiswa, $totalPanitia
$totalPanitiaVerifikasi, $totalPanitiaPenjadwalan, $totalPanitiaAdministrasi
$totalMenungguKajur, $totalSiapDijadwalkan, $totalDijadwalkan
$jurusanNama
```

### Yang harus dibuat
1. **Welcome card** — pertahankan, tapi tambahkan greeting dinamis (Selamat Pagi/Siang/Sore) berdasarkan waktu
2. **Ringkasan Status Pendaftaran** — card yang menampilkan alur proses:
   - Menunggu Persetujuan Kajur → Siap Dijadwalkan → Sudah Dijadwalkan
   - Tampilkan sebagai progress ring atau bar chart sederhana
3. **Stat cards** — pertahankan total Dosen/Mahasiswa/Panitia, tapi tambahkan icon warna-warni yang lebih menarik
4. **Status Panitia** — tampilkan breakdown panitia (verifikasi/penjadwalan/administrasi) sebagai mini bar atau pie chart
5. **Aktivitas Terbaru** (opsional) — ambil 5 pendaftaran terbaru dari database untuk ditampilkan sebagai timeline

### Yang TIDAK boleh dibuat
- **Tidak ada section "Akses Cepat"** atau quick links
- Tidak ada shortcut menu

### Teknologi
- Gunakan **Context7** untuk referensi Tailwind CSS chart/card patterns
- Pertimbangkan gunakan Alpine.js untuk animasi interaktif (sudah tersedia di project)
- Pastikan menggunakan skema warna green-* (emerald/green) yang sudah konsisten

---

## Referensi
- Skema warna: `resources/views/public/index.blade.php` (landing page sebagai referensi warna)
- Layout: `resources/views/components/layouts/app-auth.blade.php`
