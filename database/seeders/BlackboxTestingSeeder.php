<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDosen;
use App\Models\UjianPenguji;
use App\Models\BidangKeahlian;
use App\Models\Kepakaran;
use App\Models\KuotaDosen;
use App\Models\Revisi;
use App\Models\Penilaian;
use App\Models\Reminder;
use App\Models\PengaturanReminder;
use App\Models\PengaturanJadwal;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class BlackboxTestingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('========================================');
        $this->command->info('  BLACKBOX TESTING SEEDER');
        $this->command->info('========================================');

        // ============================================
        // 1. SETUP JURUSAN & PRODI (gunakan firstOrCreate)
        // ============================================
        $this->command->info('Creating Jurusan & Prodi...');

        $jurusan = Jurusan::firstOrCreate(
            ['kode_jurusan' => ''],
            [
                'nama_jurusan' => 'Agroteknologi',
                'deskripsi' => 'Jurusan Agroteknologi - Testing',
                'is_active' => true,
            ]
        );

        $prodi = Prodi::firstOrCreate(
            ['kode_prodi' => '-S1'],
            [
                'jurusan_id' => $jurusan->id,
                'nama_prodi' => 'S1 Agroteknologi',
                'is_active' => true,
            ]
        );

        // ============================================
        // 2. SETUP KEPAKARAN (firstOrCreate)
        // ============================================
        $this->command->info('Creating Kepakaran...');

        $kepakaranData = [
            ['nama' => 'Profesor', 'level' => 1],
            ['nama' => 'Lektor Kepala (S3)', 'level' => 2],
            ['nama' => 'Lektor (S3)', 'level' => 3],
            ['nama' => 'Lektor (S2)', 'level' => 4],
            ['nama' => 'Asisten Ahli (S2)', 'level' => 5],
        ];

        foreach ($kepakaranData as $k) {
            Kepakaran::firstOrCreate(
                ['nama_kepakaran' => $k['nama']],
                [
                    'hierarki_level' => $k['level'],
                    'is_active' => true,
                ]
            );
        }

        // ============================================
        // 3. SETUP BIDANG KEAHLIAN (firstOrCreate)
        // ============================================
        $this->command->info('Creating Bidang Keahlian...');

        $bidangData = [
            ['kode' => 'BDT', 'nama' => 'Budidaya Tanaman'],
            ['kode' => 'PTN', 'nama' => 'Pemuliaan Tanaman'],
            ['kode' => 'ILT', 'nama' => 'Ilmu Tanah'],
            ['kode' => 'HPT', 'nama' => 'Hama & Penyakit Tanaman'],
            ['kode' => 'AGK', 'nama' => 'Agroklimatologi'],
            ['kode' => 'BKP', 'nama' => 'Bioteknologi Pertanian'],
        ];

        foreach ($bidangData as $b) {
            BidangKeahlian::firstOrCreate(
                ['kode' => $b['kode']],
                [
                    'jurusan_id' => $jurusan->id,
                    'nama_bidang' => $b['nama'],
                    'is_active' => true,
                ]
            );
        }

        $allBidangIds = BidangKeahlian::pluck('id')->toArray();
        $allKepakaranIds = Kepakaran::pluck('id')->toArray();

        // ============================================
        // 4. SETUP USERS (firstOrCreate by email)
        // ============================================
        $this->command->info('Creating Users...');

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin Testing',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (!$superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole('super_admin');
        }

        $kajur = User::firstOrCreate(
            ['email' => 'kajur@test.com'],
            [
                'name' => 'Prof. Dr. Budi Santoso',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'nip' => '198001012010050001',
                'nomor_hp' => '08123456001',
                'kepakaran_id' => $allKepakaranIds[0] ?? null,
                'is_active' => true,
            ]
        );
        if (!$kajur->hasRole('kajur')) {
            $kajur->assignRole('kajur');
        }
        if ($kajur->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $kajur->bidangKeahlians()->sync([$allBidangIds[0], $allBidangIds[1] ?? $allBidangIds[0]]);
        }

        $sekjur = User::firstOrCreate(
            ['email' => 'sekjur@test.com'],
            [
                'name' => 'Dr. Siti Rahayu',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'nip' => '1981020120100151001',
                'nomor_hp' => '08123456002',
                'kepakaran_id' => $allKepakaranIds[1] ?? null,
                'is_active' => true,
            ]
        );
        if (!$sekjur->hasRole('sekjur')) {
            $sekjur->assignRole('sekjur');
        }

        $panVer = User::firstOrCreate(
            ['email' => 'verifikasi@test.com'],
            [
                'name' => 'Rizki Maulana - Verifikasi',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'nip' => '199501012053011001',
                'is_active' => true,
            ]
        );
        if (!$panVer->hasRole('panitia_verifikasi')) {
            $panVer->assignRole('panitia_verifikasi');
        }

        $panJad = User::firstOrCreate(
            ['email' => 'penjadwalan@test.com'],
            [
                'name' => 'Nadia Safitri - Penjadwalan',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'nip' => '199601012054012001',
                'is_active' => true,
            ]
        );
        if (!$panJad->hasRole('panitia_penjadwalan')) {
            $panJad->assignRole('panitia_penjadwalan');
        }

        // Dosen
        $dosen1 = User::firstOrCreate(
            ['email' => 'dosen1@test.com'],
            [
                'name' => 'Prof. Dr. Ahmad Fauzi',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'nip' => '197501055005011001',
                'nomor_hp' => '08123457001',
                'kepakaran_id' => $allKepakaranIds[0] ?? null,
                'is_active' => true,
            ]
        );
        if (!$dosen1->hasRole('dosen')) $dosen1->assignRole('dosen');
        if ($dosen1->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $dosen1->bidangKeahlians()->sync([$allBidangIds[0], $allBidangIds[2] ?? $allBidangIds[0]]);
        }
        KuotaDosen::firstOrCreate(
            ['dosen_id' => $dosen1->id],
            ['jurusan_id' => $jurusan->id, 'kuota_pembimbing' => 5, 'kuota_penguji' => 10]
        );

        $dosen2 = User::firstOrCreate(
            ['email' => 'dosen2@test.com'],
            [
                'name' => 'Dr. Maya Indriani',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'nip' => '198502056015012001',
                'nomor_hp' => '08123457002',
                'kepakaran_id' => $allKepakaranIds[1] ?? null,
                'is_active' => true,
            ]
        );
        if (!$dosen2->hasRole('dosen')) $dosen2->assignRole('dosen');
        if ($dosen2->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $dosen2->bidangKeahlians()->sync([$allBidangIds[1] ?? $allBidangIds[0], $allBidangIds[3] ?? $allBidangIds[0]]);
        }
        KuotaDosen::firstOrCreate(
            ['dosen_id' => $dosen2->id],
            ['jurusan_id' => $jurusan->id, 'kuota_pembimbing' => 5, 'kuota_penguji' => 10]
        );

        $dosenP1 = User::firstOrCreate(
            ['email' => 'dosenp1@test.com'],
            [
                'name' => 'Dr. Rudi Hartono',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'nip' => '198602057016011001',
                'kepakaran_id' => $allKepakaranIds[2] ?? null,
                'is_active' => true,
            ]
        );
        if (!$dosenP1->hasRole('dosen')) $dosenP1->assignRole('dosen');
        if ($dosenP1->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $dosenP1->bidangKeahlians()->sync([$allBidangIds[0]]);
        }
        KuotaDosen::firstOrCreate(
            ['dosen_id' => $dosenP1->id],
            ['jurusan_id' => $jurusan->id, 'kuota_pembimbing' => 5, 'kuota_penguji' => 5]
        );

        $dosenP2 = User::firstOrCreate(
            ['email' => 'dosenp2@test.com'],
            [
                'name' => 'Ir. Bambang Sutrisno',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'nip' => '197501058005011002',
                'kepakaran_id' => $allKepakaranIds[3] ?? null,
                'is_active' => true,
            ]
        );
        if (!$dosenP2->hasRole('dosen')) $dosenP2->assignRole('dosen');
        if ($dosenP2->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $dosenP2->bidangKeahlians()->sync([$allBidangIds[2] ?? $allBidangIds[0]]);
        }
        KuotaDosen::firstOrCreate(
            ['dosen_id' => $dosenP2->id],
            ['jurusan_id' => $jurusan->id, 'kuota_pembimbing' => 5, 'kuota_penguji' => 5]
        );

        // Mahasiswa
        $mhs1 = User::firstOrCreate(
            ['email' => 'mhs1@test.com'],
            [
                'name' => 'Dewi Kusuma',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'nim' => '531622023',
                'nomor_hp' => '08123458001',
                'is_active' => true,
            ]
        );
        if (!$mhs1->hasRole('mahasiswa')) $mhs1->assignRole('mahasiswa');

        $mhs2 = User::firstOrCreate(
            ['email' => 'mhs2@test.com'],
            [
                'name' => 'Bambang Prasetyo',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'nim' => '2021002',
                'nomor_hp' => '08123458002',
                'is_active' => true,
            ]
        );
        if (!$mhs2->hasRole('mahasiswa')) $mhs2->assignRole('mahasiswa');

        $mhs3 = User::firstOrCreate(
            ['email' => 'mhs3@test.com'],
            [
                'name' => 'Citra Anggraini',
                'password' => Hash::make('password'),
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'nim' => '2021003',
                'nomor_hp' => '08123458003',
                'is_active' => true,
            ]
        );
        if (!$mhs3->hasRole('mahasiswa')) $mhs3->assignRole('mahasiswa');

        $this->command->info('Users ready!');

        // ============================================
        // 5. SETUP PENDAFTARAN (firstOrCreate)
        // ============================================
        $this->command->info('Creating Pendaftaran...');

        // Pendaftaran 1: SELESAI (Seminar Proposal MHS1)
        $daftar1 = Pendaftaran::firstOrCreate(
            [
                'mahasiswa_id' => $mhs1->id,
                'jenis_ujian' => 'seminar_proposal',
            ],
            [
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'judul_penelitian' => 'Pengaruh Pupuk Organik terhadap Pertumbuhan Tanaman Padi Varietas Unggul',
                'abstrak' => 'Penelitian ini bertujuan untuk menganalisis pengaruh berbagai jenis pupuk organik terhadap pertumbuhan dan hasil panen padi.',
                'status' => 'selesai',
                'tanggal_ujian' => Carbon::now()->subDays(10),
                'ruangan' => 'Ruang Seminar 1',
                'sesi' => 1,
                'nilai_total' => 82.5,
                'grade' => 'B',
                'first_registered_at' => Carbon::now()->subDays(30),
                'scheduled_at' => Carbon::now()->subDays(17),
                'completed_at' => Carbon::now()->subDays(10),
                'approved_at' => Carbon::now()->subDays(20),
            ]
        );
        if ($daftar1->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $daftar1->bidangKeahlians()->sync([$allBidangIds[0], $allBidangIds[2] ?? $allBidangIds[0]]);
        }

        // Pembimbing daftar1
        PendaftaranDosen::firstOrCreate(
            ['pendaftaran_id' => $daftar1->id, 'peran' => 'pembimbing_1'],
            ['dosen_id' => $dosenP1->id]
        );
        PendaftaranDosen::firstOrCreate(
            ['pendaftaran_id' => $daftar1->id, 'peran' => 'pembimbing_2'],
            ['dosen_id' => $dosenP2->id]
        );

        // Penguji daftar1
        $ujianP1_1 = UjianPenguji::firstOrCreate(
            ['pendaftaran_id' => $daftar1->id, 'peran' => 'penguji_1'],
            [
                'dosen_id' => $dosen1->id,
                'kepakaran_id' => $allKepakaranIds[0] ?? null,
                'kuota_tersisa' => 9,
                'is_overload' => false,
            ]
        );
        $ujianP1_2 = UjianPenguji::firstOrCreate(
            ['pendaftaran_id' => $daftar1->id, 'peran' => 'penguji_2'],
            [
                'dosen_id' => $dosen2->id,
                'kepakaran_id' => $allKepakaranIds[1] ?? null,
                'kuota_tersisa' => 8,
                'is_overload' => false,
            ]
        );

        // Pendaftaran 2: DIJADWALKAN (Seminar Hasil MHS1)
        $daftar2 = Pendaftaran::firstOrCreate(
            [
                'mahasiswa_id' => $mhs1->id,
                'jenis_ujian' => 'seminar_hasil',
            ],
            [
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'judul_penelitian' => 'Hasil Penelitian: Pengaruh Pupuk Organik terhadap Pertumbuhan Tanaman Padi',
                'status' => 'dijadwalkan',
                'tanggal_ujian' => Carbon::now()->addDays(14),
                'ruangan' => 'Ruang Seminar 2',
                'sesi' => 2,
                'first_registered_at' => Carbon::now()->subDays(7),
                'scheduled_at' => Carbon::now()->subDays(1),
            ]
        );
        if ($daftar2->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $daftar2->bidangKeahlians()->sync([$allBidangIds[0]]);
        }
        PendaftaranDosen::firstOrCreate(
            ['pendaftaran_id' => $daftar2->id, 'peran' => 'pembimbing_1'],
            ['dosen_id' => $dosenP1->id]
        );
        UjianPenguji::firstOrCreate(
            ['pendaftaran_id' => $daftar2->id, 'peran' => 'penguji_1'],
            ['dosen_id' => $dosen1->id, 'kepakaran_id' => $allKepakaranIds[0] ?? null, 'kuota_tersisa' => 8]
        );
        UjianPenguji::firstOrCreate(
            ['pendaftaran_id' => $daftar2->id, 'peran' => 'penguji_2'],
            ['dosen_id' => $dosen2->id, 'kepakaran_id' => $allKepakaranIds[1] ?? null, 'kuota_tersisa' => 7]
        );

        // Pendaftaran 3: DISETUJUI KAJUR (Sidang Skripsi MHS1)
        $daftar3 = Pendaftaran::firstOrCreate(
            [
                'mahasiswa_id' => $mhs1->id,
                'jenis_ujian' => 'sidang_skripsi',
            ],
            [
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'judul_penelitian' => 'Skripsi: Optimalisasi Pupuk Organik untuk Budidaya Padi Berkelanjutan',
                'status' => 'disetujui_kajur',
                'first_registered_at' => Carbon::now()->subDays(3),
            ]
        );
        if ($daftar3->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $daftar3->bidangKeahlians()->sync([$allBidangIds[0], $allBidangIds[1] ?? $allBidangIds[0]]);
        }
        PendaftaranDosen::firstOrCreate(
            ['pendaftaran_id' => $daftar3->id, 'peran' => 'pembimbing_1'],
            ['dosen_id' => $dosenP1->id]
        );
        UjianPenguji::firstOrCreate(
            ['pendaftaran_id' => $daftar3->id, 'peran' => 'penguji_1'],
            ['dosen_id' => $dosen1->id, 'kepakaran_id' => $allKepakaranIds[0] ?? null, 'kuota_tersisa' => 7]
        );
        UjianPenguji::firstOrCreate(
            ['pendaftaran_id' => $daftar3->id, 'peran' => 'penguji_2'],
            ['dosen_id' => $dosen2->id, 'kepakaran_id' => $allKepakaranIds[1] ?? null, 'kuota_tersisa' => 6]
        );

        // Pendaftaran 4: DISETUJUI PANITIA (MHS2)
        $daftar4 = Pendaftaran::firstOrCreate(
            [
                'mahasiswa_id' => $mhs2->id,
                'jenis_ujian' => 'seminar_proposal',
            ],
            [
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'judul_penelitian' => 'Analisis Kelayakan Usaha Tani Padi Organik di Kabupaten Bogor',
                'status' => 'disetujui_panitia',
                'first_registered_at' => Carbon::now()->subDays(5),
            ]
        );
        if ($daftar4->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $daftar4->bidangKeahlians()->sync([$allBidangIds[3] ?? $allBidangIds[0]]);
        }
        PendaftaranDosen::firstOrCreate(
            ['pendaftaran_id' => $daftar4->id, 'peran' => 'pembimbing_1'],
            ['dosen_id' => $dosenP2->id]
        );

        // Pendaftaran 5: PENDING (MHS3)
        $daftar5 = Pendaftaran::firstOrCreate(
            [
                'mahasiswa_id' => $mhs3->id,
                'jenis_ujian' => 'seminar_proposal',
            ],
            [
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'judul_penelitian' => 'Strategi Pemasaran Hasil Pertanian di Era Digital',
                'status' => 'pending',
                'first_registered_at' => Carbon::now()->subDays(1),
            ]
        );
        if ($daftar5->bidangKeahlians()->count() == 0 && !empty($allBidangIds)) {
            $daftar5->bidangKeahlians()->sync([$allBidangIds[4] ?? $allBidangIds[0], $allBidangIds[5] ?? $allBidangIds[0]]);
        }
        PendaftaranDosen::firstOrCreate(
            ['pendaftaran_id' => $daftar5->id, 'peran' => 'pembimbing_1'],
            ['dosen_id' => $dosen1->id]
        );

        $this->command->info('Pendaftaran ready!');

        // ============================================
        // 6. SETUP REVISI (firstOrCreate)
        // ============================================
        $this->command->info('Creating Revisi...');

        Revisi::firstOrCreate(
            [
                'pendaftaran_id' => $daftar1->id,
                'dosen_id' => $dosen1->id,
                'peran_pemberi' => 'penguji_1',
            ],
            [
                'ujian_penguji_id' => $ujianP1_1->id,
                'isi_revisi' => 'Perbaiki format penulisan daftar pustaka sesuai APA Style. Tambahkan minimal 5 referensi jurnal internasional.',
                'kategori' => 'minor',
                'status' => 'selesai',
                'is_approved' => true,
                'approved_at' => Carbon::now()->subDays(5),
                'deadline' => Carbon::now()->subDays(3),
            ]
        );

        Revisi::firstOrCreate(
            [
                'pendaftaran_id' => $daftar1->id,
                'dosen_id' => $dosen2->id,
                'peran_pemberi' => 'penguji_2',
            ],
            [
                'ujian_penguji_id' => $ujianP1_2->id,
                'isi_revisi' => 'Tambahkan analisis statistik yang lebih mendalam pada bab hasil. Gunakan uji ANOVA.',
                'kategori' => 'major',
                'status' => 'selesai',
                'is_approved' => true,
                'approved_at' => Carbon::now()->subDays(5),
                'deadline' => Carbon::now()->subDays(3),
            ]
        );

        $this->command->info('Revisi ready!');

        // ============================================
        // 7. SETUP PENILAIAN (firstOrCreate)
        // ============================================
        $this->command->info('Creating Penilaian...');

        // ============================================
        // 7. SETUP PENILAIAN (firstOrCreate)
        // ============================================
        $this->command->info('Creating Penilaian...');

        // Penilaian dari Penguji 1 via Sistem (Dosen1 - Prof. Ahmad Fauzi)
        Penilaian::firstOrCreate(
            [
                'pendaftaran_id' => $daftar1->id,
                'dosen_id' => $dosen1->id,
                'peran_pemberi' => 'penguji_1',
            ],
            [
                'ujian_penguji_id' => $ujianP1_1->id, // Diubah dari $ujianPenguji1_1 ke $ujianP1_1 sesuai variabel di atas
                'tipe_input' => 'sistem',
                'presentasi' => 85,
                'penguasaan' => 88,
                'menjawab' => 82,
                'deskripsi' => 80,
                'analisis' => 85,
                'menyimpulkan' => 83,
                'implikasi' => 86,
                'nilai_akhir' => 84.35,  // Perhitungan bobot tepat sesuai lembar penilaian
                'nilai_huruf' => 'B',
                'predikat' => 'Baik',
                'status' => 'selesai',
                'submitted_at' => Carbon::now()->subDays(9),
            ]
        );

        // Penilaian dari Penguji 2 via Sistem (Dosen2 - Dr. Maya Indriani)
        Penilaian::firstOrCreate(
            [
                'pendaftaran_id' => $daftar1->id,
                'dosen_id' => $dosen2->id,
                'peran_pemberi' => 'penguji_2',
            ],
            [
                'ujian_penguji_id' => $ujianP1_2->id, // Diubah dari $ujianPenguji1_2 ke $ujianP1_2 sesuai variabel di atas
                'tipe_input' => 'sistem',
                'presentasi' => 80,
                'penguasaan' => 82,
                'menjawab' => 78,
                'deskripsi' => 80,
                'analisis' => 80,
                'menyimpulkan' => 81,
                'implikasi' => 83,
                'nilai_akhir' => 80.75,  // Perhitungan bobot tepat sesuai lembar penilaian
                'nilai_huruf' => 'B',
                'predikat' => 'Baik',
                'status' => 'selesai',
                'submitted_at' => Carbon::now()->subDays(9),
            ]
        );

        // Update nilai_total di pendaftaran = rata-rata penguji 1 & 2
        $daftar1->update([
            'nilai_total' => round((84.35 + 80.75) / 2, 2),  // Menghasilkan 82.55
            'grade' => 'B',
        ]);

        $this->command->info('Penilaian ready!');

        // ============================================
        // 8. SETUP REMINDER (firstOrCreate)
        // ============================================
        $this->command->info('Creating Reminders...');

        PengaturanReminder::firstOrCreate(
            ['jurusan_id' => $jurusan->id, 'jenis_ujian' => 'revisi'],
            [
                'is_active' => true,
                'deadline_days' => 14,
                'reminder_settings' => json_encode([
                    ['type' => 'daily', 'interval' => 3, 'label' => 'Setiap 3 Hari'],
                    ['type' => 'before_deadline', 'days' => 3, 'label' => 'H-3 Deadline'],
                    ['type' => 'before_deadline', 'days' => 1, 'label' => 'H-1 Deadline'],
                ]),
                'pesan_template' => 'Mohon segera menyelesaikan revisi {jenis_ujian} untuk judul "{judul}". Deadline: {deadline}.',
            ]
        );

        PengaturanReminder::firstOrCreate(
            ['jurusan_id' => $jurusan->id, 'jenis_ujian' => 'seminar_proposal'],
            [
                'is_active' => true,
                'deadline_days' => 30,
                'reminder_settings' => json_encode([
                    ['type' => 'before_deadline', 'days' => 7, 'label' => 'H-7 Ujian'],
                    ['type' => 'before_deadline', 'days' => 1, 'label' => 'H-1 Ujian'],
                ]),
                'pesan_template' => 'Pengingat ujian {jenis_ujian} untuk judul "{judul}" pada {deadline}.',
            ]
        );

        Reminder::firstOrCreate(
            [
                'user_id' => $mhs1->id,
                'pendaftaran_id' => $daftar2->id,
                'judul' => 'Persiapan Ujian - H-14',
            ],
            [
                'pesan' => 'Ujian Seminar Hasil Anda akan dilaksanakan 14 hari lagi.',
                'tipe' => 'deadline',
                'prioritas' => 'sedang',
                'tanggal_tampil' => Carbon::now(),
                'tanggal_kadaluarsa' => Carbon::now()->addDays(14),
            ]
        );

        $this->command->info('Reminders ready!');

        // ============================================
        // 9. SETUP PENGATURAN JADWAL & RUANGAN (firstOrCreate)
        // ============================================
        $this->command->info('Creating Pengaturan Jadwal & Ruangan...');

        PengaturanJadwal::firstOrCreate(
            ['jurusan_id' => $jurusan->id],
            [
                'jam_mulai' => json_encode(['08:00', '10:00', '13:00', '15:00']),
                'jam_selesai' => json_encode(['10:00', '12:00', '15:00', '17:00']),
                'label_sesi' => json_encode(['Sesi 1', 'Sesi 2', 'Sesi 3', 'Sesi 4']),
                'is_active' => true,
            ]
        );

        $ruanganData = [
            ['kode' => '-R01', 'nama' => 'Ruang Seminar 1', 'lokasi' => 'Lt. 2', 'kapasitas' => 30],
            ['kode' => '-R02', 'nama' => 'Ruang Seminar 2', 'lokasi' => 'Lt. 2', 'kapasitas' => 25],
            ['kode' => '-R03', 'nama' => 'Ruang Sidang', 'lokasi' => 'Lt. 3', 'kapasitas' => 20],
            ['kode' => '-A01', 'nama' => 'Aula Agroteknologi', 'lokasi' => 'Lt. 1', 'kapasitas' => 50],
        ];

        foreach ($ruanganData as $r) {
            Ruangan::firstOrCreate(
                ['kode_ruangan' => $r['kode']],
                [
                    'jurusan_id' => $jurusan->id,
                    'nama_ruangan' => $r['nama'],
                    'lokasi' => $r['lokasi'],
                    'kapasitas' => $r['kapasitas'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Pengaturan ready!');

        // ============================================
        // 10. OUTPUT
        // ============================================
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  DATA READY! (Password: password)');
        $this->command->info('========================================');
        $this->command->info('Super Admin      : superadmin@test.com');
        $this->command->info('Kajur            : kajur@test.com');
        $this->command->info('Sekjur           : sekjur@test.com');
        $this->command->info('Verifikasi       : verifikasi@test.com');
        $this->command->info('Penjadwalan      : penjadwalan@test.com');
        $this->command->info('Dosen1           : dosen1@test.com');
        $this->command->info('Dosen2           : dosen2@test.com');
        $this->command->info('DosenP1          : dosenp1@test.com');
        $this->command->info('MHS1             : mhs1@test.com');
        $this->command->info('MHS2             : mhs2@test.com');
        $this->command->info('MHS3             : mhs3@test.com');
        $this->command->info('========================================');
    }
}
