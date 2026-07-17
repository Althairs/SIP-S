# Issue: Admin Akses Seluruh Jurusan & Perbaikan Filter

## Masalah 1: Admin Tidak Bisa Melihat Data dari Semua Jurusan

Admin (super_admin) yang mengakses halaman seperti Data Dosen, Data Mahasiswa, dll. tidak melihat data apapun. Ini karena semua komponen Kajur/Sekjur/Panitia/Dosen/Mahasiswa melakukan hard-scope query ke `auth()->user()->jurusan_id`. Karena super_admin tidak memiliki jurusan_id (null), hasil query-nya kosong.

**Komponen yang bermasalah** (semua di `app/Livewire/`):

| Komponen | Baris | Scoping |
|---|---|---|
| `Kajur/DosenIndex` | 58, 176, 237, 240 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/MahasiswaIndex` | 144, 151, 236, 251-252 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/PanitiaIndex` | 149, 156, 203, 206 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/KuotaDosen` | 48, 88, 105, 142, 146 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/AturAtributDosen` | 146, 149, 178, 185-188 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/BidangKeahlians` | 79, 112, 114 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/Kepakaran` | (sama) | `->where('jurusan_id', $jurusanId)` |
| `Kajur/Dashboard` | 44-52 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/VerifikasiSeminarProposal` | 51, 54 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/VerifikasiSeminarHasil` | 12, 15 | `->where('jurusan_id', $jurusanId)` |
| `Kajur/VerifikasiSidangSkripsi` | (sama) | `->where('jurusan_id', $jurusanId)` |
| `Sekjur/Dashboard` | 25, 27 | `->where('jurusan_id', $jurusanId)` |
| `Sekjur/PengujiIndex` | 33, 37, 59, 64 | `->where('jurusan_id', $jurusanId)` |
| `Sekjur/GeneratePenguji` | 68, 76 | `->where('jurusan_id', $jurusanId)` |
| `Panitia/*` semua | banyak | `->where('jurusan_id', $jurusanId)` |

### Solusi

Di setiap komponen di atas, ubah pola scoping dari:

```php
$jurusanId = auth()->user()->jurusan_id;
$query->where('jurusan_id', $jurusanId);
```

Menjadi:

```php
$user = auth()->user();
$query->when(!$user->hasRole('super_admin'), fn($q) => $q->where('jurusan_id', $user->jurusan_id));
```

Atau cara yang lebih rapi: buat **global scope** atau **helper method** di service/PermissionService:

```php
public static function scopeByJurusan($query) {
    $user = auth()->user();
    if (!$user || $user->hasRole('super_admin')) return $query;
    return $query->where('jurusan_id', $user->jurusan_id);
}
```

Dengan pendekatan ini: super_admin melihat SEMUA data dari semua jurusan, sementara role lain tetap terbatas ke jurusan masing-masing.

---

## Masalah 2: Filter Tidak Berfungsi / Perlu Refresh

Beberapa filter dropdown di Livewire component tidak mereset pagination saat berubah, atau tidak persist state saat di-refresh.

### a. Missing `updated*()` Methods (filter change tidak reset pagination)

Tambah method `updated{NamaFilter}()` yang memanggil `$this->resetPage()` di komponen berikut:

| Komponen | Filter | File |
|---|---|---|
| `Admin/JurusanIndex` | `$filterStatus` | `app/Livewire/Admin/JurusanIndex.php:18` |
| `Kajur/KuotaDosen` | `$prodiFilter` | `app/Livewire/Kajur/KuotaDosen.php:22` |
| `Kajur/VerifikasiSeminarProposal` | `$statusFilter` | `app/Livewire/Kajur/VerifikasiSeminarProposal.php:18` |
| `Panitia/Verifikasi/VerifikasiBerkas` | `$statusFilter` | `app/Livewire/Panitia/Verifikasi/VerifikasiBerkas.php:18` |

### b. Missing `#[Url]` Attribute (filter tidak persist di URL)

Tambah `#[Url(history: true)]` ke property filter berikut:

| Komponen | Filter | File |
|---|---|---|
| `Panitia/Administrasi/KelolaNilaiBerkas` | `$search` | `app/Livewire/Panitia/Administrasi/KelolaNilaiBerkas.php:14` |
| `Panitia/Administrasi/KelolaNilaiBerkas` | `$statusFilter` | `app/Livewire/Panitia/Administrasi/KelolaNilaiBerkas.php:15` |

### c. Wrong `wire:model` Binding (menggunakan plain `wire:model`, bukan `.change`)

Ubah binding di view dari `wire:model` menjadi `wire:model.change`:

| View | Filter | Baris |
|---|---|---|
| `kajur/panitia-index.blade.php` | `roleFilter` | 135 |
| `kajur/panitia-index.blade.php` | `prodiFilter` | 141 |

---

## File yang Perlu Diubah

**Jurusan scoping fix (~30 files):**
- `app/Livewire/Kajur/*.php` — semua komponen
- `app/Livewire/Sekjur/*.php` — semua komponen
- `app/Livewire/Panitia/*/*.php` — semua komponen
- Opsional: `app/Services/PermissionService.php` — tambah helper `scopeByJurusan()`

**Filter fix (6 files):**
- `app/Livewire/Admin/JurusanIndex.php`
- `app/Livewire/Kajur/KuotaDosen.php`
- `app/Livewire/Kajur/VerifikasiSeminarProposal.php`
- `app/Livewire/Panitia/Verifikasi/VerifikasiBerkas.php`
- `app/Livewire/Panitia/Administrasi/KelolaNilaiBerkas.php`
- `resources/views/livewire/kajur/panitia-index.blade.php`

## Prioritas Pengerjaan

1. **Helper `scopeByJurusan()`** di PermissionService — sekali buat, bisa dipakai di semua komponen
2. **Terapkan helper ke semua komponen Kajur/Sekjur/Panitia** — super_admin bisa lihat semua data
3. **Fix filter yang missing `updated*()`** — tambah method di 4 komponen
4. **Fix filter yang missing `#[Url]`** — tambah attribute di KelolaNilaiBerkas
5. **Fix binding `wire:model`** — ganti ke `wire:model.change`
