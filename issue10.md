# Issue: Perbaikan UI Filter & Fungsionalitas Filter Data

## Ringkasan

Perencanaan high-level untuk memperbaiki dua area terkait filter di seluruh sistem:
1. **UI Filter** - logo/ikon filter (v) terlalu dekat dengan teks, perlu spacing yang lebih baik
2. **Fungsionalitas Filter** - filter tidak berfungsi saat dipilih, data tidak berubah (contoh: Super Admin > User saat pilih status tidak terjadi apa-apa)

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Livewire, Alpine.js, dan TailwindCSS yang relevan.

---

## Konteks Sistem

### Masalah UI Filter
- Icon/chevron (v) pada filter terlalu dekat dengan teks label
- Masalah ini mungkin terjadi di berbagai halaman yang memiliki filter
- Spacing yang kurang menyebabkan tampilan kurang profesional dan sulit dibaca
- Perlu penyesuaian margin/padding pada elemen filter

### Masalah Fungsionalitas Filter
- Contoh spesifik: Super Admin > User saat memilih status filter, data tidak berubah
- Filter mungkin sudah ada di UI tapi tidak terhubung dengan logic Livewire
- Event listener atau wire:model mungkin tidak terkonfigurasi dengan benar
- Query data mungkin tidak merespon perubahan nilai filter

---

## Scope High-Level

### 1. Perbaiki UI Filter (Spacing Icon)

**Investigasi:**
- Cari semua file blade yang memiliki komponen filter dengan icon/chevron
- Identifikasi pattern yang digunakan untuk icon filter (biasanya svg chevron-down)
- Periksa class Tailwind yang digunakan untuk spacing antara teks dan icon

**Perbaikan:**
- Tambahkan spacing yang cukup antara teks label dan icon filter
- Gunakan class seperti `ml-2`, `gap-2`, atau padding yang sesuai
- Pastikan spacing konsisten di seluruh sistem
- Test visual di berbagai ukuran layar untuk memastikan spacing tetap baik

### 2. Perbaiki Fungsionalitas Filter

**Investigasi:**
- Identifikasi semua halaman yang memiliki filter tapi tidak berfungsi
- Cek Livewire component terkait untuk memastikan property filter ada dan terhubung
- Verifikasi wire:model pada elemen select/filter
- Cek method render atau query data untuk memastikan filter diterapkan

**Perbaikan:**
- Pastikan property filter didefinisikan di Livewire component
- Tambahkan wire:model yang benar pada elemen filter
- Implementasi logic query yang merespon perubahan filter
- Tambahkan event listener jika perlu (misal: wire:change)
- Test filter dengan berbagai nilai untuk memastikan data berubah sesuai

---

## Acceptance Criteria

### UI Filter
- Icon/chevron filter memiliki spacing yang cukup dari teks label
- Spacing konsisten di seluruh halaman yang memiliki filter
- Tampilan filter lebih rapi dan profesional
- Tidak ada overlap antara teks dan icon
- Spacing tetap baik di berbagai ukuran layar

### Fungsionalitas Filter
- Filter berfungsi saat dipilih, data berubah sesuai filter
- Contoh: Super Admin > User filter status berfungsi dan mengubah data yang ditampilkan
- Semua filter yang ada di UI terhubung dengan logic backend
- User bisa melihat perubahan data secara real-time saat mengubah filter
- Tidak ada filter yang "mati" atau tidak responsif

---

## Lokasi File Utama

| Area | File |
|------|------|
| Super Admin User Component | `app/Livewire/SuperAdmin/Users.php` (atau nama similar) |
| Super Admin User View | `resources/views/livewire/super-admin/users.blade.php` (atau nama similar) |
| Filter components | Semua file blade di `resources/views/livewire/` yang memiliki filter |
| Livewire components dengan filter | Semua component di `app/Livewire/` yang memiliki property filter |

---

## Urutan Implementasi (Disarankan)

1. **Audit semua filter** - cari semua lokasi filter di seluruh codebase
2. **Perbaiki UI filter dulu** - tambahkan spacing yang cukup pada icon filter
3. **Perbaiki fungsionalitas filter** - mulai dari contoh spesifik (Super Admin > User)
4. **Test filter yang diperbaiki** - verifikasi UI dan fungsionalitas
5. **Apply pattern ke filter lain** - gunakan solusi yang sama untuk filter lain yang bermasalah
6. **Test comprehensive** - cek semua filter di seluruh sistem

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Livewire reactive properties dan Alpine.js event handling
- Untuk spacing, gunakan Tailwind utility class seperti `gap-2`, `ml-2`, atau `pl-2` pada container atau icon
- Untuk fungsionalitas, pastikan property filter menggunakan proper Livewire reactivity
- Cek apakah perlu menggunakan `updated{PropertyName}()` method untuk handle perubahan filter
- Test filter dengan data yang cukup untuk memastikan perubahan terlihat
- Pastikan tidak ada filter yang tertinggal atau tidak diperbaiki
- Document pattern yang digunakan untuk konsistensi di masa depan
