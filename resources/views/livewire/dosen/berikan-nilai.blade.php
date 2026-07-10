<div>
    @section('title', 'Berikan Nilai')
    @section('page-title', 'Berikan Nilai Ujian')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <p class="text-sm text-gray-600">Pilih metode input: <strong class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>Sistem</strong> (input langsung) atau <strong class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>Berkas</strong> (upload untuk panitia)</p>
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
                            <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $nilai->nilai_huruf ?? '-' }} ({{ $nilai->nilai_akhir ?? '-' }})
                            @else
                            <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg> Berkas
                            @endif
                        </span>
                        @else
                        <span class="text-xs text-gray-400">Belum</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('dosen.nilai.input', $p->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 text-xs font-medium flex items-center" title="Input via Sistem">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $sudahNilai && $nilai->tipe_input === 'sistem' ? 'Edit' : 'Input' }}
                            </a>
                            <a href="{{ route('dosen.nilai.upload', $p->id) }}" class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 text-xs font-medium flex items-center" title="Upload Berkas">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                {{ $sudahNilai && $nilai->tipe_input === 'berkas' ? 'Re-upload' : 'Upload' }}
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
