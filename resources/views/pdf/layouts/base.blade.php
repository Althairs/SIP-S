<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan ?? 'Laporan' }}</title>
    @include('pdf.partials.styles')
</head>
<body>
    @include('pdf.partials.kop', ['logoBase64' => $logoBase64])

    <div class="judul-laporan">
        <h3>{{ $judulLaporan }}</h3>
        <p>Tanggal Cetak: {{ $tanggalCetak }}</p>
        @if(!empty($filterLabel))
            <p>Filter: {{ $filterLabel }}</p>
        @endif
    </div>

    @yield('content')
</body>
</html>
