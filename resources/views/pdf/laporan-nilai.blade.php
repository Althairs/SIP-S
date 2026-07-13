@extends('pdf.layouts.base')

@section('content')
<table class="tabel-data">
    <thead>
        <tr>
            <th style="width: 4%">No</th>
            <th style="width: 10%">NIM</th>
            <th style="width: 16%">Nama Mahasiswa</th>
            <th style="width: 12%">Jenis Ujian</th>
            <th style="width: 8%">Nilai</th>
            <th style="width: 8%">Grade</th>
            <th style="width: 22%">Pembimbing</th>
            <th style="width: 20%">Penguji</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
        @php
            $pembimbing = $item->dosens->map(fn ($d) => $d->dosen?->name)->filter()->implode(', ');
            $penguji = $item->pengujis->map(fn ($p) => $p->dosen?->name)->filter()->implode(', ');
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->mahasiswa?->nim ?? '-' }}</td>
            <td>{{ $item->mahasiswa?->name ?? '-' }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $item->jenis_ujian)) }}</td>
            <td class="text-center">{{ $item->nilai_total ?? '-' }}</td>
            <td class="text-center">{{ $item->grade ?? '-' }}</td>
            <td>{{ $pembimbing ?: '-' }}</td>
            <td>{{ $penguji ?: '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="kosong">Tidak ada data nilai.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
