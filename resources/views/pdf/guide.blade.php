<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }} - {{ $role }}</title>
    <style>
        /* Reset dan Style Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #1a1a1a;
            margin: 0;
            padding: 20px;
            background: #ffffff;
        }

        /* Kop Surat */
        .letterhead {
            text-align: center;
            padding: 0 0 10px 0;
            border-bottom: 3px double #1a3c6e;
            margin-bottom: 20px;
        }

        .letterhead .institution {
            font-size: 10pt;
            font-weight: bold;
            color: #1a3c6e;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .letterhead .address {
            font-size: 8pt;
            color: #333;
            margin-top: 2px;
        }

        .letterhead .separator {
            border: none;
            border-top: 2px solid #1a3c6e;
            margin: 8px auto;
            width: 100%;
        }

        /* Nomor Surat */
        .document-number {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin: 5px 0 15px 0;
            color: #1a3c6e;
        }

        /* Judul Dokumen */
        .document-title {
            text-align: center;
            margin: 15px 0 5px 0;
        }

        .document-title h1 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1a1a1a;
            margin: 0;
        }

        .document-title .role-subtitle {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a3c6e;
            margin-top: 3px;
        }

        /* Body Konten */
        .content {
            margin-top: 20px;
        }

        .section {
            margin-bottom: 15px;
            text-align: justify;
        }

        .section .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1a3c6e;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .section .section-content {
            margin-left: 15px;
            text-align: justify;
        }

        .section .section-content p {
            margin-bottom: 5px;
        }

        /* Numbered List */
        .numbered-list {
            margin-left: 20px;
            list-style: none;
            counter-reset: item;
        }

        .numbered-list li {
            margin-bottom: 3px;
            text-align: justify;
            position: relative;
            padding-left: 25px;
        }

        .numbered-list li::before {
            counter-increment: item;
            content: counter(item) ".";
            position: absolute;
            left: 0;
            font-weight: bold;
            color: #1a3c6e;
        }

        /* Sub Points */
        .sub-points {
            margin-left: 30px;
            list-style: none;
            counter-reset: subitem;
        }

        .sub-points li {
            margin-bottom: 2px;
            text-align: justify;
            position: relative;
            padding-left: 25px;
        }

        .sub-points li::before {
            counter-increment: subitem;
            content: counter(subitem) ")";
            position: absolute;
            left: 0;
            font-weight: normal;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        .footer .footer-system {
            font-weight: bold;
            color: #1a3c6e;
        }

        .footer .footer-date {
            margin-top: 2px;
        }

        /* Page Break untuk Print */
        .page-break {
            page-break-before: always;
        }

        /* Print Optimization */
        @media print {
            body {
                padding: 30px 40px;
            }

            .section {
                page-break-inside: avoid;
            }

            .letterhead {
                border-bottom-color: #1a3c6e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .document-title .role-subtitle {
                color: #1a3c6e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section .section-title {
                color: #1a3c6e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .numbered-list li::before {
                color: #1a3c6e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .footer .footer-system {
                color: #1a3c6e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }

            .letterhead .institution {
                font-size: 9pt;
            }

            .document-title h1 {
                font-size: 11pt;
            }

            .section .section-content {
                margin-left: 5px;
            }

            .numbered-list {
                margin-left: 10px;
            }

            .numbered-list li {
                padding-left: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="letterhead">
        <div class="institution">
            KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI<br>
            UNIVERSITAS NEGERI GORONTALO<br>
            FAKULTAS PERTANIAN
        </div>
        <div class="address">
            Jalan Prof. Dr. Ing. B.J. Habibie, Tilongkabila, Bone Bolango 96583<br>
            Telepon (0435) 821125 Faximile (0435) 821752<br>
            Laman: faperta.ung.ac.id, Email: faperta@ung.ac.id
        </div>
        <hr class="separator">
    </div>

    <!-- Nomor Dokumen (auto-generated) -->
    <div class="document-number">
        NOMOR : {{ 'SIP-S/' . $role . '/' . date('d.m.Y') . '/' . rand(100, 999) }}
    </div>

    <!-- Judul Dokumen -->
    <div class="document-title">
        <h1>{{ $title }}</h1>
        <div class="role-subtitle">UNTUK {{ $role }}</div>
    </div>

    <!-- Body Content -->
    <div class="content">
        @foreach($sections as $section)
        <div class="section">
            <div class="section-title">{{ $section['title'] }}</div>
            <div class="section-content">
                @php
                    $content = $section['content'];
                    $hasNumberedList = preg_match('/^\d+\./', $content);
                    $hasSubPoints = preg_match('/\d\)/', $content);
                @endphp

                @if($hasNumberedList || $hasSubPoints)
                    @php
                        // Parse numbered list
                        $lines = explode("\n", $content);
                        $items = [];
                        $currentItem = '';
                        $isInList = false;

                        foreach($lines as $line) {
                            $line = trim($line);
                            if (empty($line)) continue;

                            if (preg_match('/^(\d+)\.\s+(.+)/', $line, $matches)) {
                                if (!empty($currentItem)) {
                                    $items[] = $currentItem;
                                }
                                $currentItem = $matches[2];
                                $isInList = true;
                            } else if ($isInList) {
                                $currentItem .= ' ' . $line;
                            } else {
                                // Regular paragraph
                                echo '<p>' . $line . '</p>';
                            }
                        }
                        if (!empty($currentItem)) {
                            $items[] = $currentItem;
                        }
                    @endphp

                    @if(!empty($items))
                        <ul class="numbered-list">
                            @foreach($items as $item)
                                @php
                                    // Check for sub-points
                                    $hasSub = preg_match('/\d\)/', $item);
                                @endphp
                                @if($hasSub)
                                    @php
                                        $parts = preg_split('/(\d\)\s*)/', $item, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                                        $mainText = '';
                                        $subItems = [];

                                        foreach($parts as $part) {
                                            if (preg_match('/^\d\)/', $part)) {
                                                $nextIndex = array_search($part, $parts) + 1;
                                                if (isset($parts[$nextIndex])) {
                                                    $subItems[] = trim($part . $parts[$nextIndex]);
                                                }
                                            } else if (empty($subItems)) {
                                                $mainText .= $part;
                                            }
                                        }
                                    @endphp
                                    <li>
                                        @if(!empty($mainText))
                                            {{ trim($mainText) }}
                                        @endif
                                        @if(!empty($subItems))
                                            <ul class="sub-points">
                                                @foreach($subItems as $sub)
                                                    <li>{{ $sub }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @else
                                    <li>{{ $item }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p>{{ $content }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-system">SIP-S — Sistem Informasi Pengelolaan Skripsi</div>
        <div>Jurusan Agribisnis • Fakultas Pertanian • Universitas Negeri Gorontalo</div>
        <div class="footer-date">Dokumen ini dibuat secara otomatis oleh sistem pada tanggal {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
    </div>
</body>
</html>
