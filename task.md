Berikut adalah draf prompt terstruktur dengan bahasa *high-level* yang dirancang khusus agar AI coding assistant (seperti OpenCode atau Claude) dapat memahami konteks, arsitektur, dan instruksi kerja Anda dengan sempurna tanpa kehilangan detail teknis yang krusial.

---

# Prompt Instruksi Pengembangan: Refactoring Navigasi, Skema Warna, dan Arsitektur Komponen Livewire

## Peran & Konteks Sistem

Anda adalah seorang **Senior Full-Stack Developer** yang ahli dalam ekosistem **Laravel, Livewire, Tailwind CSS, dan Spatie Laravel-Permission**.

Tugas Anda adalah melakukan refactoring besar-besaran pada sistem aplikasi manajemen akademik ini. Fokus utama Anda adalah meningkatkan efisiensi kode, konsistensi visual (menggunakan palet warna tema Pertanian), serta mengubah UX komponen dari pola *Modal Overlay* menjadi *Inline Card View* yang dilengkapi *Breadcrumb*.

---

## Ringkasan Arsitektur & Aturan Utama

Sebelum menulis kode, patuhi aturan arsitektur berikut:

1. **Pola Sidebar Master:** Gabungkan 8 file sidebar berbasis role menjadi satu file komponen tunggal memanfaatkan direktif Spatie (`@role`, `@hasanyrole`). Gunakan `match(true)` untuk penentuan `$homeRoute` secara dinamis.
2. **Skema Warna (Tema Hijau):** Standardisasi semua elemen aksen utama (CTA, header, hover, aktif) dari warna *indigo/blue/purple/teal* ke **Tailwind `green-***` (misal: `green-50` untuk hover, `green-700` untuk teks/ikon aktif).
*  **Pengecualian Penting:** **PERTAHANKAN** warna semantik untuk status atau menu khusus berikut: `rose-*` (Kuota Dosen, Role & Akses), `yellow-*` (Seminar Hasil), `red-*` (Sidang Skripsi, Tombol Sign Out) dan yang ada di controller.


3. **Pola Refaktor Livewire (Modal ➔ Inline):** Ubah komponen dari *floating modal* (`fixed inset-0`) menjadi *inline card conditional* (`@if($showForm)`).
* Saat form/detail aktif, **tabel utama harus disembunyikan dari DOM**.
* Wajib menyertakan *Dynamic Breadcrumb* di atas card dengan format `Nama Halaman > Aksi Aktif`.



---

## Rencana Eksekusi Bertahap (Tabel Tugas)

Jalankan implementasi secara sekuensial berdasarkan urutan ketergantungan berikut:

| Fase | Komponen / Target | Detail Implementasi |
| --- | --- | --- |
| **Fase 1: Navigasi Master** | `sidebar.blade.php` & `app-auth.blade.php` | • Satukan menu untuk role `super_admin`, `mahasiswa`, `dosen`, `kajur`, `sekjur`, dan aliansi `panitia_*`. <br>

<br>• Pindahkan semua logika *badge counter* dan *fallback* `@unless` untuk user tanpa role.<br>

<br>• Hapus 7 file sidebar lama dan ganti panggilannya di `app-auth` menjadi `<x-navigation.sidebar />`. |
| **Fase 2: Penyelarasan Warna** | Seluruh Views & Komponen Livewire | • Audit dan ubah kelas warna aksen di direktori `livewire/kajur/`, `dosen/`, `mahasiswa/`, `panitia/`, `sekjur/`, dan `admin/` menjadi varian `green-*`. <br>

<br>• Pastikan fungsi `npm run build` dijalankan untuk kompilasi kelas Tailwind baru. |
| **Fase 3: Refaktor UX Livewire** | 15 Komponen (Kajur, Panitia, Dosen, Mahasiswa) | • Ubah properti state dari `$showModal` menjadi `$showForm` (atau `$showDetail`).<br>

<br>• **Khusus `jadwal-ujians.blade.php**`: Jangan gunakan multiple boolean. Refaktor menjadi satu status `public string $modalMode` untuk mengontrol 3 jenis tampilan card (schedule/batch/detail). |
| **Fase 4: Perbaikan Diksi** | Dashboard Penjadwalan | • Ubah label teks statis dari `"Konteks Proses"` menjadi `"Status Penjadwalan"` pada widget ringkasan halaman dashboard penjadwalan. |

---

## Spesifikasi Automated Testing (Terdokumentasi)

Setiap fitur yang Anda refaktor harus divalidasi dengan test suite menggunakan PHPUnit/Pest. Tulis dan pastikan seluruh test berikut lulus:

1. **`SidebarRoleIsolationTest.php` (Property 1 & 2):**
* Assert bahwa menu terisolasi dengan ketat; role yang aktif hanya melihat menu miliknya dan tidak bisa melihat menu role lain (lakukan minimal 8 iterasi untuk setiap role).
* Assert seluruh route dari inventaris navigasi lama tersedia lengkap di Sidebar Master.


2. **`SidebarFileStructureTest.php` (Smoke Test):**
* Assert file `sidebar.blade.php` ada, dan assert 7 file lama telah terhapus dari repositori.
* Assert tidak ada string komponen lama (seperti `x-navigation.sidebar-kajur`, dll.) di seluruh file Blade.


3. **`KajurInlineFormTest.php` & `InlineFormStateTest.php` (State & Validasi):**
* Test state transition: `openCreate()` merubah `$showForm = true` dan `closeForm()` mereset data.
* Assert jika validasi atau penyimpanan database gagal, form **harus tetap terbuka** (`$showForm = true`) dan data input tidak hilang.


4. **`InlineFormBreadcrumbTest.php` (UX Property):**
* Assert struktur breadcrumb menghasilkan 2 segmen dinamis yang mencerminkan aksi yang sedang berjalan.
* Assert elemen tabel menghilang ketika `$showForm` bernilai `true`.



---

> **Instruksi Eksekusi:** Mulailah dari **Fase 1**, lakukan pengerjaan kode secara rapi dan bersih. Tuliskan kode untuk komponen sidebar master terlebih dahulu beserta test-nya, lalu konfirmasikan jika Anda siap melanjutkan ke fase penyelarasan warna dan refaktor Livewire.
