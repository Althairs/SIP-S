<div>
    @section('title', 'Input Nilai Ujian')
    @section('page-title', 'Input Nilai via Sistem')

    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
    @endif

    <div class="max-w-4xl mx-auto">
        <!-- Info Ujian -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Mahasiswa</p>
                    <p class="font-bold text-lg">{{ $pendaftaran->mahasiswa->name }} ({{ $pendaftaran->mahasiswa->nim }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jenis Ujian</p>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Judul</p>
                    <p class="font-medium">{{ $pendaftaran->judul_penelitian }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Peran Anda</p>
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $peranDosen)) }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipe Input</p>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Input Sistem
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Bobot -->
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6">
            <h3 class="text-sm font-semibold text-green-800 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Ketentuan Penilaian
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-green-700">
                <div>• Presentasi: <strong>10%</strong></div>
                <div>• Penguasaan: <strong>15%</strong></div>
                <div>• Menjawab: <strong>10%</strong></div>
                <div>• Deskripsi: <strong>10%</strong></div>
                <div>• Analisis: <strong>20%</strong></div>
                <div>• Menyimpulkan: <strong>15%</strong></div>
                <div>• Implikasi: <strong>20%</strong></div>
            </div>
        </div>

        <form wire:submit="save">
            <!-- Input Nilai -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Input Nilai (Skala 0-100)</h3>

                <div class="space-y-4">
                    @php
                        $komponen = [
                            'presentasi' => ['label' => 'Presentasi Karya Ilmiah', 'bobot' => '10%', 'icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z'],
                            'penguasaan' => ['label' => 'Penguasaan Materi', 'bobot' => '15%', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            'menjawab' => ['label' => 'Cara Menjawab', 'bobot' => '10%', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                            'deskripsi' => ['label' => 'Daya Deskripsi', 'bobot' => '10%', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                            'analisis' => ['label' => 'Daya Analisis', 'bobot' => '20%', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                            'menyimpulkan' => ['label' => 'Daya Menyimpulkan', 'bobot' => '15%', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'implikasi' => ['label' => 'Daya Implikasi', 'bobot' => '20%', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                        ];
                    @endphp

                    @foreach($komponen as $key => $k)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $k['icon'] }}"></path>
                        </svg>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $k['label'] }}
                                <span class="text-xs text-gray-400">(Bobot {{ $k['bobot'] }})</span>
                            </label>
                            <div class="flex items-center gap-3 mt-1">
                                <input type="range" wire:model.live="{{ $key }}" min="0" max="100" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                                <input type="number" wire:model.live="{{ $key }}" min="0" max="100" class="w-20 px-3 py-1.5 border border-gray-300 rounded-lg text-center text-sm font-bold focus:ring-2 focus:ring-green-500">
                                <span class="text-xs text-gray-400 w-12 text-right">{{ round($$key * \App\Models\Penilaian::BOBOT[$key], 1) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Preview Hasil -->
            @if($showPreview)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Hasil Perhitungan
                </h3>

                <div class="bg-gradient-to-r from-green-50 to-green-50 rounded-2xl p-6">
                    <div class="grid grid-cols-3 gap-6 text-center">
                        <div>
                            <p class="text-sm text-gray-500">Nilai Akhir (NA)</p>
                            <p class="text-4xl font-bold text-green-700">{{ $nilaiAkhir }}</p>
                            <p class="text-xs text-gray-400 mt-1">Skala 0-100</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nilai Huruf</p>
                            <p class="text-4xl font-bold {{ $nilaiHuruf === 'A' ? 'text-green-600' : ($nilaiHuruf === 'B' ? 'text-green-600' : ($nilaiHuruf === 'C' ? 'text-amber-600' : ($nilaiHuruf === 'D' ? 'text-green-600' : 'text-red-600'))) }}">{{ $nilaiHuruf }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Predikat</p>
                            <p class="text-lg font-bold {{ $nilaiHuruf === 'A' ? 'text-green-700' : ($nilaiHuruf === 'B' ? 'text-green-700' : ($nilaiHuruf === 'C' ? 'text-amber-700' : ($nilaiHuruf === 'D' ? 'text-green-700' : 'text-red-700'))) }}">{{ $predikat }}</p>
                        </div>
                    </div>

                    <!-- Detail Perhitungan -->
                    <div class="mt-4 bg-white/50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Detail Perhitungan:</p>
                        <p class="text-xs text-gray-600">
                            NA = ({{ $presentasi }} × 10%) + ({{ $penguasaan }} × 15%) + ({{ $menjawab }} × 10%) + ({{ $deskripsi }} × 10%) + ({{ $analisis }} × 20%) + ({{ $menyimpulkan }} × 15%) + ({{ $implikasi }} × 20%)
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            NA = {{ round($presentasi * 0.1, 1) }} + {{ round($penguasaan * 0.15, 1) }} + {{ round($menjawab * 0.1, 1) }} + {{ round($deskripsi * 0.1, 1) }} + {{ round($analisis * 0.2, 1) }} + {{ round($menyimpulkan * 0.15, 1) }} + {{ round($implikasi * 0.2, 1) }}
                        </p>
                        <p class="text-xs font-bold text-green-700 mt-1">NA = <strong>{{ $nilaiAkhir }}</strong> → {{ $nilaiHuruf }} ({{ $predikat }})</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Catatan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500" placeholder="Catatan tambahan untuk mahasiswa..."></textarea>
            </div>

            <!-- Kaidah Keputusan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Kaidah Keputusan (Referensi)
                </h4>
                <div class="grid grid-cols-5 gap-2 text-center text-xs">
                    <div class="bg-green-50 rounded-lg p-2"><span class="font-bold text-green-700">A</span><br><span class="text-green-600">&gt; 85</span><br><span class="text-green-500">Sangat Baik</span></div>
                    <div class="bg-green-50 rounded-lg p-2"><span class="font-bold text-green-700">B</span><br><span class="text-green-600">70-84</span><br><span class="text-green-500">Baik</span></div>
                    <div class="bg-amber-50 rounded-lg p-2"><span class="font-bold text-amber-700">C</span><br><span class="text-amber-600">55-69</span><br><span class="text-amber-500">Cukup</span></div>
                    <div class="bg-green-50 rounded-lg p-2"><span class="font-bold text-green-700">D</span><br><span class="text-green-600">50-54</span><br><span class="text-green-500">Kurang</span></div>
                    <div class="bg-red-50 rounded-lg p-2"><span class="font-bold text-red-700">E</span><br><span class="text-red-600">0-49</span><br><span class="text-red-500">Gagal</span></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('dosen.nilai.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Kembali</a>
                <div class="flex gap-3">
                    <button type="button" wire:click="hitungNilai" class="px-6 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 font-medium inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Hitung Ulang
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-green-700 text-white rounded-xl hover:bg-green-800 font-medium shadow-sm shadow-green-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $editMode ? 'Perbarui' : 'Simpan' }} Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
