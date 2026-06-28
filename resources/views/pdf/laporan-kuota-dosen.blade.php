@extends('pdf.layouts.base')

@section('content')
<table class="tabel-data">
    <thead>
        <tr>
            <th style="width: 4%">No</th>
            <th style="width: 22%">Nama Dosen</th>
            <th style="width: 12%">NIP</th>
            <th style="width: 14%">Prodi</th>
            <th style="width: 8%">Kuota Bim.</th>
            <th style="width: 8%">Terpakai Bim.</th>
            <th style="width: 8%">Sisa Bim.</th>
            <th style="width: 8%">Kuota Uji</th>
            <th style="width: 8%">Terpakai Uji</th>
            <th style="width: 8%">Sisa Uji</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $item->dosen?->name ?? '-' }}</td>
            <td>{{ $item->dosen?->nip ?? '-' }}</td>
            <td>{{ $item->dosen?->prodi?->nama_prodi ?? '-' }}</td>
            <td class="text-center">{{ $item->kuota_pembimbing }}</td>
            <td class="text-center">{{ $item->terpakai_pembimbing }}</td>
            <td class="text-center">{{ $item->sisa_pembimbing }}</td>
            <td class="text-center">{{ $item->kuota_penguji }}</td>
            <td class="text-center">{{ $item->terpakai_penguji }}</td>
            <td class="text-center">{{ $item->sisa_penguji }}</td>
        </tr>
        @empty
        <tr><td colspan="10" class="kosong">Tidak ada data kuota dosen.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
