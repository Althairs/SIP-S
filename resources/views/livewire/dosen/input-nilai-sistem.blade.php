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
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
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
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">🖥️ Input Sistem</span>
                </div>
            </div>
        </div>

        <!-- Info Bobot -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">📊 Ketentuan Penilaian</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs text-blue-700">
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
                            'presentasi' => ['label' => 'Presentasi Karya Ilmiah', 'bobot' => '10%', 'icon' => '🎤'],
                            'penguasaan' => ['label' => 'Penguasaan Materi', 'bobot' => '15%', 'icon' => '📚'],
                            'menjawab' => ['label' => 'Cara Menjawab', 'bobot' => '10%', 'icon' => '💬'],
                            'deskripsi' => ['label' => 'Daya Deskripsi', 'bobot' => '10%', 'icon' => '📝'],
                            'analisis' => ['label' => 'Daya Analisis', 'bobot' => '20%', 'icon' => '🔍'],
                            'menyimpulkan' => ['label' => 'Daya Menyimpulkan', 'bobot' => '15%', 'icon' => '🎯'],
                            'implikasi' => ['label' => 'Daya Implikasi', 'bobot' => '20%', 'icon' => '💡'],
                        ];
                    @endphp

                    @foreach($komponen as $key => $k)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        <span class="text-xl">{{ $k['icon'] }}</span>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $k['label'] }}
                                <span class="text-xs text-gray-400">(Bobot {{ $k['bobot'] }})</span>
                            </label>
                            <div class="flex items-center gap-3 mt-1">
                                <input type="range" wire:model.live="{{ $key }}" min="0" max="100" class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <input type="number" wire:model.live="{{ $key }}" min="0" max="100" class="w-20 px-3 py-1.5 border border-gray-300 rounded-lg text-center text-sm font-bold focus:ring-2 focus:ring-indigo-500">
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Hasil Perhitungan</h3>

                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6">
                    <div class="grid grid-cols-3 gap-6 text-center">
                        <div>
                            <p class="text-sm text-gray-500">Nilai Akhir (NA)</p>
                            <p class="text-4xl font-bold text-indigo-700">{{ $nilaiAkhir }}</p>
                            <p class="text-xs text-gray-400 mt-1">Skala 0-100</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nilai Huruf</p>
                            <p class="text-4xl font-bold {{ $nilaiHuruf === 'A' ? 'text-green-600' : ($nilaiHuruf === 'B' ? 'text-blue-600' : ($nilaiHuruf === 'C' ? 'text-amber-600' : ($nilaiHuruf === 'D' ? 'text-orange-600' : 'text-red-600'))) }}">{{ $nilaiHuruf }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Predikat</p>
                            <p class="text-lg font-bold {{ $nilaiHuruf === 'A' ? 'text-green-700' : ($nilaiHuruf === 'B' ? 'text-blue-700' : ($nilaiHuruf === 'C' ? 'text-amber-700' : ($nilaiHuruf === 'D' ? 'text-orange-700' : 'text-red-700'))) }}">{{ $predikat }}</p>
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
                        <p class="text-xs font-bold text-indigo-700 mt-1">NA = <strong>{{ $nilaiAkhir }}</strong> → {{ $nilaiHuruf }} ({{ $predikat }})</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Catatan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500" placeholder="Catatan tambahan untuk mahasiswa..."></textarea>
            </div>

            <!-- Kaidah Keputusan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">📖 Kaidah Keputusan (Referensi)</h4>
                <div class="grid grid-cols-5 gap-2 text-center text-xs">
                    <div class="bg-green-50 rounded-lg p-2"><span class="font-bold text-green-700">A</span><br><span class="text-green-600">&gt; 85</span><br><span class="text-green-500">Sangat Baik</span></div>
                    <div class="bg-blue-50 rounded-lg p-2"><span class="font-bold text-blue-700">B</span><br><span class="text-blue-600">70-84</span><br><span class="text-blue-500">Baik</span></div>
                    <div class="bg-amber-50 rounded-lg p-2"><span class="font-bold text-amber-700">C</span><br><span class="text-amber-600">55-69</span><br><span class="text-amber-500">Cukup</span></div>
                    <div class="bg-orange-50 rounded-lg p-2"><span class="font-bold text-orange-700">D</span><br><span class="text-orange-600">50-54</span><br><span class="text-orange-500">Kurang</span></div>
                    <div class="bg-red-50 rounded-lg p-2"><span class="font-bold text-red-700">E</span><br><span class="text-red-600">0-49</span><br><span class="text-red-500">Gagal</span></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('dosen.nilai.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Kembali</a>
                <div class="flex gap-3">
                    <button type="button" wire:click="hitungNilai" class="px-6 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 font-medium">🔄 Hitung Ulang</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-700 text-white rounded-xl hover:bg-indigo-800 font-medium shadow-sm shadow-indigo-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $editMode ? 'Perbarui' : 'Simpan' }} Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
