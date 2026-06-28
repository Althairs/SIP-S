@extends('pdf.layouts.base')

@section('content')
<table class="tabel-data">
    <thead>
        <tr>
            <th style="width: 4%">No</th>
            <th style="width: 10%">NIM</th>
            <th style="width: 16%">Nama Mahasiswa</th>
            <th style="width: 12%">Jenis Ujian</th>
            <th style="width: 12%">Tanggal Ujian</th>
            <th style="width: 8%">Nilai</th>
            <th style="width: 8%">Grade</th>
            <th style="width: 30%">Judul Penelitian</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->mahasiswa?->nim ?? '-' }}</td>
            <td>{{ $item->mahasiswa?->name ?? '-' }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $item->jenis_ujian)) }}</td>
            <td>{{ $item->tanggal_ujian?->translatedFormat('d M Y') ?? ($item->completed_at?->translatedFormat('d M Y') ?? '-') }}</td>
            <td class="text-center">{{ $item->nilai_total ?? '-' }}</td>
            <td class="text-center">{{ $item->grade ?? '-' }}</td>
            <td>{{ $item->judul_penelitian }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="kosong">Tidak ada data ujian selesai.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
