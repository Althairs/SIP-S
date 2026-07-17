# Issue: Redesign Dashboard — Futuristik (z-contohdashboard)

## Tujuan

Mengubah tampilan seluruh halaman dashboard role-based di SIP-S mengikuti pola desain yang ada di folder `z-contohdashboard/` agar terlihat lebih modern, futuristik, dan konsisten.

## Daftar Dashboard yang Diubah

| Role | Livewire Class | Blade View Saat Ini |
|------|----------------|---------------------|
| Super Admin | `app/Livewire/Admin/dashboard.php` | `resources/views/livewire/admin/dashboard.blade.php` |
| Kajur | `app/Livewire/Kajur/Dashboard.php` | `resources/views/livewire/kajur/dashboard.blade.php` |
| Sekjur | `app/Livewire/Sekjur/Dashboard.php` | `resources/views/livewire/sekjur/dashboard.blade.php` |
| Dosen | `app/Livewire/Dosen/Dashboard.php` | `resources/views/livewire/dosen/dashboard.blade.php` |
| Panitia Verifikasi | `app/Livewire/Panitia/Verifikasi/Dashboard.php` | `resources/views/livewire/panitia/verifikasi/dashboard.blade.php` |
| Panitia Penjadwalan | `app/Livewire/Panitia/Penjadwalan/Dashboard.php` | `resources/views/livewire/panitia/penjadwalan/dashboard.blade.php` |
| Panitia Administrasi | `app/Livewire/Panitia/Administrasi/Dashboard.php` | `resources/views/livewire/panitia/administrasi/dashboard.blade.php` |

## Acuan Desain: `z-contohdashboard/`

Folder ini berisi 7 file contoh komponen Blade dengan gaya futuristik:
- **ecommerce.blade.php** — layout utama yang merangkai sub-komponen via `<x-ecommerce.*>`
- **ecommerce-metrics.blade.php** — kartu metrik (icon + angka + trend %)
- **statistics-chart.blade.php** — kartu chart dengan tab filter + date range picker (flatpickr)
- **monthly-sale.blade.php** / **monthly-target.blade.php** — kartu progress / target
- **recent-orders.blade.php** — tabel data dengan status badge + tombol filter
- **customer-demographic.blade.php** — kartu demografi (pie/progress)

### Ciri Visual yang Wajib Diikuti

| Aspek | Spesifikasi |
|-------|-------------|
| Card | `rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]` |
| Icon card | `flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl` |
| Teks | `text-title-sm`, `text-theme-sm`, `text-sm text-gray-500 dark:text-gray-400` |
| Judul card | `text-lg font-semibold text-gray-800 dark:text-white/90` |
| Trend badge | `rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600` |
| Tabel | Clean, `border-t border-gray-100`, status badges `rounded-full px-2 py-0.5 text-theme-xs font-medium` |
| Tombol filter | `rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium shadow-theme-xs` |
| Dark mode | Semua komponen harus punya varian `dark:` |
| Interaktivitas | Alpine.js (`x-data`, `@click`, `:class`, `x-show`) untuk tab/filter/dropdown |

## Arsitektur Target

Setiap dashboard view dipecah menjadi komponen-komponen Blade kecil yang dirangkai di file utama, mengikuti pola `z-contohdashboard/ecommerce.blade.php`:

```
resources/views/
├── components/
│   ├── dashboard/             # komponen bersama (shared)
│   │   ├── metrics-card.blade.php
│   │   ├── data-table.blade.php
│   │   ├── chart-card.blade.php
│   │   └── welcome-card.blade.php
│   ├── kajur/                 # komponen spesifik Kajur
│   │   ├── alur-pendaftaran.blade.php
│   │   └── aktivitas-terbaru.blade.php
│   ├── dosen/
│   │   ├── fokus-tugas.blade.php
│   │   ├── jadwal-hari-ini.blade.php
│   │   └── quick-actions.blade.php
│   ├── panitia-verifikasi/
│   │   └── ...
│   ├── panitia-penjadwalan/
│   │   └── ...
│   └── panitia-administrasi/
│       └── ...
└── livewire/
    ├── kajur/
    │   └── dashboard.blade.php    # routing: <x-kajur.alur-pendaftaran /> dll
    ├── dosen/
    │   └── dashboard.blade.php
    └── ...
```

File view Livewire utama (`dashboard.blade.php`) hanya berisi grid layout dan `<x-*>` component references — **tanpa HTML berulang** (monolitik).

## Alur Implementasi per Role

1. **Baca Livewire PHP class** role tersebut — catat semua properti/data yang dikirim ke view (`$totalDosen`, `$jadwalHariIni`, `$recentPendaftarans`, dll).
2. **Identifikasi blok visual** di Blade view saat ini — kelompokkan menjadi komponen mandiri (metrics card, tabel, chart, progress bar, dll).
3. **Buat Blade component** di `resources/views/components/{role}/` — gunakan `@props` untuk menerima data, terapkan styling dari `z-contohdashboard`.
4. **Rewrite view Livewire** (`resources/views/livewire/{role}/dashboard.blade.php`) — gunakan grid `grid grid-cols-12 gap-4 md:gap-6` dan panggil komponen via `<x-role.nama-komponen />`.
5. **Pastikan semua data binding tetap berfungsi** — Livewire properti akan dioper sebagai props ke komponen.

## Data per Role (Ringkasan)

| Role | Data Utama untuk Dashboard |
|------|---------------------------|
| **Kajur** | greeting, jurusanNama, totalMenungguKajur, totalSiapDijadwalkan, totalDijadwalkan, totalDosen, totalMahasiswa, totalPanitia (verifikasi/penjadwalan/administrasi), recentPendaftarans |
| **Sekjur** | greeting, totalMenungguSekjur, totalDisetujuiSekjur, totalDitolak, totalPA, totalMahasiswa, recentPendaftarans |
| **Dosen** | totalMenguji, totalRevisi, kuotaPembimbing, kuotaPenguji, jadwalHariIni, totalNilaiPerluInput, pendingRevisis, jadwalMendatang |
| **Panitia Verifikasi** | totalMenunggu, totalDiverifikasi, totalDitolak, recentPengajuan, daftarPending |
| **Panitia Penjadwalan** | totalMenungguJadwal, totalTerjadwal, totalHariIni, jadwalHariIni, recentJadwal |
| **Panitia Administrasi** | totalSelesai, totalPengajuanAktif, recentBerkas, statusDokumen |
| **Super Admin** | totalUsers, totalJurusan, totalMahasiswa, totalDosen, totalPanitia, aktivitasTerbaru, statistikSistem |

## Aturan & Catatan Penting

### Context7
Gunakan **Context7** sebagai referensi selama implementasi:
- `resolve-library-id` + `query-docs` untuk dokumentasi Laravel, Livewire, Tailwind CSS, Alpine.js bila diperlukan
- Patuhi style, pattern, dan convention yang sudah ada di project (Spatie Permission, struktur Livewire, blade layout)

### Larangan
- Jangan mengubah logika PHP di Livewire class — hanya view (Blade) yang diubah
- Jangan menghapus atau mengubah route
- Jangan merusak fitur dark mode yang sudah ada

### Keharusan
- Setiap komponen harus punya dark mode (`dark:` variant)
- Ukuran font konsisten: `text-theme-xs`, `text-theme-sm`, `text-title-sm`, `text-lg`
- Icon gunakan SVG inline (copy pola dari `z-contohdashboard`)
- Testing: pastikan setiap dashboard tidak error setelah refactor

## Contoh Template Component

### metrics-card.blade.php (shared component)
```blade
@props(['icon', 'label', 'value', 'trend' => null, 'trendUp' => true])

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
        {!! $icon !!}
    </div>
    <div class="flex items-end justify-between mt-5">
        <div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $value }}</h4>
        </div>
        @if($trend)
        <span class="flex items-center gap-1 rounded-full {{ $trendUp ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }} py-0.5 pl-2 pr-2.5 text-sm font-medium">
            {{ $trendUp ? '↑' : '↓' }} {{ $trend }}
        </span>
        @endif
    </div>
</div>
```

## Urutan Pengerjaan

1. Buat shared components (`metrics-card`, `data-table`, `chart-card`, `welcome-card`)
2. Dashboard **Kajur** (paling lengkap, jadi patokan)
3. Dashboard **Sekjur**
4. Dashboard **Dosen**
5. Dashboard **Panitia Verifikasi**
6. Dashboard **Panitia Penjadwalan**
7. Dashboard **Panitia Administrasi**
8. Dashboard **Super Admin**

---

**Referensi**: Lihat semua file di `z-contohdashboard/` untuk detail styling, interaksi Alpine.js, dan pola komponen.
