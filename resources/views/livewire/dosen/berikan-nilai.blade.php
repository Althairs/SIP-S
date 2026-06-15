<div>
    @section('title', 'Berikan Nilai')
    @section('page-title', 'Berikan Nilai Ujian')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <p class="text-sm text-gray-600">Pilih metode input: <strong>🖥️ Sistem</strong> (input langsung) atau <strong>📎 Berkas</strong> (upload untuk panitia)</p>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Mahasiswa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Peran</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($ujianSaya as $jp)
                @php
                    $p = $jp->pendaftaran;
                    $sudahNilai = isset($penilaianSaya[$p->id]);
                    $nilai = $penilaianSaya[$p->id] ?? null;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium">{{ $p->mahasiswa->name }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ Str::limit($p->judul_penelitian, 40) }}</td>
                    <td class="px-6 py-4 text-sm">{{ ucwords(str_replace('_', ' ', $jp->peran)) }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($sudahNilai)
                        <span class="px-2 py-0.5 bg-{{ $nilai->statusColor }}-100 text-{{ $nilai->statusColor }}-800 rounded-full text-xs">
                            @if($nilai->tipe_input === 'sistem')
                            🖥️ {{ $nilai->nilai_huruf ?? '-' }} ({{ $nilai->nilai_akhir ?? '-' }})
                            @else
                            📎 Berkas
                            @endif
                        </span>
                        @else
                        <span class="text-xs text-gray-400">Belum</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('dosen.nilai.input', $p->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 text-xs font-medium" title="Input via Sistem">
                                🖥️ {{ $sudahNilai && $nilai->tipe_input === 'sistem' ? 'Edit' : 'Input' }}
                            </a>
                            <a href="{{ route('dosen.nilai.upload', $p->id) }}" class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 text-xs font-medium" title="Upload Berkas">
                                📎 {{ $sudahNilai && $nilai->tipe_input === 'berkas' ? 'Re-upload' : 'Upload' }}
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data ujian</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
