# Issue: Fitur Revisi, Alur Nilai Berkas, dan Konsistensi UI

## Ringkasan

Perencanaan high-level untuk melengkapi alur revisi mahasiswa–dosen, integrasi upload nilai berkas ke panitia administrasi, dan standarisasi icon di seluruh sistem.

**Referensi teknis saat implementasi:** gunakan **Context7** untuk dokumentasi Laravel, Livewire, dan TailwindCSS yang relevan.

---

## Konteks Sistem

- **Revisi:** mahasiswa mengunggah berkas perbaikan → dosen memberi instruksi/catatan revisi
- **Nilai:** dosen dapat input nilai via sistem (langsung) atau via berkas (upload); berkas nilai ditujukan untuk dikelola panitia administrasi
- **Komponen terkait yang sudah ada (perlu dilengkapi/dihubungkan):**
  - Route `mahasiswa.revisi` → `App\Livewire\Mahasiswa\DaftarRevisi`
  - Route `dosen.revisi.*` → `DaftarRevisi`, `BerikanRevisi`
  - Route `dosen.nilai.upload` → `UploadNilaiBerkas`
  - Model `Revisi`, `Penilaian`
  - Panel panitia administrasi saat ini hanya memiliki Dashboard dan Laporan

---

## 1. Sidebar Mahasiswa — Menu Revisi

**Masalah:** Route revisi sudah tersedia, namun belum ada akses navigasi di sidebar mahasiswa.

**Scope high-level:**
- Tambahkan menu "Revisi" di `sidebar-mahasiswa.blade.php`
- Hubungkan ke route `mahasiswa.revisi`
- Ikuti pola styling menu sidebar yang sudah ada (icon SVG, active state, grouping section)

**Acceptance criteria:**
- Mahasiswa dapat mengakses halaman revisi dari sidebar
- Menu menampilkan active state saat berada di halaman revisi
- Konsisten secara visual dengan menu sidebar lainnya

---

## 2. Sidebar Dosen — Revisi Mahasiswa

**Masalah:** Dosen perlu melihat daftar revisi mahasiswa dan memberikan instruksi perbaikan.

**Scope high-level:**
- Pastikan menu Revisi di `sidebar-dosen.blade.php` terhubung dengan benar ke alur revisi
- Halaman daftar revisi: tampilkan mahasiswa yang perlu/menunggu revisi
- Halaman berikan revisi: dosen dapat menulis instruksi/catatan perbaikan yang masih perlu dilakukan mahasiswa
- Manfaatkan komponen Livewire yang sudah ada (`DaftarRevisi`, `BerikanRevisi`); sesuaikan UI/UX jika belum memenuhi kebutuhan

**Acceptance criteria:**
- Dosen dapat melihat revisi mahasiswa bimbingan/pengujian dari sidebar
- Dosen dapat memberikan dan menyimpan instruksi revisi
- Mahasiswa dapat melihat instruksi yang diberikan dosen (terintegrasi dengan fitur #1)

---

## 3. Dosen — Perbaikan Layout Upload Nilai Berkas

**Masalah:** Halaman upload nilai via berkas perlu perbaikan layout dan alur agar file masuk ke panitia administrasi.

**Scope high-level:**
- Perbaiki layout halaman `upload-nilai-berkas` agar konsisten dengan halaman nilai dosen lainnya (`berikan-nilai`, `input-nilai-sistem`)
- Pastikan upload berkas nilai tersimpan di model `Penilaian` dengan `tipe_input = berkas`
- File yang diupload harus dapat diakses/dikelola oleh panitia administrasi (bukan hanya tersimpan tanpa alur lanjutan)
- Perjelas di UI bahwa berkas akan diteruskan ke panitia administrasi

**Lokasi utama:**
- `app/Livewire/Dosen/UploadNilaiBerkas.php`
- `resources/views/livewire/dosen/upload-nilai-berkas.blade.php`

**Acceptance criteria:**
- Layout upload berkas rapi dan konsisten dengan halaman dosen lainnya
- File berhasil tersimpan dan terhubung ke data penilaian
- Panitia administrasi dapat melihat berkas yang diupload dosen (lihat fitur #4)

---

## 4. Panitia Administrasi — Kelola Nilai Berkas Dosen

**Masalah:** Belum ada halaman untuk panitia administrasi mengelola nilai berkas yang diupload dosen.

**Scope high-level:**
- Buat halaman baru di panel panitia administrasi untuk melihat dan mengelola nilai berkas dari dosen
- Layout mengikuti pola halaman nilai dosen (tabel daftar, info mahasiswa, status, aksi download/lihat berkas)
- Tambahkan menu di `sidebar-panitia-administrasi.blade.php` dan route di `web.php`
- Buat Livewire component baru di namespace `App\Livewire\Panitia\Administrasi`

**Acceptance criteria:**
- Panitia administrasi dapat melihat daftar nilai berkas yang diupload dosen
- Panitia dapat melihat detail (mahasiswa, dosen, jenis ujian, catatan) dan mengunduh/membuka berkas
- Navigasi tersedia dari sidebar panitia administrasi

---

## 5. Penggantian Emoji dengan Icon

**Masalah:** Beberapa bagian UI masih menggunakan emoji; perlu diganti dengan icon SVG agar lebih profesional dan konsisten.

**Scope high-level:**
- Audit seluruh codebase (view blade, Livewire component) untuk penggunaan emoji
- Ganti dengan icon SVG inline (Heroicons/Flowbite style — sesuai pola yang sudah dipakai di sidebar)
- Prioritaskan file yang tampil ke user: dashboard, verifikasi, WhatsApp test, sekjur, mahasiswa, dosen, panitia

**Contoh lokasi yang teridentifikasi:**
- `resources/views/livewire/mahasiswa/dashboard.blade.php`
- `resources/views/livewire/dosen/kuota-saya.blade.php`
- `resources/views/livewire/sekjur/generate-penguji.blade.php`
- `resources/views/livewire/panitia/verifikasi/verifikasi-berkas.blade.php`
- `resources/views/livewire/whatsapp-test.blade.php`
- `app/Livewire/WhatsAppTest.php`

**Acceptance criteria:**
- Tidak ada emoji di UI yang ditampilkan ke end-user
- Icon memiliki makna visual yang setara dengan emoji sebelumnya
- Style icon konsisten di seluruh aplikasi

---

## Urutan Implementasi (Disarankan)

1. **Sidebar mahasiswa — menu Revisi** — quick win, route sudah ada
2. **Sidebar dosen — alur revisi** — pastikan flow mahasiswa ↔ dosen berjalan
3. **Perbaikan layout upload nilai berkas (dosen)** — persiapan data untuk panitia
4. **Halaman kelola nilai berkas (panitia administrasi)** — konsumsi data dari upload dosen
5. **Penggantian emoji dengan icon** — polish UI, bisa dikerjakan paralel atau terakhir

---

## Catatan untuk Implementor

- Gunakan **Context7** untuk referensi Livewire, file upload (`WithFileUploads`), dan TailwindCSS
- Ikuti konvensi kode dan pola UI yang sudah ada di proyek (sidebar, card, tabel, badge status)
- Manfaatkan model dan route yang sudah tersedia sebelum membuat struktur baru
- Test alur end-to-end: mahasiswa upload revisi → dosen beri instruksi → dosen upload nilai berkas → panitia administrasi kelola berkas
- Cek `tests/Feature/RevisiFlowTest.php` sebagai referensi alur revisi yang sudah ditest
