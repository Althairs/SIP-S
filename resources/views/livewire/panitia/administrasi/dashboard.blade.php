<div>
    @section('title', 'Dashboard Administrasi')
    @section('page-title', 'Dashboard Administrasi')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Panel Administrasi Ujian</h2>
                <p class="text-slate-200 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                <p class="text-slate-300 text-sm mt-2">Fokus pada rekap dokumen, revisi, dan nilai ujian. <a href="{{ route('panitia.administrasi.laporan') }}" class="underline hover:text-white">Unduh laporan PDF →</a></p>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 text-slate-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-blue-100">
            <p class="text-sm text-gray-500">Dokumen Siap Arsip</p>
            <p class="text-3xl font-bold text-blue-700 mt-2">{{ $totalDokumenSiap }}</p>
            <p class="text-xs text-gray-400 mt-2">Ujian dijadwalkan dan selesai.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100">
            <p class="text-sm text-gray-500">Ujian Selesai</p>
            <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalSelesai }}</p>
            <p class="text-xs text-gray-400 mt-2">Siap direkap administrasi.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-amber-100">
            <p class="text-sm text-gray-500">Revisi Aktif</p>
            <p class="text-3xl font-bold text-amber-700 mt-2">{{ $totalRevisi }}</p>
            <p class="text-xs text-gray-400 mt-2">Perlu dipantau sampai final.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100">
            <p class="text-sm text-gray-500">Nilai Masuk</p>
            <p class="text-3xl font-bold text-purple-700 mt-2">{{ $totalNilaiMasuk }}</p>
            <p class="text-xs text-gray-400 mt-2">Input nilai selesai/diverifikasi.</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Completed Exams -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Rekap Ujian Selesai</h2>
                <p class="text-sm text-gray-500">Data terbaru untuk bahan arsip dan laporan administrasi.</p>
            </div>
            <div class="p-6">
                @if($recentSelesai->isEmpty())
                    <div class="rounded-xl bg-gray-50 p-8 text-center text-sm text-gray-500">Belum ada ujian selesai untuk direkap.</div>
                @else
                    <div class="space-y-4">
                        @foreach($recentSelesai as $pendaftaran)
                            <div class="rounded-xl border border-gray-100 p-4">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
                                            <span class="text-xs text-gray-400">{{ $pendaftaran->completed_at?->format('d M Y') ?? '-' }}</span>
                                        </div>
                                        <p class="font-semibold text-gray-900">{{ Str::limit($pendaftaran->judul_penelitian, 75) }}</p>
                                        <p class="text-sm text-gray-500">{{ $pendaftaran->mahasiswa->name }} | {{ $pendaftaran->mahasiswa->nim }}</p>
                                    </div>
                                    <div class="text-sm text-right">
                                        <p class="font-semibold text-gray-900">{{ $pendaftaran->nilai_total ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">Nilai akhir</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Context Checklist -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Checklist Administrasi</h2>
            <div class="space-y-3">
                <div class="rounded-xl bg-blue-50 p-4">
                    <p class="text-sm font-semibold text-blue-900">1. Arsipkan dokumen ujian</p>
                    <p class="text-xs text-blue-700 mt-1">Prioritaskan ujian yang sudah selesai dan punya nilai.</p>
                </div>
                <div class="rounded-xl bg-amber-50 p-4">
                    <p class="text-sm font-semibold text-amber-900">2. Pantau revisi aktif</p>
                    <p class="text-xs text-amber-700 mt-1">Pastikan revisi tidak terselip setelah ujian.</p>
                </div>
                <div class="rounded-xl bg-purple-50 p-4">
                    <p class="text-sm font-semibold text-purple-900">3. Cocokkan nilai penguji</p>
                    <p class="text-xs text-purple-700 mt-1">Gunakan data nilai masuk sebagai bahan rekap.</p>
                </div>
                <a href="{{ route('panitia.administrasi.profile') }}" class="block text-center px-4 py-2 bg-slate-800 text-white rounded-xl hover:bg-slate-900 text-sm font-medium">Buka Profil</a>
            </div>
        </div>
    </div>

    <!-- Revisions -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Revisi Terbaru</h2>
            <p class="text-sm text-gray-500">Pantauan administrasi untuk pekerjaan yang belum benar-benar final.</p>
        </div>
        <div class="p-6">
            @if($recentRevisi->isEmpty())
                <div class="rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500">Belum ada revisi terbaru.</div>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($recentRevisi as $revisi)
                        <div class="rounded-xl border border-gray-100 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-1 bg-{{ $revisi->kategoriColor }}-100 text-{{ $revisi->kategoriColor }}-800 rounded-full text-xs font-medium">{{ $revisi->kategoriLabel }}</span>
                                <span class="text-xs text-gray-400">{{ $revisi->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="font-semibold text-gray-900">{{ $revisi->pendaftaran?->mahasiswa?->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($revisi->isi_revisi, 95) }}</p>
                            <p class="text-xs text-gray-400 mt-2">Dosen: {{ $revisi->dosen?->name ?? '-' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
