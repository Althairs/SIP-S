<div>
    @section('title', 'Berikan Nilai')
    @section('page-title', 'Berikan Nilai Ujian')

    <!-- Info -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
        <p class="text-sm text-amber-800">Fitur penilaian akan hadir dengan rumus perhitungan:
        <strong>Penguji 1 (40%) + Penguji 2 (30%) + Pembimbing (30%)</strong>. Untuk saat ini, tampilan bersifat sementara.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Mahasiswa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis Ujian</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Peran</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Nilai</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($ujianSaya as $jp)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $jp->pendaftaran->mahasiswa->name }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $jp->pendaftaran->jenis_ujian)) }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ Str::limit($jp->pendaftaran->judul_penelitian, 40) }}</td>
                    <td class="px-6 py-4 text-sm">{{ ucwords(str_replace('_', ' ', $jp->peran)) }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-gray-400">Coming Soon</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-0.5 bg-{{ $jp->pendaftaran->status === 'selesai' ? 'green' : 'blue' }}-100 text-{{ $jp->pendaftaran->status === 'selesai' ? 'green' : 'blue' }}-800 rounded-full text-xs">{{ $jp->pendaftaran->statusLabel }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
