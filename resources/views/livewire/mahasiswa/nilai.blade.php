<div>
    @section('title', 'Nilai Ujian')
    @section('page-title', 'Nilai Ujian Saya')

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Ujian</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalUjian }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
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
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
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
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
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
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Nilai -->
    @if($nilais->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
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
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Nilai Akhir</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Predikat</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($nilais as $index => $nilai)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $nilai->jenis_ujian)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ Str::limit($nilai->judul_penelitian, 35) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold text-gray-900">{{ $nilai->nilai_total }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-{{ $this->getGradeColor($nilai->grade) }}-100 text-{{ $this->getGradeColor($nilai->grade) }}-800 text-xl font-bold">
                                {{ $nilai->grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium">
                            @php
                                $predikat = '';
                                if ($nilai->grade === 'A') $predikat = 'Sangat Baik';
                                elseif ($nilai->grade === 'B') $predikat = 'Baik';
                                elseif ($nilai->grade === 'C') $predikat = 'Cukup';
                                elseif ($nilai->grade === 'D') $predikat = 'Kurang';
                                elseif ($nilai->grade === 'E') $predikat = 'Gagal';
                            @endphp
                            {{ $predikat }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="showDetailNilai({{ $nilai->id }})" class="text-sm text-purple-700 hover:text-purple-800 font-medium">Lihat Detail</button>
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
                <p class="text-xs text-green-600">&gt; 85</p>
                <p class="text-xs text-green-500">Sangat Baik</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-blue-800">B</p>
                <p class="text-xs text-blue-600">70 - 84</p>
                <p class="text-xs text-blue-500">Baik</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-yellow-800">C</p>
                <p class="text-xs text-yellow-600">55 - 69</p>
                <p class="text-xs text-yellow-500">Cukup</p>
            </div>
            <div class="bg-orange-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-orange-800">D</p>
                <p class="text-xs text-orange-600">50 - 54</p>
                <p class="text-xs text-orange-500">Kurang</p>
            </div>
            <div class="bg-red-50 rounded-xl p-3 text-center">
                <p class="text-2xl font-bold text-red-800">E</p>
                <p class="text-xs text-red-600">&lt; 50</p>
                <p class="text-xs text-red-500">Gagal</p>
            </div>
        </div>
    </div>

    {{-- ============= MODAL DETAIL NILAI ============= --}}
    @if($showDetail && $selectedUjian)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDetail"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-t-3xl p-6 text-white sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs">
                                {{ ucwords(str_replace('_', ' ', $selectedUjian->jenis_ujian)) }}
                            </span>
                            <h2 class="text-xl font-bold mt-2">Detail Hasil Ujian</h2>
                        </div>
                        <button wire:click="closeDetail" class="text-white/80 hover:text-white bg-white/10 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Info Ujian -->
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Mahasiswa</p>
                                <p class="font-bold">{{ $selectedUjian->mahasiswa->name }}</p>
                                <p class="text-xs text-gray-400">{{ $selectedUjian->mahasiswa->nim }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Jurusan / Prodi</p>
                                <p class="font-bold">{{ $selectedUjian->jurusan?->nama_jurusan ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500">Judul Penelitian</p>
                                <p class="font-medium">{{ $selectedUjian->judul_penelitian }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Nilai -->
                    @if($selectedUjian->nilai_total)
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-200">
                        <h3 class="text-lg font-bold text-purple-800 mb-4 text-center">Hasil Akhir Ujian</h3>
                        <div class="grid grid-cols-3 gap-6 text-center">
                            <div>
                                <p class="text-sm text-purple-600 mb-1">Nilai Akhir</p>
                                <p class="text-5xl font-bold text-purple-800">{{ $selectedUjian->nilai_total }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-purple-600 mb-1">Grade</p>
                                <p class="text-5xl font-bold text-{{ $this->getGradeColor($selectedUjian->grade) }}-600">{{ $selectedUjian->grade }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-purple-600 mb-1">Status</p>
                                @php
                                    $statusLulus = in_array($selectedUjian->grade, ['A', 'B', 'C']);
                                @endphp
                                <p class="text-2xl font-bold {{ $statusLulus ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $statusLulus ? '✅ LULUS' : '❌ TIDAK LULUS' }}
                                </p>
                                @php
                                    $predikat = '';
                                    if ($selectedUjian->grade === 'A') $predikat = 'Sangat Baik';
                                    elseif ($selectedUjian->grade === 'B') $predikat = 'Baik';
                                    elseif ($selectedUjian->grade === 'C') $predikat = 'Cukup';
                                    elseif ($selectedUjian->grade === 'D') $predikat = 'Kurang';
                                    elseif ($selectedUjian->grade === 'E') $predikat = 'Gagal';
                                @endphp
                                <p class="text-xs text-gray-500 mt-1">{{ $predikat }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ============= PENILAIAN PENGUJI 1 ============= --}}
                    @if(isset($detailPenilaian['penguji_1']))
                    @php $p1 = $detailPenilaian['penguji_1']; @endphp
                    <div class="border border-blue-200 rounded-2xl overflow-hidden">
                        <div class="bg-blue-50 px-6 py-3 border-b border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-blue-800">📝 Penilaian Penguji 1</h3>
                                    <p class="text-sm text-blue-600">{{ $p1['nama'] }} (NIP: {{ $p1['nip'] }})</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">🖥️ Sistem</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 text-xs">
                                        <th class="px-4 py-3 text-left rounded-l-lg">No</th>
                                        <th class="px-4 py-3 text-left">Aspek Penilaian</th>
                                        <th class="px-4 py-3 text-center">Bobot (%)</th>
                                        <th class="px-4 py-3 text-center">Nilai Angka</th>
                                        <th class="px-4 py-3 text-center rounded-r-lg">Nilai Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y text-sm">
                                    @php
                                        $aspekLabels = [
                                            'presentasi' => ['a', 'Presentasi Karya Ilmiah'],
                                            'penguasaan' => ['b', 'Penguasaan Materi'],
                                            'menjawab' => ['c', 'Cara Menjawab'],
                                            'deskripsi' => ['d', 'Daya Deskripsi'],
                                            'analisis' => ['e', 'Daya Analisis'],
                                            'menyimpulkan' => ['f', 'Daya Menyimpulkan'],
                                            'implikasi' => ['g', 'Daya Implikasi'],
                                        ];
                                        $bobotValues = [
                                            'presentasi' => 10,
                                            'penguasaan' => 15,
                                            'menjawab' => 10,
                                            'deskripsi' => 10,
                                            'analisis' => 20,
                                            'menyimpulkan' => 15,
                                            'implikasi' => 20,
                                        ];
                                        $totalNilaiAkhirP1 = 0;
                                    @endphp
                                    @foreach($aspekLabels as $key => $label)
                                    @php
                                        $nilaiAngka = $p1['aspek'][$key] ?? 0;
                                        $nilaiAkhir = $nilaiAngka * ($bobotValues[$key] / 100);
                                        $totalNilaiAkhirP1 += $nilaiAkhir;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-500">{{ $label[0] }}.</td>
                                        <td class="px-4 py-3 font-medium">{{ $label[1] }}</td>
                                        <td class="px-4 py-3 text-center">{{ $bobotValues[$key] }}%</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center w-12 h-8 bg-blue-50 rounded-lg font-bold text-blue-700">{{ $nilaiAngka }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-blue-700">{{ number_format($nilaiAkhir, 1) }}</td>
                                    </tr>
                                    @endforeach
                                    <!-- Total -->
                                    <tr class="bg-blue-50 font-bold">
                                        <td class="px-4 py-3" colspan="2">Total</td>
                                        <td class="px-4 py-3 text-center text-blue-800">100%</td>
                                        <td class="px-4 py-3 text-center text-blue-800">
                                            @php $totalAngkaP1 = array_sum($p1['aspek']); @endphp
                                            {{ $totalAngkaP1 }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-blue-800 text-lg">{{ number_format($totalNilaiAkhirP1, 1) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Rumus -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-xl text-xs text-gray-600">
                                <p class="font-medium mb-1">Rumus NA = Σ(Nilai Angka × Bobot)</p>
                                <p>NA = ({{ $p1['aspek']['presentasi'] }}×10%) + ({{ $p1['aspek']['penguasaan'] }}×15%) + ({{ $p1['aspek']['menjawab'] }}×10%) + ({{ $p1['aspek']['deskripsi'] }}×10%) + ({{ $p1['aspek']['analisis'] }}×20%) + ({{ $p1['aspek']['menyimpulkan'] }}×15%) + ({{ $p1['aspek']['implikasi'] }}×20%)</p>
                                <p class="font-bold text-blue-700 mt-1">NA Penguji 1 = {{ number_format($totalNilaiAkhirP1, 1) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ============= PENILAIAN PENGUJI 2 ============= --}}
                    @if(isset($detailPenilaian['penguji_2']))
                    @php $p2 = $detailPenilaian['penguji_2']; @endphp
                    <div class="border border-green-200 rounded-2xl overflow-hidden">
                        <div class="bg-green-50 px-6 py-3 border-b border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-green-800">📝 Penilaian Penguji 2</h3>
                                    <p class="text-sm text-green-600">{{ $p2['nama'] }} (NIP: {{ $p2['nip'] }})</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">🖥️ Sistem</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 text-xs">
                                        <th class="px-4 py-3 text-left rounded-l-lg">No</th>
                                        <th class="px-4 py-3 text-left">Aspek Penilaian</th>
                                        <th class="px-4 py-3 text-center">Bobot (%)</th>
                                        <th class="px-4 py-3 text-center">Nilai Angka</th>
                                        <th class="px-4 py-3 text-center rounded-r-lg">Nilai Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y text-sm">
                                    @php $totalNilaiAkhirP2 = 0; @endphp
                                    @foreach($aspekLabels as $key => $label)
                                    @php
                                        $nilaiAngka = $p2['aspek'][$key] ?? 0;
                                        $nilaiAkhir = $nilaiAngka * ($bobotValues[$key] / 100);
                                        $totalNilaiAkhirP2 += $nilaiAkhir;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-500">{{ $label[0] }}.</td>
                                        <td class="px-4 py-3 font-medium">{{ $label[1] }}</td>
                                        <td class="px-4 py-3 text-center">{{ $bobotValues[$key] }}%</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center w-12 h-8 bg-green-50 rounded-lg font-bold text-green-700">{{ $nilaiAngka }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-green-700">{{ number_format($nilaiAkhir, 1) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-green-50 font-bold">
                                        <td class="px-4 py-3" colspan="2">Total</td>
                                        <td class="px-4 py-3 text-center text-green-800">100%</td>
                                        <td class="px-4 py-3 text-center text-green-800">
                                            @php $totalAngkaP2 = array_sum($p2['aspek']); @endphp
                                            {{ $totalAngkaP2 }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-green-800 text-lg">{{ number_format($totalNilaiAkhirP2, 1) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-4 p-3 bg-gray-50 rounded-xl text-xs text-gray-600">
                                <p class="font-bold text-green-700 mt-1">NA Penguji 2 = {{ number_format($totalNilaiAkhirP2, 1) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ============= REKAPITULASI NILAI AKHIR ============= --}}
                    @if(isset($detailPenilaian['penguji_1']) && isset($detailPenilaian['penguji_2']))
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-4 text-center">📊 Rekapitulasi Nilai Akhir</h3>
                        <div class="grid grid-cols-3 gap-6 text-center">
                            <div class="bg-white/20 rounded-xl p-4">
                                <p class="text-sm text-indigo-100">Penguji 1</p>
                                <p class="text-3xl font-bold">{{ number_format($totalNilaiAkhirP1 ?? 0, 1) }}</p>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4">
                                <p class="text-sm text-indigo-100">Penguji 2</p>
                                <p class="text-3xl font-bold">{{ number_format($totalNilaiAkhirP2 ?? 0, 1) }}</p>
                            </div>
                            <div class="bg-white/30 rounded-xl p-4">
                                <p class="text-sm text-indigo-100">Nilai Akhir</p>
                                <p class="text-3xl font-bold">{{ $selectedUjian->nilai_total }}</p>
                            </div>
                        </div>
                        <p class="text-center text-sm text-indigo-100 mt-4">
                            NA = (NA Penguji 1 + NA Penguji 2) / 2 = ({{ number_format($totalNilaiAkhirP1 ?? 0, 1) }} + {{ number_format($totalNilaiAkhirP2 ?? 0, 1) }}) / 2 = <strong>{{ $selectedUjian->nilai_total }}</strong>
                        </p>
                    </div>
                    @endif

                    <!-- Catatan -->
                    @if($selectedUjian->catatan_penguji)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-amber-800">📝 Catatan Penguji</p>
                        <p class="text-sm text-amber-700 mt-1">{{ $selectedUjian->catatan_penguji }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
