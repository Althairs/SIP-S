<div>
    @section('title', 'Nilai Ujian')
    @section('page-title', 'Nilai Ujian')

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Ujian</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalUjian }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $rataRata ? number_format($rataRata, 2) : '-' }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Nilai Tertinggi</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $nilais->max('nilai_total') ?? '-' }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Grade Terbaik</p>
                    <p class="text-3xl font-bold text-gray-900">
                        @php
                            $grades = $nilais->pluck('grade')->filter()->toArray();
                            $bestGrade = !empty($grades) ? (in_array('A', $grades) ? 'A' : (in_array('B', $grades) ? 'B' : max($grades))) : '-';
                        @endphp
                        {{ $bestGrade }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Nilai -->
    @if($nilais->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Nilai</h3>
        <p class="text-gray-500">Nilai akan muncul setelah Anda menyelesaikan ujian.</p>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis Ujian</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nilai</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($nilais as $index => $nilai)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                {{ ucwords(str_replace('_', ' ', $nilai->jenis_ujian)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                            {{ Str::limit($nilai->judul_penelitian, 35) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-gray-900">{{ $nilai->nilai_total }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-{{ $this->getGradeColor($nilai->grade) }}-100 text-{{ $this->getGradeColor($nilai->grade) }}-800 text-xl font-bold">
                                {{ $nilai->grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $nilai->tanggal_ujian ? \Carbon\Carbon::parse($nilai->tanggal_ujian)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="showDetailNilai({{ $nilai->id }})"
                                    class="text-sm text-purple-700 hover:text-purple-800 font-medium">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Grade Scale Reference -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Skala Penilaian</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="bg-green-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-green-800">A</p>
                <p class="text-xs text-green-600">≥ 80</p>
                <p class="text-xs text-green-500">Sangat Memuaskan</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-blue-800">B</p>
                <p class="text-xs text-blue-600">70 - 79</p>
                <p class="text-xs text-blue-500">Memuaskan</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-yellow-800">C</p>
                <p class="text-xs text-yellow-600">60 - 69</p>
                <p class="text-xs text-yellow-500">Cukup</p>
            </div>
            <div class="bg-orange-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-orange-800">D</p>
                <p class="text-xs text-orange-600">50 - 59</p>
                <p class="text-xs text-orange-500">Kurang</p>
            </div>
            <div class="bg-red-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-red-800">E</p>
                <p class="text-xs text-red-600">&lt; 50</p>
                <p class="text-xs text-red-500">Tidak Lulus</p>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetail && $selectedUjian)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDetail"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Detail Nilai</h3>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Jenis Ujian</p>
                        <p class="font-medium">{{ ucwords(str_replace('_', ' ', $selectedUjian->jenis_ujian)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Judul Penelitian</p>
                        <p class="font-medium">{{ $selectedUjian->judul_penelitian }}</p>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-purple-600 mb-1">Nilai Akhir</p>
                        <p class="text-4xl font-bold text-purple-800">{{ $selectedUjian->nilai_total }}</p>
                        <p class="text-lg font-bold text-purple-700 mt-1">Grade: {{ $selectedUjian->grade }}</p>
                    </div>

                    @if($selectedUjian->catatan_penguji)
                    <div>
                        <p class="text-sm text-gray-500">Catatan Penguji</p>
                        <p class="text-sm bg-gray-50 p-3 rounded-lg">{{ $selectedUjian->catatan_penguji }}</p>
                    </div>
                    @endif

                    @if($selectedUjian->catatan_revisi)
                    <div>
                        <p class="text-sm text-gray-500">Catatan Revisi</p>
                        <p class="text-sm bg-amber-50 p-3 rounded-lg text-amber-800">{{ $selectedUjian->catatan_revisi }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
