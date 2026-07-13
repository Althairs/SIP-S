@extends('pdf.layouts.base')

@section('content')
<table class="tabel-data">
    <thead>
        <tr>
            <th style="width: 4%">No</th>
            <th style="width: 12%">NIM</th>
            <th style="width: 18%">Nama Mahasiswa</th>
            <th style="width: 14%">Prodi</th>
            <th style="width: 14%">Jenis Ujian</th>
            <th style="width: 14%">Status</th>
            <th style="width: 24%">Judul Penelitian</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->mahasiswa?->nim ?? '-' }}</td>
            <td>{{ $item->mahasiswa?->name ?? '-' }}</td>
            <td>{{ $item->prodi?->nama_prodi ?? '-' }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $item->jenis_ujian)) }}</td>
            <td>{{ $item->status_label }}</td>
            <td>{{ $item->judul_penelitian }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="kosong">Tidak ada data pendaftaran.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
