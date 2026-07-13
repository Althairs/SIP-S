<?php
// 1. Load Autoloader Composer
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// =========================================================================
// BAGIAN CONTROLLER (DATA)
// =========================================================================
$nomorSurat      = '1135/UN47.B6/PT.01.04/2026';
$tanggalSurat    = '26 Mei 2026';
$judulPenelitian = 'Sistem Informasi Pengelolaan Skripsi di Jurusan Agribisnis Universitas Negeri Gorontalo';

$dekan = [
    'nama'    => 'Dr. Ir. Muhammad Mukhtar, S.Pt, M. Agr, Sc',
    'nip'     => '197108262005011001',
    'pangkat' => 'Pembina Utama Muda / IV c',
    'jabatan' => 'Dekan Fakultas Pertanian Universitas Negeri Gorontalo',
];

$mahasiswa = [
    'nama'     => 'Satria Indra Dewangga',
    'nim'      => '531422023',
    'jurusan'  => 'S1- Sistem Informatika',
    'fakultas' => 'Teknik Universitas Negeri Gorontalo',
    'angkatan' => '2022',
];

// Trik aman DomPDF PHP Native: Ubah gambar logo ke format Base64
$pathLogo   = __DIR__ . '/images/logo-ung.png'; // Sesuaikan folder Anda
$logoBase64 = '';
if (file_exists($pathLogo)) {
    $fileData   = file_get_contents($pathLogo);
    $logoBase64 = 'data:image/png;base64,' . base64_encode($fileData);
}


// =========================================================================
// BAGIAN VIEW (Mulai rekam HTML ke dalam Buffer)
// =========================================================================
ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Rekomendasi - <?= $mahasiswa['nama'] ?></title>
    <style>
        @page {
            margin: 2.5cm 2cm 2cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        table.tabel-kop { width: 100%; border-collapse: collapse; }
        table.tabel-kop td { vertical-align: middle; }
        .kolom-logo { width: 15%; text-align: center; }
        .kolom-logo img { width: 2.3cm; height: auto; }
        .kolom-teks { width: 70%; text-align: center; }
        .kolom-spacer { width: 15%; }
        .kolom-teks h1 { font-size: 13pt; font-weight: normal; margin: 0; }
        .kolom-teks h2 { font-size: 14pt; font-weight: bold; margin: 0; }
        .kolom-teks p { font-size: 9pt; margin: 1px 0; }

        hr.garis-kop {
            border: none;
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            height: 2px;
            margin: 12px 0 20px 0;
        }

        .judul-surat { text-align: center; margin-bottom: 20px; }
        .judul-surat h3 { font-size: 14pt; text-decoration: underline; margin: 0; }
        .judul-surat p { font-size: 12pt; margin: 2px 0; }

        table.tabel-data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.tabel-data td { vertical-align: top; padding: 2px 0; }
        table.tabel-data td.label { width: 26%; }
        table.tabel-data td.titik-dua { width: 3%; text-align: center; }
        table.tabel-data td.value { width: 71%; }

        .paragraf { text-align: justify; text-indent: 1.2cm; margin-bottom: 10px; }

        .blok-ttd { width: 100%; margin-top: 30px; }
        .kotak-ttd { float: right; width: 55%; }

        .footer-ite {
            margin-top: 50px;
            font-size: 8pt;
            border-top: 1px solid #666;
            padding-top: 6px;
            line-height: 1.2;
            text-align: justify;
        }
    </style>
</head>
<body>

    <table class="tabel-kop">
        <tr>
            <td class="kolom-logo">
                <img src="<?= $logoBase64 ?>" alt="Logo UNG">
            </td>
            <td class="kolom-teks">
                <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h1>
                <h2>UNIVERSITAS NEGERI GORONTALO</h2>
                <h2>FAKULTAS PERTANIAN</h2>
                <p>Jalan Prof. Dr. Ing. B.J. Habibie, Tilongkabila, Bone Bolango 96583,</p>
                <p>Telepon (0435) 821125 Faximile (0435) 821752.</p>
                <p>Laman: faperta.ung.ac.id, Email: faperta@ung.ac.id</p>
            </td>
            <td class="kolom-spacer"></td>
        </tr>
    </table>
    <hr class="garis-kop">

    <div class="judul-surat">
        <h3>SURAT REKOMENDASI</h3>
        <p>NOMOR: <?= $nomorSurat ?></p>
    </div>

    <p style="margin-bottom: 5px;">Yang bertanda tangan dibawah ini:</p>

    <table class="tabel-data">
        <tr>
            <td class="label">Nama</td>
            <td class="titik-dua">:</td>
            <td class="value"><strong><?= $dekan['nama'] ?></strong></td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $dekan['nip'] ?></td>
        </tr>
        <tr>
            <td class="label">Pangkat/Golongan</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $dekan['pangkat'] ?></td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $dekan['jabatan'] ?></td>
        </tr>
    </table>

    <p style="margin-bottom: 5px;">Dengan ini Menerangkan bahwa:</p>

    <table class="tabel-data">
        <tr>
            <td class="label">Nama</td>
            <td class="titik-dua">:</td>
            <td class="value"><strong><?= $mahasiswa['nama'] ?></strong></td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $mahasiswa['nim'] ?></td>
        </tr>
        <tr>
            <td class="label">Jurusan</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $mahasiswa['jurusan'] ?></td>
        </tr>
        <tr>
            <td class="label">Fakultas</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $mahasiswa['fakultas'] ?></td>
        </tr>
        <tr>
            <td class="label">Angkatan</td>
            <td class="titik-dua">:</td>
            <td class="value"><?= $mahasiswa['angkatan'] ?></td>
        </tr>
    </table>

    <p class="paragraf">
        Bahwa mahasiswa yang bersangkutan diberikan rekomendasi untuk melakukan penelitian di Jurusan Agribisnis Fakultas Pertanian Universitas Negeri Gorontalo, dengan judul penelitian <strong>"<?= $judulPenelitian ?>"</strong>.
    </p>

    <p class="paragraf">
        Demikian surat rekomendasi ini diberikan kepada yang bersangkutan, untuk digunakan seperlunya.
    </p>

    <div class="blok-ttd">
        <div class="kotak-ttd">
            <p style="margin: 0;"><?= $tanggalSurat ?></p>
            <p style="margin: 0;">Dekan,</p>
            <br><br><br>
            <p style="margin: 0;"><strong><?= $dekan['nama'] ?></strong></p>
            <p style="margin: 0;">NIP. <?= $dekan['nip'] ?></p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer-ite">
        <i>Dokumen ini telah ditanda tangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan BSrE. UU ITE No. 11 Tahun 2008 Pasal 5 ayat 1: "Informasi Elektronik dan/atau Dokumen Elektronik dan/atau hasil cetaknya merupakan alat bukti hukum yang sah". Cetakan ini merupakan salinan dan dapat dibuktikan keasliannya melalui scan QRCode yang terdapat pada dokumen ini.</i>
    </div>

</body>
</html>
<?php
// Masukkan seluruh rekaman HTML di atas ke variabel $html
$html = ob_get_clean();


// =========================================================================
// PROSES GENERATE PDF
// =========================================================================
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Times New Roman');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$namaFile = 'Surat_Rekomendasi_' . $mahasiswa['nim'] . '.pdf';

// Stream PDF ke browser
$dompdf->stream($namaFile, [
    "Attachment" => true // Ubah ke 'false' jika ingin PDF-nya terbuka di tab browser (Preview), bukan otomatis terunduh
]);

exit;
