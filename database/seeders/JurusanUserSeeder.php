<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JurusanUserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // Super Admin (Tidak terikat jurusan)
        // ============================================
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@sips.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super_admin');

        // ============================================
        // JURUSAN 1: Agroteknologi
        // ============================================
        $agroteknologi = Jurusan::create([
            'kode_jurusan' => 'AGT',
            'nama_jurusan' => 'Agroteknologi',
            'deskripsi' => 'Jurusan Agroteknologi mempelajari teknologi budidaya tanaman',
            'is_active' => true,
        ]);

        // Prodi Agroteknologi
        $prodiAgt1 = Prodi::create([
            'jurusan_id' => $agroteknologi->id,
            'kode_prodi' => 'AGT-S1',
            'nama_prodi' => 'S1 Agroteknologi',
            'deskripsi' => 'Program Sarjana Agroteknologi',
            'is_active' => true,
        ]);

        $prodiAgt2 = Prodi::create([
            'jurusan_id' => $agroteknologi->id,
            'kode_prodi' => 'AGT-S2',
            'nama_prodi' => 'S2 Agroteknologi',
            'deskripsi' => 'Program Magister Agroteknologi',
            'is_active' => true,
        ]);

        // Kajur Agroteknologi
        $kajurAgt = User::create([
            'name' => 'Dr. Budi Santoso, SP., MP.',
            'email' => 'kajur.agroteknologi@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'nip' => '198001012010011001',
            'nomor_hp' => '081234561001',
            'alamat' => 'Jl. Pertanian No. 1, Kota',
            'is_active' => true,
        ]);
        $kajurAgt->assignRole('kajur');

        // Sekjur Agroteknologi
        $sekjurAgt = User::create([
            'name' => 'Dr. Siti Rahayu, SP., M.Si.',
            'email' => 'sekjur.agroteknologi@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'nip' => '198102012010012001',
            'nomor_hp' => '081234561002',
            'alamat' => 'Jl. Pertanian No. 2, Kota',
            'is_active' => true,
        ]);
        $sekjurAgt->assignRole('sekjur');

        // Dosen Agroteknologi
        $dosenAgt1 = User::create([
            'name' => 'Prof. Dr. Ahmad Fauzi, M.Sc.',
            'email' => 'ahmad.fauzi@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'prodi_id' => $prodiAgt1->id,
            'nip' => '197501012005011001',
            'nomor_hp' => '081234562001',
            'alamat' => 'Jl. Dosen No. 1, Kota',
            'is_active' => true,
        ]);
        $dosenAgt1->assignRole('dosen');

        $dosenAgt2 = User::create([
            'name' => 'Dr. Ir. Maya Indriani, MP.',
            'email' => 'maya.indriani@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'prodi_id' => $prodiAgt1->id,
            'nip' => '198502012015012001',
            'nomor_hp' => '081234562002',
            'alamat' => 'Jl. Dosen No. 2, Kota',
            'is_active' => true,
        ]);
        $dosenAgt2->assignRole('dosen');

        $dosenAgt3 = User::create([
            'name' => 'Dr. Rudi Hartono, SP., M.Agr.',
            'email' => 'rudi.hartono@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'prodi_id' => $prodiAgt2->id,
            'nip' => '198602012016011001',
            'nomor_hp' => '081234562003',
            'alamat' => 'Jl. Dosen No. 3, Kota',
            'is_active' => true,
        ]);
        $dosenAgt3->assignRole('dosen');

        // Panitia Agroteknologi
        // $panitiaAgt1 = User::create([
        //     'name' => 'Andi Pratama, S.Kom.',
        //     'email' => 'andi.pratama.agt@sips.com',
        //     'password' => Hash::make('password123'),
        //     'jurusan_id' => $agroteknologi->id,
        //     'nip' => '199001012020011001',
        //     'nomor_hp' => '081234563001',
        //     'alamat' => 'Jl. Staff No. 1, Kota',
        //     'is_active' => true,
        // ]);
        // $panitiaAgt1->assignRole('panitia');

        // $panitiaAgt2 = User::create([
        //     'name' => 'Rina Wulandari, A.Md.',
        //     'email' => 'rina.wulandari.agt@sips.com',
        //     'password' => Hash::make('password123'),
        //     'jurusan_id' => $agroteknologi->id,
        //     'nip' => '199102012020012001',
        //     'nomor_hp' => '081234563002',
        //     'alamat' => 'Jl. Staff No. 2, Kota',
        //     'is_active' => true,
        // ]);
        // $panitiaAgt2->assignRole('panitia');

        // Mahasiswa Agroteknologi
        $mhsAgt1 = User::create([
            'name' => 'Dewi Kusuma',
            'email' => 'dewi.kusuma@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'prodi_id' => $prodiAgt1->id,
            'nim' => 'AGT2021001',
            'nomor_hp' => '081234564001',
            'alamat' => 'Jl. Mahasiswa No. 1, Kota',
            'is_active' => true,
        ]);
        $mhsAgt1->assignRole('mahasiswa');

        $mhsAgt2 = User::create([
            'name' => 'Bambang Prasetyo',
            'email' => 'bambang.prasetyo@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'prodi_id' => $prodiAgt1->id,
            'nim' => 'AGT2021002',
            'nomor_hp' => '081234564002',
            'alamat' => 'Jl. Mahasiswa No. 2, Kota',
            'is_active' => true,
        ]);
        $mhsAgt2->assignRole('mahasiswa');

        $mhsAgt3 = User::create([
            'name' => 'Citra Anggraini',
            'email' => 'citra.anggraini@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'prodi_id' => $prodiAgt2->id,
            'nim' => 'AGT2021003',
            'nomor_hp' => '081234564003',
            'alamat' => 'Jl. Mahasiswa No. 3, Kota',
            'is_active' => true,
        ]);
        $mhsAgt3->assignRole('mahasiswa');

        // ============================================
        // JURUSAN 2: Agribisnis
        // ============================================
        $agribisnis = Jurusan::create([
            'kode_jurusan' => 'AGB',
            'nama_jurusan' => 'Agribisnis',
            'deskripsi' => 'Jurusan Agribisnis mempelajari ekonomi dan bisnis pertanian',
            'is_active' => true,
        ]);

        // Prodi Agribisnis
        $prodiAgb1 = Prodi::create([
            'jurusan_id' => $agribisnis->id,
            'kode_prodi' => 'AGB-S1',
            'nama_prodi' => 'S1 Agribisnis',
            'deskripsi' => 'Program Sarjana Agribisnis',
            'is_active' => true,
        ]);

        $prodiAgb2 = Prodi::create([
            'jurusan_id' => $agribisnis->id,
            'kode_prodi' => 'AGB-D3',
            'nama_prodi' => 'D3 Agribisnis',
            'deskripsi' => 'Program Diploma Agribisnis',
            'is_active' => true,
        ]);

        // Kajur Agribisnis
        $kajurAgb = User::create([
            'name' => 'Prof. Dr. Ir. Rina Marlina, M.Si.',
            'email' => 'kajur.agribisnis@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'nip' => '197801012008012001',
            'nomor_hp' => '081235561001',
            'alamat' => 'Jl. Agribisnis No. 1, Kota',
            'is_active' => true,
        ]);
        $kajurAgb->assignRole('kajur');

        // Sekjur Agribisnis
        $sekjurAgb = User::create([
            'name' => 'Dr. Hendra Gunawan, SP., MM.',
            'email' => 'sekjur.agribisnis@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'nip' => '197901012008011001',
            'nomor_hp' => '081235561002',
            'alamat' => 'Jl. Agribisnis No. 2, Kota',
            'is_active' => true,
        ]);
        $sekjurAgb->assignRole('sekjur');

        // Dosen Agribisnis
        $dosenAgb1 = User::create([
            'name' => 'Dr. Fitriani Dewi, SP., M.Si.',
            'email' => 'fitriani.dewi@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'prodi_id' => $prodiAgb1->id,
            'nip' => '198301012013012001',
            'nomor_hp' => '081235562001',
            'alamat' => 'Jl. Dosen AGB No. 1, Kota',
            'is_active' => true,
        ]);
        $dosenAgb1->assignRole('dosen');

        $dosenAgb2 = User::create([
            'name' => 'Ir. Bambang Sutrisno, MM.',
            'email' => 'bambang.sutrisno@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'prodi_id' => $prodiAgb2->id,
            'nip' => '197501012005011002',
            'nomor_hp' => '081235562002',
            'alamat' => 'Jl. Dosen AGB No. 2, Kota',
            'is_active' => true,
        ]);
        $dosenAgb2->assignRole('dosen');

        // Panitia Agribisnis
        // $panitiaAgb1 = User::create([
        //     'name' => 'Dian Permata, S.E.',
        //     'email' => 'dian.permata.agb@sips.com',
        //     'password' => Hash::make('password123'),
        //     'jurusan_id' => $agribisnis->id,
        //     'nip' => '199201012021012001',
        //     'nomor_hp' => '081235563001',
        //     'alamat' => 'Jl. Staff AGB No. 1, Kota',
        //     'is_active' => true,
        // ]);
        // $panitiaAgb1->assignRole('panitia');

        // Mahasiswa Agribisnis
        $mhsAgb1 = User::create([
            'name' => 'Eko Prasetyo',
            'email' => 'eko.prasetyo@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'prodi_id' => $prodiAgb1->id,
            'nim' => 'AGB2021001',
            'nomor_hp' => '081235564001',
            'alamat' => 'Jl. Mahasiswa AGB No. 1, Kota',
            'is_active' => true,
        ]);
        $mhsAgb1->assignRole('mahasiswa');

        $mhsAgb2 = User::create([
            'name' => 'Fitri Handayani',
            'email' => 'fitri.handayani@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'prodi_id' => $prodiAgb1->id,
            'nim' => 'AGB2021002',
            'nomor_hp' => '081235564002',
            'alamat' => 'Jl. Mahasiswa AGB No. 2, Kota',
            'is_active' => true,
        ]);
        $mhsAgb2->assignRole('mahasiswa');

        // ============================================
        // JURUSAN 3: Ilmu Tanah
        // ============================================
        $ilmuTanah = Jurusan::create([
            'kode_jurusan' => 'ILT',
            'nama_jurusan' => 'Ilmu Tanah',
            'deskripsi' => 'Jurusan Ilmu Tanah mempelajari karakteristik dan pengelolaan tanah',
            'is_active' => true,
        ]);

        // Prodi Ilmu Tanah
        $prodiIlt1 = Prodi::create([
            'jurusan_id' => $ilmuTanah->id,
            'kode_prodi' => 'ILT-S1',
            'nama_prodi' => 'S1 Ilmu Tanah',
            'deskripsi' => 'Program Sarjana Ilmu Tanah',
            'is_active' => true,
        ]);

        // Kajur Ilmu Tanah
        $kajurIlt = User::create([
            'name' => 'Dr. Ir. Supriyanto, M.Sc.',
            'email' => 'kajur.ilmutanah@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'nip' => '198001012009011001',
            'nomor_hp' => '081236661001',
            'alamat' => 'Jl. Tanah No. 1, Kota',
            'is_active' => true,
        ]);
        $kajurIlt->assignRole('kajur');

        // Sekjur Ilmu Tanah
        $sekjurIlt = User::create([
            'name' => 'Dr. Ir. Sri Wahyuni, M.Si.',
            'email' => 'sekjur.ilmutanah@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'nip' => '198002012009012001',
            'nomor_hp' => '081236661002',
            'alamat' => 'Jl. Tanah No. 2, Kota',
            'is_active' => true,
        ]);
        $sekjurIlt->assignRole('sekjur');

        // Dosen Ilmu Tanah
        $dosenIlt1 = User::create([
            'name' => 'Prof. Dr. Ir. Rahmat Hidayat, M.Agr.',
            'email' => 'rahmat.hidayat@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'prodi_id' => $prodiIlt1->id,
            'nip' => '197001012000011001',
            'nomor_hp' => '081236662001',
            'alamat' => 'Jl. Dosen ILT No. 1, Kota',
            'is_active' => true,
        ]);
        $dosenIlt1->assignRole('dosen');

        $dosenIlt2 = User::create([
            'name' => 'Dr. Ir. Nani Haryani, MP.',
            'email' => 'nani.haryani@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'prodi_id' => $prodiIlt1->id,
            'nip' => '198301012013012002',
            'nomor_hp' => '081236662002',
            'alamat' => 'Jl. Dosen ILT No. 2, Kota',
            'is_active' => true,
        ]);
        $dosenIlt2->assignRole('dosen');

        // Panitia Ilmu Tanah
        // $panitiaIlt1 = User::create([
        //     'name' => 'Agus Setiawan, S.Kom.',
        //     'email' => 'agus.setiawan.ilt@sips.com',
        //     'password' => Hash::make('password123'),
        //     'jurusan_id' => $ilmuTanah->id,
        //     'nip' => '199301012022011001',
        //     'nomor_hp' => '081236663001',
        //     'alamat' => 'Jl. Staff ILT No. 1, Kota',
        //     'is_active' => true,
        // ]);
        // $panitiaIlt1->assignRole('panitia');

        // Mahasiswa Ilmu Tanah
        $mhsIlt1 = User::create([
            'name' => 'Gilang Ramadhan',
            'email' => 'gilang.ramadhan@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'prodi_id' => $prodiIlt1->id,
            'nim' => 'ILT2021001',
            'nomor_hp' => '081236664001',
            'alamat' => 'Jl. Mahasiswa ILT No. 1, Kota',
            'is_active' => true,
        ]);
        $mhsIlt1->assignRole('mahasiswa');

        $mhsIlt2 = User::create([
            'name' => 'Hani Pratiwi',
            'email' => 'hani.pratiwi@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'prodi_id' => $prodiIlt1->id,
            'nim' => 'ILT2021002',
            'nomor_hp' => '081236664002',
            'alamat' => 'Jl. Mahasiswa ILT No. 2, Kota',
            'is_active' => true,
        ]);
        $mhsIlt2->assignRole('mahasiswa');

        // ============================================
        // JURUSAN 4: Proteksi Tanaman (NONAKTIF)
        // ============================================
        $proteksiTanaman = Jurusan::create([
            'kode_jurusan' => 'PTN',
            'nama_jurusan' => 'Proteksi Tanaman',
            'deskripsi' => 'Jurusan Proteksi Tanaman (saat ini nonaktif)',
            'is_active' => false,  // Nonaktif
        ]);

        // Panitia Verifikasi Agroteknologi
        $panitiaVerifikasiAgt = User::create([
            'name' => 'Rizki Maulana, S.T.',
            'email' => 'verifikasi.agt@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'nip' => '199501012023011001',
            'nomor_hp' => '081234565001',
            'alamat' => 'Jl. Panitia No. 1, Kota',
            'is_active' => true,
        ]);
        $panitiaVerifikasiAgt->assignRole('panitia_verifikasi');

        // Panitia Penjadwalan Agroteknologi
        $panitiaPenjadwalanAgt = User::create([
            'name' => 'Nadia Safitri, S.Kom.',
            'email' => 'penjadwalan.agt@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agroteknologi->id,
            'nip' => '199601012023012001',
            'nomor_hp' => '081234565002',
            'alamat' => 'Jl. Panitia No. 2, Kota',
            'is_active' => true,
        ]);
        $panitiaPenjadwalanAgt->assignRole('panitia_penjadwalan');

        // HAPUS panitia lama (andi.pratama.agt dan rina.wulandari.agt) atau ubah rolenya

        // Panitia Verifikasi Agribisnis
        User::create([
            'name' => 'Teguh Prasetyo, A.Md.',
            'email' => 'verifikasi.agb@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'nip' => '199701012023011001',
            'nomor_hp' => '081235565001',
            'is_active' => true,
        ])->assignRole('panitia_verifikasi');

        // Panitia Penjadwalan Agribisnis
        User::create([
            'name' => 'Sari Dewi, S.E.',
            'email' => 'penjadwalan.agb@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $agribisnis->id,
            'nip' => '199801012023012001',
            'nomor_hp' => '081235565002',
            'is_active' => true,
        ])->assignRole('panitia_penjadwalan');

        // Panitia Verifikasi Ilmu Tanah
        User::create([
            'name' => 'Dimas Ardiansyah, S.Kom.',
            'email' => 'verifikasi.ilt@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'nip' => '199901012023011001',
            'nomor_hp' => '081236665001',
            'is_active' => true,
        ])->assignRole('panitia_verifikasi');

        // Panitia Penjadwalan Ilmu Tanah
        User::create([
            'name' => 'Putri Amelia, S.T.',
            'email' => 'penjadwalan.ilt@sips.com',
            'password' => Hash::make('password123'),
            'jurusan_id' => $ilmuTanah->id,
            'nip' => '200001012023012001',
            'nomor_hp' => '081236665002',
            'is_active' => true,
        ])->assignRole('panitia_penjadwalan');


        echo "Seeder berhasil dijalankan!\n";
        echo "========================================\n";
        echo "Data Testing Login:\n";
        echo "----------------------------------------\n";
        echo "Super Admin: superadmin@sips.com / password123\n";
        echo "----------------------------------------\n";
        echo "Jurusan Agroteknologi:\n";
        echo "  Kajur:   kajur.agroteknologi@sips.com / password123\n";
        echo "  Sekjur:  sekjur.agroteknologi@sips.com / password123\n";
        echo "  Dosen:   ahmad.fauzi@sips.com / password123\n";
        echo "  Panitia: andi.pratama.agt@sips.com / password123\n";
        echo "  Mhs:     dewi.kusuma@sips.com / password123\n";
        echo "----------------------------------------\n";
        echo "Jurusan Agribisnis:\n";
        echo "  Kajur:   kajur.agribisnis@sips.com / password123\n";
        echo "  Sekjur:  sekjur.agribisnis@sips.com / password123\n";
        echo "  Dosen:   fitriani.dewi@sips.com / password123\n";
        echo "  Panitia: dian.permata.agb@sips.com / password123\n";
        echo "  Mhs:     eko.prasetyo@sips.com / password123\n";
        echo "----------------------------------------\n";
        echo "Jurusan Ilmu Tanah:\n";
        echo "  Kajur:   kajur.ilmutanah@sips.com / password123\n";
        echo "  Sekjur:  sekjur.ilmutanah@sips.com / password123\n";
        echo "  Dosen:   rahmat.hidayat@sips.com / password123\n";
        echo "  Panitia: agus.setiawan.ilt@sips.com / password123\n";
        echo "  Mhs:     gilang.ramadhan@sips.com / password123\n";
        echo "========================================\n";

        echo "Panitia:\n";
        echo "  Verifikasi AGT: verifikasi.agt@sips.com / password123\n";
        echo "  Penjadwalan AGT: penjadwalan.agt@sips.com / password123\n";
        echo "  Verifikasi AGB: verifikasi.agb@sips.com / password123\n";
        echo "  Penjadwalan AGB: penjadwalan.agb@sips.com / password123\n";
        echo "  Verifikasi ILT: verifikasi.ilt@sips.com / password123\n";
        echo "  Penjadwalan ILT: penjadwalan.ilt@sips.com / password123\n";
    }
}
