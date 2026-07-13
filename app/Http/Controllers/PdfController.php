<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Generate PDF for Mahasiswa guide
     */
    public function mahasiswa()
    {
        $data = [
            'title' => 'PANDUAN PENGGUNAAN SISTEM INFORMASI PENGELOLAAN SKRIPSI (SIP-S)',
            'role' => 'MAHASISWA',
            'sections' => [
                [
                    'title' => 'BAB I : PENDAHULUAN',
                    'content' => 'Mahasiswa merupakan pengguna utama dalam Sistem Informasi Pengelolaan Skripsi (SIP-S). Melalui sistem ini, mahasiswa dapat melakukan pendaftaran ujian, mengunggah berkas persyaratan, melihat jadwal ujian, melihat nilai, serta mengelola revisi secara daring. Panduan ini menjelaskan langkah-langkah penggunaan sistem dari sudut pandang mahasiswa sesuai dengan alur proses yang berlaku di Jurusan Agribisnis Universitas Negeri Gorontalo.'
                ],
                [
                    'title' => 'BAB II : PROSEDUR PENGGUNAAN SISTEM',
                    'content' => '1. LOGIN : Akses sistem menggunakan akun yang telah terdaftar. Buka halaman login SIP-S melalui browser, masukkan alamat email dan kata sandi, kemudian klik tombol Login. Hasil yang diperoleh adalah akses ke halaman Dashboard dan seluruh fitur sesuai hak akses.'
                ],
                [
                    'title' => '2. DASHBOARD',
                    'content' => 'Halaman utama yang menampilkan ringkasan informasi terkait proses skripsi yang sedang dijalani, meliputi status pendaftaran ujian, jadwal ujian yang akan datang, status verifikasi berkas, dan pengumuman terkait pelaksanaan seminar atau sidang skripsi.'
                ],
                [
                    'title' => '3. PENDAFTARAN UJIAN',
                    'content' => 'Mahasiswa dapat mengajukan permohonan ujian seminar proposal, seminar hasil, atau sidang skripsi melalui menu Pendaftaran. Langkah-langkah: pilih jenis ujian, isi formulir pendaftaran (judul skripsi dan abstrak), unggah dokumen persyaratan (file draft skripsi format PDF, lembar persetujuan bimbingan, scan KRS, scan transkrip nilai), kemudian klik Kirim Pendaftaran. Hasil yang diperoleh adalah pendaftaran tersimpan dengan status Menunggu Verifikasi.'
                ],
                [
                    'title' => '4. UPLOAD BERKAS',
                    'content' => 'Melengkapi berkas persyaratan yang dibutuhkan dalam proses pendaftaran ujian. Pada halaman Pendaftaran, pilih jenis dokumen, klik Pilih File dan pilih file dari perangkat, pastikan file dalam format PDF dengan ukuran maksimal 5MB, kemudian klik Upload.'
                ],
                [
                    'title' => '5. MELIHAT JADWAL UJIAN',
                    'content' => 'Mengetahui jadwal pelaksanaan ujian yang telah ditetapkan oleh panitia penjadwalan melalui menu Jadwal Ujian. Informasi yang ditampilkan meliputi tanggal dan waktu pelaksanaan ujian, ruangan, nama dosen penguji, dan sesi ujian.'
                ],
                [
                    'title' => '6. MELIHAT NILAI UJIAN',
                    'content' => 'Mengetahui hasil penilaian yang diberikan oleh dosen penguji setelah pelaksanaan ujian melalui menu Nilai. Informasi yang ditampilkan meliputi nilai setiap komponen penilaian, nilai akhir, grade/huruf mutu, dan status kelulusan.'
                ],
                [
                    'title' => '7. PENGELOLAAN REVISI',
                    'content' => 'Melihat dan menyelesaikan catatan revisi yang diberikan oleh dosen setelah ujian melalui menu Revisi. Mahasiswa dapat membaca poin-poin revisi, melakukan perbaikan sesuai catatan, dan mengunggah file revisi yang telah diperbaiki. Status revisi akan berubah menjadi Selesai setelah disetujui oleh dosen.'
                ],
                [
                    'title' => 'BAB III : PENUTUP',
                    'content' => 'Untuk mengakhiri sesi penggunaan sistem, klik ikon profil atau nama pengguna di pojok kanan atas halaman, kemudian pilih menu Logout atau Keluar. Mahasiswa akan keluar dari sistem dan diarahkan kembali ke halaman login.'
                ]
            ]
        ];

        $pdf = Pdf::loadView('pdf.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('PANDUAN_PENGGUNA_SIP-S_MAHASISWA.pdf');
    }

    /**
     * Generate PDF for Dosen guide
     */
    public function dosen()
    {
        $data = [
            'title' => 'PANDUAN PENGGUNAAN SISTEM INFORMASI PENGELOLAAN SKRIPSI (SIP-S)',
            'role' => 'DOSEN',
            'sections' => [
                [
                    'title' => 'BAB I : PENDAHULUAN',
                    'content' => 'Dosen berperan sebagai pembimbing dan penguji dalam proses seminar proposal, seminar hasil, dan sidang skripsi. Melalui Sistem Informasi Pengelolaan Skripsi (SIP-S), dosen dapat memantau mahasiswa bimbingan, memberikan revisi, mengisi nilai ujian, melihat jadwal menguji, serta memantau kuota pembimbing dan penguji. Panduan ini menjelaskan langkah-langkah penggunaan sistem dari sudut pandang dosen.'
                ],
                [
                    'title' => 'BAB II : PROSEDUR PENGGUNAAN SISTEM',
                    'content' => '1. LOGIN : Akses sistem menggunakan akun dosen yang telah terdaftar. Buka halaman login SIP-S melalui browser, masukkan alamat email dan kata sandi, kemudian klik tombol Login. Hasil yang diperoleh adalah akses ke halaman Dashboard dan seluruh fitur sesuai hak akses.'
                ],
                [
                    'title' => '2. DASHBOARD',
                    'content' => 'Halaman utama yang menampilkan ringkasan informasi terkait tugas sebagai dosen, meliputi jumlah mahasiswa bimbingan, jumlah ujian yang akan diuji, jadwal menguji terdekat, dan notifikasi revisi yang perlu diproses.'
                ],
                [
                    'title' => '3. TUGAS SAYA',
                    'content' => 'Mengetahui daftar mahasiswa yang menjadi tanggung jawab dosen sebagai pembimbing atau penguji melalui menu Tugas Saya. Informasi yang ditampilkan meliputi daftar mahasiswa bimbingan, status perkembangan skripsi, daftar ujian yang akan diuji, dan informasi jadwal ujian yang harus dihadiri.'
                ],
                [
                    'title' => '4. PEMBERIAN REVISI',
                    'content' => 'Memberikan catatan perbaikan kepada mahasiswa setelah pelaksanaan ujian melalui menu Revisi. Langkah-langkah: pilih mahasiswa, tuliskan poin-poin revisi, tentukan kategori revisi (minor/major), tentukan batas waktu penyelesaian, kemudian klik Simpan.'
                ],
                [
                    'title' => '5. PENGISIAN NILAI',
                    'content' => 'Memberikan penilaian terhadap hasil ujian mahasiswa melalui menu Nilai. Komponen penilaian meliputi Presentasi Karya Ilmiah (bobot 10%), Penguasaan Materi (bobot 15%), Cara Menjawab Pertanyaan (bobot 10%), dan Daya Analisis Komparatif (bobot 20%). Sistem secara otomatis menghitung nilai akhir.'
                ],
                [
                    'title' => '6. JADWAL MENGAJI',
                    'content' => 'Mengetahui jadwal ujian yang harus dihadiri sebagai dosen penguji melalui menu Jadwal Menguji. Informasi yang ditampilkan meliputi tanggal dan waktu pelaksanaan ujian, nama mahasiswa, ruangan, dan peran sebagai penguji.'
                ],
                [
                    'title' => '7. KUOTA SAYA',
                    'content' => 'Memantau jumlah kuota pembimbing dan penguji yang dimiliki melalui menu Kuota Saya. Informasi yang ditampilkan meliputi jumlah kuota pembimbing yang tersedia dan terpakai, serta jumlah kuota penguji yang tersedia dan terpakai.'
                ],
                [
                    'title' => 'BAB III : PENUTUP',
                    'content' => 'Untuk mengakhiri sesi penggunaan sistem, klik ikon profil atau nama pengguna di pojok kanan atas halaman, kemudian pilih menu Logout atau Keluar. Dosen akan keluar dari sistem dan diarahkan kembali ke halaman login.'
                ]
            ]
        ];

        $pdf = Pdf::loadView('pdf.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('PANDUAN_PENGGUNA_SIP-S_DOSEN.pdf');
    }

    /**
     * Generate PDF for Panitia Verifikasi guide
     */
    public function panitiaVerifikasi()
    {
        $data = [
            'title' => 'PANDUAN PENGGUNAAN SISTEM INFORMASI PENGELOLAAN SKRIPSI (SIP-S)',
            'role' => 'PANITIA VERIFIKASI',
            'sections' => [
                [
                    'title' => 'BAB I : PENDAHULUAN',
                    'content' => 'Panitia Verifikasi bertugas memeriksa kelengkapan berkas pendaftaran ujian mahasiswa. Melalui Sistem Informasi Pengelolaan Skripsi (SIP-S), panitia verifikasi dapat melihat daftar pendaftaran, memeriksa kelengkapan dokumen, memberikan status verifikasi, serta memberikan catatan jika terdapat berkas yang perlu diperbaiki. Panduan ini menjelaskan langkah-langkah penggunaan sistem dari sudut pandang panitia verifikasi.'
                ],
                [
                    'title' => 'BAB II : PROSEDUR PENGGUNAAN SISTEM',
                    'content' => '1. LOGIN : Akses sistem menggunakan akun panitia verifikasi yang telah terdaftar. Buka halaman login SIP-S melalui browser, masukkan alamat email dan kata sandi, kemudian klik tombol Login. Hasil yang diperoleh adalah akses ke halaman Dashboard dan seluruh fitur sesuai hak akses.'
                ],
                [
                    'title' => '2. DASHBOARD',
                    'content' => 'Halaman utama yang menampilkan ringkasan informasi terkait tugas verifikasi, meliputi jumlah pendaftaran yang menunggu verifikasi, jumlah pendaftaran yang telah diverifikasi, jumlah pendaftaran yang ditolak, dan notifikasi pendaftaran baru.'
                ],
                [
                    'title' => '3. MELIHAT DATA PENDAFTARAN',
                    'content' => 'Mengetahui daftar mahasiswa yang telah mengajukan pendaftaran ujian melalui menu Pendaftaran atau Verifikasi. Informasi yang ditampilkan meliputi nama dan NIM mahasiswa, jenis ujian, judul skripsi, status verifikasi, dan tanggal pendaftaran.'
                ],
                [
                    'title' => '4. MEMERIKSA KELENGKAPAN BERKAS',
                    'content' => 'Memastikan seluruh dokumen persyaratan telah diunggah dan lengkap. Langkah-langkah: pilih pendaftaran, klik Detail, periksa setiap dokumen (file draft skripsi, lembar persetujuan bimbingan, scan KRS, scan transkrip nilai), buka dan periksa setiap dokumen untuk memastikan kelengkapan dan keabsahannya.'
                ],
                [
                    'title' => '5. VERIFIKASI BERKAS',
                    'content' => 'Memberikan keputusan terhadap pendaftaran mahasiswa berdasarkan kelengkapan berkas. Pilih status Terima jika seluruh berkas lengkap dan sesuai, atau pilih Tolak jika terdapat berkas yang tidak lengkap atau tidak sesuai. Jika menolak, berikan catatan mengenai berkas yang perlu diperbaiki.'
                ],
                [
                    'title' => '6. PEMBERIAN CATATAN VERIFIKASI',
                    'content' => 'Memberikan informasi tambahan kepada mahasiswa terkait hasil verifikasi. Tuliskan catatan pada kolom yang tersedia saat menolak pendaftaran, jelaskan secara jelas dokumen yang perlu diperbaiki atau dilengkapi, kemudian klik Simpan atau Kirim.'
                ],
                [
                    'title' => 'BAB III : PENUTUP',
                    'content' => 'Untuk mengakhiri sesi penggunaan sistem, klik ikon profil atau nama pengguna di pojok kanan atas halaman, kemudian pilih menu Logout atau Keluar. Panitia verifikasi akan keluar dari sistem dan diarahkan kembali ke halaman login.'
                ]
            ]
        ];

        $pdf = Pdf::loadView('pdf.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('PANDUAN_PENGGUNA_SIP-S_PANITIA_VERIFIKASI.pdf');
    }

    /**
     * Generate PDF for Panitia Penjadwalan guide
     */
    public function panitiaPenjadwalan()
    {
        $data = [
            'title' => 'PANDUAN PENGGUNAAN SISTEM INFORMASI PENGELOLAAN SKRIPSI (SIP-S)',
            'role' => 'PANITIA PENJADWALAN',
            'sections' => [
                [
                    'title' => 'BAB I : PENDAHULUAN',
                    'content' => 'Panitia Penjadwalan bertugas mengatur jadwal pelaksanaan seminar proposal, seminar hasil, dan sidang skripsi. Melalui Sistem Informasi Pengelolaan Skripsi (SIP-S), panitia penjadwalan dapat menjadwalkan ujian, mengatur waktu dan sesi, mengelola ruangan, serta melakukan penjadwalan batch. Panduan ini menjelaskan langkah-langkah penggunaan sistem dari sudut pandang panitia penjadwalan.'
                ],
                [
                    'title' => 'BAB II : PROSEDUR PENGGUNAAN SISTEM',
                    'content' => '1. LOGIN : Akses sistem menggunakan akun panitia penjadwalan yang telah terdaftar. Buka halaman login SIP-S melalui browser, masukkan alamat email dan kata sandi, kemudian klik tombol Login. Hasil yang diperoleh adalah akses ke halaman Dashboard dan seluruh fitur sesuai hak akses.'
                ],
                [
                    'title' => '2. DASHBOARD',
                    'content' => 'Halaman utama yang menampilkan ringkasan informasi terkait tugas penjadwalan, meliputi jumlah ujian yang perlu dijadwalkan, jumlah ujian yang telah dijadwalkan, daftar ruangan yang tersedia, dan bentrok jadwal yang perlu diperhatikan.'
                ],
                [
                    'title' => '3. PENJADWALAN UJIAN',
                    'content' => 'Menentukan jadwal pelaksanaan ujian bagi mahasiswa yang telah memenuhi persyaratan melalui menu Penjadwalan. Langkah-langkah: pilih pendaftaran ujian, tentukan tanggal dan waktu pelaksanaan, tentukan sesi ujian, kemudian klik Simpan atau Jadwalkan.'
                ],
                [
                    'title' => '4. PENGATURAN WAKTU DAN SESI',
                    'content' => 'Mengelola pengaturan waktu dan sesi ujian secara konsisten melalui menu Pengaturan Waktu. Atur durasi setiap sesi ujian, tentukan waktu mulai dan berakhir setiap sesi, kemudian klik Simpan.'
                ],
                [
                    'title' => '5. PENGELOLAAN RUANGAN',
                    'content' => 'Mengelola data ruangan yang dapat digunakan untuk pelaksanaan ujian melalui menu Pengaturan Ruangan. Tambahkan ruangan baru dengan mengisi kode ruangan, nama ruangan, lokasi ruangan, dan kapasitas ruangan, kemudian klik Simpan.'
                ],
                [
                    'title' => '6. BATCH PENJADWALAN',
                    'content' => 'Menjadwalkan beberapa ujian sekaligus dengan tanggal dan waktu yang sama melalui menu Batch Penjadwalan. Pilih beberapa pendaftaran ujian, tentukan tanggal dan waktu pelaksanaan yang sama, kemudian klik Jadwalkan Batch.'
                ],
                [
                    'title' => '7. PEMERIKSAAN BENTROK JADWAL',
                    'content' => 'Memastikan tidak terjadi bentrok jadwal antara dosen, ruangan, atau waktu pelaksanaan. Sistem secara otomatis melakukan pengecekan bentrok jadwal dan memberikan peringatan jika terdapat bentrok.'
                ],
                [
                    'title' => 'BAB III : PENUTUP',
                    'content' => 'Untuk mengakhiri sesi penggunaan sistem, klik ikon profil atau nama pengguna di pojok kanan atas halaman, kemudian pilih menu Logout atau Keluar. Panitia penjadwalan akan keluar dari sistem dan diarahkan kembali ke halaman login.'
                ]
            ]
        ];

        $pdf = Pdf::loadView('pdf.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('PANDUAN_PENGGUNA_SIP-S_PANITIA_PENJADWALAN.pdf');
    }

    /**
     * Generate PDF for Sekretaris Jurusan guide
     */
    public function sekjur()
    {
        $data = [
            'title' => 'PANDUAN PENGGUNAAN SISTEM INFORMASI PENGELOLAAN SKRIPSI (SIP-S)',
            'role' => 'SEKRETARIS JURUSAN',
            'sections' => [
                [
                    'title' => 'BAB I : PENDAHULUAN',
                    'content' => 'Sekretaris Jurusan memiliki peran penting dalam proses pengelolaan skripsi, khususnya dalam penetapan dosen penguji. Melalui Sistem Informasi Pengelolaan Skripsi (SIP-S), sekretaris jurusan dapat melihat data master (view only), menetapkan penguji untuk setiap ujian, dan melihat status verifikasi pendaftaran. Panduan ini menjelaskan langkah-langkah penggunaan sistem dari sudut pandang sekretaris jurusan.'
                ],
                [
                    'title' => 'BAB II : PROSEDUR PENGGUNAAN SISTEM',
                    'content' => '1. LOGIN : Akses sistem menggunakan akun sekretaris jurusan yang telah terdaftar. Buka halaman login SIP-S melalui browser, masukkan alamat email dan kata sandi, kemudian klik tombol Login. Hasil yang diperoleh adalah akses ke halaman Dashboard dan seluruh fitur sesuai hak akses.'
                ],
                [
                    'title' => '2. DASHBOARD',
                    'content' => 'Halaman utama yang menampilkan ringkasan informasi terkait pengelolaan skripsi di jurusan, meliputi jumlah mahasiswa yang terdaftar, jumlah dosen yang terdaftar, jumlah panitia yang terdaftar, dan data pendaftaran seminar proposal, seminar hasil, dan sidang skripsi.'
                ],
                [
                    'title' => '3. MELIHAT DATA MASTER',
                    'content' => 'Melihat data dosen, mahasiswa, dan panitia yang terdaftar dalam sistem melalui menu Data Dosen, Data Mahasiswa, dan Data Panitia. Informasi yang ditampilkan meliputi data dosen (nama, NIP, bidang keahlian, tingkat kepakaran), data mahasiswa (nama, NIM, program studi, status skripsi), dan data panitia (nama, peran).'
                ],
                [
                    'title' => '4. MELIHAT KUOTA DOSEN',
                    'content' => 'Memantau jumlah kuota pembimbing dan penguji yang dimiliki setiap dosen melalui menu Kuota Dosen. Informasi yang ditampilkan meliputi jumlah kuota pembimbing setiap dosen, jumlah kuota pembimbing yang telah terpakai, jumlah kuota penguji setiap dosen, dan jumlah kuota penguji yang telah terpakai.'
                ],
                [
                    'title' => '5. PENETAPAN PENGUJI',
                    'content' => 'Menentukan dosen pembimbing dan dosen penguji untuk setiap ujian mahasiswa melalui menu Penetapan Penguji. Langkah-langkah: pilih pendaftaran ujian, pilih dosen pembimbing 1, pembimbing 2 (jika ada), penguji 1, dan penguji 2, kemudian klik Simpan atau Tetapkan.'
                ],
                [
                    'title' => '6. MELIHAT DATA SEMINAR DAN SIDANG',
                    'content' => 'Memantau pelaksanaan seminar proposal, seminar hasil, dan sidang skripsi melalui menu Seminar Proposal, Seminar Hasil, dan Sidang Skripsi. Informasi yang ditampilkan meliputi nama dan NIM mahasiswa, judul skripsi, status pendaftaran, jadwal ujian, dan dosen penguji.'
                ],
                [
                    'title' => '7. MELIHAT STATUS VERIFIKASI',
                    'content' => 'Mengetahui status verifikasi pendaftaran ujian mahasiswa melalui kolom status verifikasi pada setiap menu data seminar dan sidang. Status yang tersedia: Menunggu Verifikasi, Diverifikasi, Ditolak.'
                ],
                [
                    'title' => 'BAB III : PENUTUP',
                    'content' => 'Untuk mengakhiri sesi penggunaan sistem, klik ikon profil atau nama pengguna di pojok kanan atas halaman, kemudian pilih menu Logout atau Keluar. Sekretaris jurusan akan keluar dari sistem dan diarahkan kembali ke halaman login.'
                ]
            ]
        ];

        $pdf = Pdf::loadView('pdf.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('PANDUAN_PENGGUNA_SIP-S_SEKRETARIS_JURUSAN.pdf');
    }

    /**
     * Generate PDF for Ketua Jurusan guide
     */
    public function kajur()
    {
        $data = [
            'title' => 'PANDUAN PENGGUNAAN SISTEM INFORMASI PENGELOLAAN SKRIPSI (SIP-S)',
            'role' => 'KETUA JURUSAN',
            'sections' => [
                [
                    'title' => 'BAB I : PENDAHULUAN',
                    'content' => 'Ketua Jurusan memiliki peran tertinggi dalam pengelolaan skripsi di tingkat jurusan. Melalui Sistem Informasi Pengelolaan Skripsi (SIP-S), ketua jurusan dapat mengelola data master, melakukan verifikasi akhir, mengelola kuota dosen, dan mengatur pengaturan reminder. Panduan ini menjelaskan langkah-langkah penggunaan sistem dari sudut pandang ketua jurusan.'
                ],
                [
                    'title' => 'BAB II : PROSEDUR PENGGUNAAN SISTEM',
                    'content' => '1. LOGIN : Akses sistem menggunakan akun ketua jurusan yang telah terdaftar. Buka halaman login SIP-S melalui browser, masukkan alamat email dan kata sandi, kemudian klik tombol Login. Hasil yang diperoleh adalah akses ke halaman Dashboard dan seluruh fitur sesuai hak akses.'
                ],
                [
                    'title' => '2. DASHBOARD',
                    'content' => 'Halaman utama yang menampilkan ringkasan informasi pengelolaan skripsi di jurusan, meliputi jumlah mahasiswa yang terdaftar, jumlah dosen yang terdaftar, jumlah panitia yang terdaftar, data pendaftaran seminar proposal, seminar hasil, dan sidang skripsi, serta notifikasi penting terkait pengelolaan skripsi.'
                ],
                [
                    'title' => '3. PENGELOLAAN DATA MASTER',
                    'content' => 'Mengelola data dosen, mahasiswa, panitia, bidang keahlian, dan kepakaran melalui menu Data Dosen, Data Mahasiswa, Data Panitia, Bidang Keahlian, dan Kepakaran. Data master tersimpan dalam sistem dan dapat digunakan sebagai referensi dalam berbagai proses pengelolaan skripsi.'
                ],
                [
                    'title' => '4. PENGELOLAAN KUOTA DOSEN',
                    'content' => 'Mengatur kuota pembimbing dan penguji untuk setiap dosen melalui menu Kuota Dosen. Pilih dosen yang akan diatur kuotanya, masukkan jumlah kuota pembimbing dan kuota penguji, kemudian klik Simpan.'
                ],
                [
                    'title' => '5. VERIFIKASI AKHIR',
                    'content' => 'Melakukan verifikasi akhir pada pendaftaran ujian mahasiswa melalui menu Seminar Proposal, Seminar Hasil, atau Sidang Skripsi. Pilih pendaftaran yang akan diverifikasi, periksa status verifikasi dari panitia, berikan persetujuan akhir dengan mengubah status menjadi Disetujui.'
                ],
                [
                    'title' => '6. PENGATURAN REMINDER',
                    'content' => 'Mengelola pengingat otomatis untuk mahasiswa dan dosen melalui menu Pengaturan Reminder. Atur pengingat untuk jadwal ujian yang akan datang, deadline revisi, dan pendaftaran ujian. Tentukan waktu pengiriman pengingat (H-7, H-3, H-1), kemudian klik Simpan.'
                ],
                [
                    'title' => '7. PEMANTAUAN PELAKSANAAN UJIAN',
                    'content' => 'Memantau pelaksanaan seminar proposal, seminar hasil, dan sidang skripsi melalui menu Seminar Proposal, Seminar Hasil, dan Sidang Skripsi. Informasi yang dapat dipantau meliputi jumlah ujian yang telah dilaksanakan, jumlah ujian yang sedang berlangsung, jumlah ujian yang akan dilaksanakan, dan status kelulusan mahasiswa.'
                ],
                [
                    'title' => 'BAB III : PENUTUP',
                    'content' => 'Untuk mengakhiri sesi penggunaan sistem, klik ikon profil atau nama pengguna di pojok kanan atas halaman, kemudian pilih menu Logout atau Keluar. Ketua jurusan akan keluar dari sistem dan diarahkan kembali ke halaman login.'
                ]
            ]
        ];

        $pdf = Pdf::loadView('pdf.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('PANDUAN_PENGGUNA_SIP-S_KETUA_JURUSAN.pdf');
    }
}
