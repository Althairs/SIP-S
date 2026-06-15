<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireframe - Generate Penguji (Sekjur)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: monospace;
            background: #fff;
        }
        .wire-box {
            border: 1px solid #000;
            background: transparent;
            padding: 8px;
        }
        .wire-header {
            border-bottom: 1px solid #000;
            padding: 12px;
            background: #f5f5f5;
        }
        .wire-sidebar {
            border-right: 1px solid #000;
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            height: calc(100% - 60px);
            padding: 16px;
            overflow-y: auto;
        }
        .wire-main {
            margin-left: 260px;
            margin-top: 60px;
            padding: 20px;
        }
        .wire-card {
            border: 1px solid #000;
            padding: 16px;
            margin-bottom: 20px;
        }
        .wire-menu-item {
            padding: 8px;
            border: 1px solid #ccc;
            margin-bottom: 4px;
        }
        .wire-divider {
            border-top: 1px dashed #999;
            margin: 12px 0;
            padding-top: 8px;
            font-size: 11px;
            color: #666;
        }
        .wire-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #000;
            background: #fff;
            font-family: monospace;
        }
        .wire-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #000;
            background: #fff;
            font-family: monospace;
        }
        .wire-table {
            width: 100%;
            border-collapse: collapse;
        }
        .wire-table th, .wire-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .wire-table th {
            background: #f0f0f0;
        }
        .badge-placeholder {
            display: inline-block;
            border: 1px solid #000;
            padding: 2px 6px;
            font-size: 10px;
        }
        .wire-button {
            padding: 8px 16px;
            border: 1px solid #000;
            background: #e0e0e0;
            cursor: pointer;
            font-family: monospace;
        }
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .flex-wrap {
            display: flex;
            flex-wrap: wrap;
        }
        .gap-1 {
            gap: 4px;
        }
        .gap-2 {
            gap: 8px;
        }
        .gap-3 {
            gap: 12px;
        }
        .gap-4 {
            gap: 16px;
        }
        .mt-1 {
            margin-top: 4px;
        }
        .mt-2 {
            margin-top: 8px;
        }
        .mt-3 {
            margin-top: 12px;
        }
        .mt-4 {
            margin-top: 16px;
        }
        .mb-1 {
            margin-bottom: 4px;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
        .mb-3 {
            margin-bottom: 12px;
        }
        .mb-4 {
            margin-bottom: 16px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .text-xs {
            font-size: 11px;
        }
        .text-sm {
            font-size: 12px;
        }
        .text-lg {
            font-size: 16px;
        }
        .text-xl {
            font-size: 20px;
        }
        .font-bold {
            font-weight: bold;
        }
        .font-medium {
            font-weight: 500;
        }
        .w-full {
            width: 100%;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .bg-light {
            background: #f5f5f5;
        }
        .border-bottom {
            border-bottom: 1px solid #000;
        }
        hr {
            border-top: 1px solid #000;
            margin: 8px 0;
        }
        .selected-row {
            background: #e8e8e8;
        }
        .hierarchy-1 { border-left: 3px solid #000; background: #f0f0f0; }
        .hierarchy-2 { border-left: 3px solid #666; background: #f5f5f5; }
        .hierarchy-3 { border-left: 3px solid #999; background: #fafafa; }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="wire-header" style="position: fixed; top: 0; left: 0; right: 0; background: #f0f0f0; z-index: 10;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="wire-box" style="width: 32px; height: 32px;">|||</div>
            <div style="font-weight: bold;">SIP-S | Sekjur</div>
        </div>
        <div>
            <div class="wire-box" style="width: 40px; height: 40px;">[ ]</div>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<div class="wire-sidebar">
    <div>
        <div class="wire-menu-item" style="background: #e0e0e0;">[+] Dashboard</div>

        <div class="wire-divider">DATA MASTER (VIEW)</div>
        <div class="wire-menu-item">[ ] Data Dosen [View]</div>
        <div class="wire-menu-item">[ ] Data Mahasiswa [View]</div>
        <div class="wire-menu-item">[ ] Data Panitia [View]</div>
        <div class="wire-menu-item" style="background: #e0e0e0;">[x] Penguji [Baru]</div>

        <div class="wire-divider">VERIFIKASI (VIEW)</div>
        <div class="wire-menu-item">[ ] Seminar Proposal [View]</div>
        <div class="wire-menu-item">[ ] Seminar Hasil [View]</div>
        <div class="wire-menu-item">[ ] Sidang Skripsi [View]</div>

        <div class="wire-divider">PENGATURAN</div>
        <div class="wire-menu-item">[ ] Profile</div>
        <div class="wire-menu-item">[ ] Settings</div>
        <div class="wire-menu-item">[ ] Sign Out</div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="wire-main">

    <!-- DETAIL PENDAFTARAN -->
    <div class="wire-card">
        <div class="font-bold text-lg mb-4">[DETAIL PENDAFTARAN]</div>
        <div class="grid-2">
            <div>
                <div class="text-xs">[Mahasiswa]</div>
                <div class="font-medium">[Ahmad Wijaya] (20210001)</div>
            </div>
            <div>
                <div class="text-xs">[Jenis Ujian]</div>
                <div class="font-medium">[Sidang Skripsi]</div>
            </div>
            <div>
                <div class="text-xs">[Pembimbing 1]</div>
                <div class="font-medium">[Dr. Ahmad, M.Kom]</div>
            </div>
            <div>
                <div class="text-xs">[Pembimbing 2]</div>
                <div class="font-medium">[Dr. Budi, M.T]</div>
            </div>
            <div style="grid-column: span 2;">
                <div class="text-xs">[Judul]</div>
                <div class="font-medium">[Judul Skripsi]</div>
            </div>
        </div>

        <div class="mt-3">
            <div class="text-xs mb-1">[Bidang Keahlian:]</div>
            <div class="flex-wrap gap-1">
                <span class="badge-placeholder">[Hidroponik]</span>
                <span class="badge-placeholder">[Budidaya Tanaman]</span>
                <span class="badge-placeholder">[Pengolahan Limbah]</span>
            </div>
        </div>
    </div>

    <!-- MODE SELECTION -->
    <div class="flex-wrap gap-3 mb-4">
        <button class="wire-button" style="background: #d0d0d0;">[GENERATE OTOMATIS]</button>
        <button class="wire-button">[PILIH MANUAL]</button>
    </div>

    <!-- PENGUJI 1 & PENGUJI 2 -->
    <div class="grid-2 mb-4">

        <!-- Penguji 1 -->
        <div class="wire-card">
            <div class="flex-between mb-3">
                <span class="badge-placeholder">[Penguji 1 (Utama)]</span>
                <span class="text-xs">[Hierarki Tertinggi]</span>
            </div>

            <div>
                <div class="font-bold text-lg">[Prof. Dr. Siti Nurhaliza, M.Sc]</div>
                <div class="text-sm">[NIP: 197001013]</div>
                <div class="text-sm mt-1">
                    [Kepakaran:
                    <span class="font-bold">[Profesor]</span>
                    <span class="text-xs">(hierarki_level: 1)</span>
                </div>
                <div class="text-xs mt-1">[Deskripsi: Guru Besar]</div>
                <div class="text-sm mt-1">
                    [Kuota Tersisa:
                    <span class="font-bold">[3]</span>
                    ]
                </div>
            </div>

            <!-- Manual mode -->
            <div class="mt-3" style="border-top: 1px dashed #ccc; padding-top: 12px;">
                <div class="text-xs mb-1">[Pilih Dosen Penguji 1]</div>
                <select class="wire-select">
                    <option>[-- Pilih Dosen --]</option>
                    <option>[Prof. Dr. Siti Nurhaliza | Profesor | Sisa: 3]</option>
                    <option>[Dr. Budi Santoso | Kepala Lektor (S3) | Sisa: 0]</option>
                </select>
            </div>
        </div>

        <!-- Penguji 2 -->
        <div class="wire-card">
            <div class="flex-between mb-3">
                <span class="badge-placeholder">[Penguji 2]</span>
                <span class="text-xs">[Kuota Terbanyak / Manual]</span>
            </div>

            <div>
                <div class="font-bold text-lg">[Dr. Budi Santoso, M.T]</div>
                <div class="text-sm">[NIP: 197001012]</div>
                <div class="text-sm mt-1">
                    [Kepakaran:
                    <span class="font-bold">[Kepala Lektor (S3)]</span>
                    <span class="text-xs">(hierarki_level: 2)</span>
                </div>
                <div class="text-xs mt-1">[Deskripsi: Lektor Kepala dengan Doktor]</div>
                <div class="text-sm mt-1">
                    [Kuota Tersisa:
                    <span class="font-bold">[2]</span>
                    ]
                </div>
            </div>

            <!-- Manual mode -->
            <div class="mt-3" style="border-top: 1px dashed #ccc; padding-top: 12px;">
                <div class="text-xs mb-1">[Pilih Dosen Penguji 2]</div>
                <select class="wire-select">
                    <option>[-- Pilih Dosen (Opsional) --]</option>
                    <option>[Dr. Budi Santoso | Kepala Lektor (S3) | Sisa: 2]</option>
                    <option>[Prof. Dr. Siti Nurhaliza | Profesor | Sisa: 3]</option>
                </select>
            </div>
        </div>
    </div>

    <!-- DOSEN TERSEDIA TABLE -->
    <div class="wire-card" style="padding: 0;">
        <div class="border-bottom" style="padding: 12px;">
            <div class="font-bold">[DOSEN TERSEDIA]</div>
            <div class="text-xs">[Diurutkan berdasarkan hierarki_level (1=tertinggi) + kuota tersisa]</div>
        </div>
        <div style="overflow-x: auto;">
            <table class="wire-table" style="width: 100%;">
                <thead>
                    <tr style="background: #f0f0f0;">
                        <th style="border: 1px solid #000; padding: 10px;">[Nama]</th>
                        <th style="border: 1px solid #000; padding: 10px;">[Kepakaran]</th>
                        <th style="border: 1px solid #000; padding: 10px;">[Hierarki]</th>
                        <th style="border: 1px solid #000; padding: 10px;">[Deskripsi]</th>
                        <th style="border: 1px solid #000; padding: 10px;">[Kuota Tersisa]</th>
                        <th style="border: 1px solid #000; padding: 10px;">[Status]</th>
                        <th style="border: 1px solid #000; padding: 10px;">[Skor]</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 - Profesor (hierarki_level: 1) - Selected as Penguji 1 -->
                    <tr class="selected-row">
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Prof. Dr. Siti Nurhaliza, M.Sc]</div>
                            <div class="text-xs">[NIP: 197001013]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Profesor]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[1]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Guru Besar]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[3]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[100]</td>
                    </tr>

                    <!-- Row 2 - Kepala Lektor (S3) (hierarki_level: 2) - Selected as Penguji 2 -->
                    <tr class="selected-row">
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Dr. Budi Santoso, M.T]</div>
                            <div class="text-xs">[NIP: 197001012]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Kepala Lektor (S3)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[2]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor Kepala dengan Doktor]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[2]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[85]</td>
                    </tr>

                    <!-- Row 3 - Kepala Lektor (S2) (hierarki_level: 3) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Dr. Ahmad Wijaya, M.Kom]</div>
                            <div class="text-xs">[NIP: 197001011]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Kepala Lektor (S2)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[3]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor Kepala dengan Magister]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[1]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Overload]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[45]</td>
                    </tr>

                    <!-- Row 4 - Lektor (S3) (hierarki_level: 4) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Dr. Rina Dewi, M.Kom]</div>
                            <div class="text-xs">[NIP: 197001014]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor (S3)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[4]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor dengan Doktor]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[5]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[70]</td>
                    </tr>

                    <!-- Row 5 - Lektor (S2) (hierarki_level: 5) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[M. Fajar, S.T., M.Kom]</div>
                            <div class="text-xs">[NIP: 197001015]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor (S2)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[5]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor dengan Magister]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[4]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[65]</td>
                    </tr>

                    <!-- Row 6 - Ahli Bidang (S3) (hierarki_level: 6) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Dr. Ir. Hendra Gunawan]</div>
                            <div class="text-xs">[NIP: 197001016]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Ahli Bidang (S3)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[6]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Asisten Ahli dengan Doktor]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[6]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[60]</td>
                    </tr>

                    <!-- Row 7 - Ahli Bidang (S2) (hierarki_level: 7) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Lina Marlina, S.Si., M.T]</div>
                            <div class="text-xs">[NIP: 197001017]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Ahli Bidang (S2)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[7]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Asisten Ahli dengan Magister]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[8]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[55]</td>
                    </tr>

                    <!-- Row 8 - Lektor Kepala (S3) (hierarki_level: 2, duplicate from row 2 style) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Dr. Eko Prasetyo]</div>
                            <div class="text-xs">[NIP: 197001018]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor Kepala (S3)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[2]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Lektor Kepala dengan Doktor]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[3]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[80]</td>
                    </tr>

                    <!-- Row 9 - Asisten Ahli (S2) (hierarki_level: 5) -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <div class="font-bold text-sm">[Nina Herlina, S.Kom., M.Kom]</div>
                            <div class="text-xs">[NIP: 197001019]</div>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[Asisten Ahli (S2)]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[5]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[Asisten Ahli dengan Magister]</td>
                        <td style="border: 1px solid #000; padding: 10px;">[7]</td>
                        <td style="border: 1px solid #000; padding: 10px;">
                            <span class="badge-placeholder">[Tersedia]</span>
                        </td>
                        <td style="border: 1px solid #000; padding: 10px;">[50]</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="border-bottom" style="padding: 8px;">
            <div class="text-xs">[Menampilkan 9 dari 25 dosen]</div>
        </div>
    </div>

    <!-- ACTIONS -->
    <div class="flex-between gap-4">
        <button class="wire-button" style="background: #d0d0d0;">[SIMPAN PENGUJI & LANJUTKAN KE PENJADWALAN]</button>
        <button class="wire-button">[BATAL]</button>
    </div>

    <!-- LEGEND HIERARKI -->
    <div class="wire-card" style="background: #fafafa;">
        <div class="font-bold text-sm mb-2">[REFERENSI HIERARKI KEPAKARAN]</div>
        <div class="grid-2 gap-2 text-xs">
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 1]</span> [Profesor - Guru Besar]</div>
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 2]</span> [Kepala Lektor (S3) - Lektor Kepala dengan Doktor]</div>
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 3]</span> [Kepala Lektor (S2) - Lektor Kepala dengan Magister]</div>
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 4]</span> [Lektor (S3) - Lektor dengan Doktor]</div>
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 5]</span> [Lektor (S2) - Lektor dengan Magister]</div>
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 6]</span> [Ahli Bidang (S3) - Asisten Ahli dengan Doktor]</div>
            <div class="flex-between gap-2"><span class="badge-placeholder">[Level 7]</span> [Ahli Bidang (S2) - Asisten Ahli dengan Magister]</div>
        </div>
    </div>

    <!-- Wireframe note -->
    <div style="margin-top: 20px; padding: 12px; border: 1px dashed #999; background: #fafafa; font-size: 11px; text-align: center;">
        [ WIREFRAME - HANYA KERANGKA STRUKTURAL ]
    </div>

</div>

</body>
</html>
