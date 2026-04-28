<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BidangKeahlian;
use App\Models\Jurusan;

class BidangKeahlianSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = Jurusan::active()->get();

        $bidangKeahlianData = [
            'AGT' => [
                ['kode' => 'BDT', 'nama_bidang' => 'Budidaya Tanaman', 'deskripsi' => 'Teknik budidaya tanaman pangan dan hortikultura'],
                ['kode' => 'PTN', 'nama_bidang' => 'Pemuliaan Tanaman', 'deskripsi' => 'Genetika dan pemuliaan tanaman untuk varietas unggul'],
                ['kode' => 'ILT', 'nama_bidang' => 'Ilmu Tanah', 'deskripsi' => 'Pengelolaan kesuburan dan konservasi tanah'],
                ['kode' => 'HPT', 'nama_bidang' => 'Hama & Penyakit Tanaman', 'deskripsi' => 'Pengendalian hama dan penyakit tanaman terpadu'],
                ['kode' => 'AGK', 'nama_bidang' => 'Agroklimatologi', 'deskripsi' => 'Studi iklim dan pengaruhnya terhadap pertanian'],
                ['kode' => 'BKP', 'nama_bidang' => 'Bioteknologi Pertanian', 'deskripsi' => 'Aplikasi bioteknologi dalam bidang pertanian'],
            ],
            'AGB' => [
                ['kode' => 'EKP', 'nama_bidang' => 'Ekonomi Pertanian', 'deskripsi' => 'Analisis ekonomi dalam sistem pertanian'],
                ['kode' => 'MNB', 'nama_bidang' => 'Manajemen Agribisnis', 'deskripsi' => 'Manajemen dan strategi bisnis pertanian'],
                ['kode' => 'PMS', 'nama_bidang' => 'Pemasaran Hasil Pertanian', 'deskripsi' => 'Strategi pemasaran produk pertanian'],
                ['kode' => 'SOS', 'nama_bidang' => 'Sosiologi Pedesaan', 'deskripsi' => 'Kajian sosial masyarakat pedesaan'],
                ['kode' => 'PPM', 'nama_bidang' => 'Pemberdayaan Masyarakat', 'deskripsi' => 'Program pemberdayaan masyarakat petani'],
            ],
            'ILT' => [
                ['kode' => 'KST', 'nama_bidang' => 'Konservasi Tanah', 'deskripsi' => 'Teknik konservasi tanah dan air'],
                ['kode' => 'KST2', 'nama_bidang' => 'Kesuburan Tanah', 'deskripsi' => 'Manajemen kesuburan tanah'],
                ['kode' => 'FST', 'nama_bidang' => 'Fisika Tanah', 'deskripsi' => 'Sifat fisik dan mekanika tanah'],
                ['kode' => 'KMT', 'nama_bidang' => 'Kimia Tanah', 'deskripsi' => 'Analisis kimia dan mineral tanah'],
                ['kode' => 'BLT', 'nama_bidang' => 'Biologi Tanah', 'deskripsi' => 'Mikrobiologi dan fauna tanah'],
            ],
        ];

        foreach ($jurusans as $jurusan) {
            $kode = $jurusan->kode_jurusan;
            if (isset($bidangKeahlianData[$kode])) {
                foreach ($bidangKeahlianData[$kode] as $data) {
                    BidangKeahlian::create([
                        'jurusan_id' => $jurusan->id,
                        'kode' => $data['kode'],
                        'nama_bidang' => $data['nama_bidang'],
                        'deskripsi' => $data['deskripsi'],
                        'is_active' => true,
                    ]);
                }
            }
        }

        echo "Bidang KeahlianSeeder selesai!\n";
        echo "Total bidang keahlian: " . BidangKeahlian::count() . "\n";
    }
}
