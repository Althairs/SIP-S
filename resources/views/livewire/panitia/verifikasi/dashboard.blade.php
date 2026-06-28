<div>
    @section('title', 'Dashboard Verifikasi')
    @section('page-title', 'Dashboard Verifikasi Berkas')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Panel Verifikasi Berkas</h2>
                <p class="text-orange-100 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                <p class="text-orange-200 text-sm mt-2">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 text-orange-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-amber-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-amber-700 mt-2">{{ $totalPending }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            @if($totalPending > 0)
                <a href="{{ route('panitia.verifikasi.berkas') }}"
                    class="inline-block mt-3 text-sm text-orange-700 font-medium hover:text-orange-800">
                    Verifikasi Sekarang →
                </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Disetujui</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalDisetujui }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-red-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Ditolak</p>
                    <p class="text-3xl font-bold text-red-700 mt-2">{{ $totalDitolak }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-blue-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Diverifikasi</p>
                    <p class="text-3xl font-bold text-blue-700 mt-2">{{ $totalDiverifikasi }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Queue -->
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Prioritas Verifikasi</h2>
                    <p class="text-sm text-gray-500">Berkas pending terlama ditampilkan lebih dulu agar antrean terasa jelas.</p>
                </div>
                <a href="{{ route('panitia.verifikasi.berkas') }}" class="text-sm font-medium text-orange-700 hover:text-orange-800">Buka Berkas</a>
            </div>

            @if($prioritasBerkas->isEmpty())
                <div class="rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500">Tidak ada berkas yang menunggu verifikasi.</div>
            @else
                <div class="space-y-3">
                    @foreach($prioritasBerkas as $pendaftaran)
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 rounded-xl border border-gray-100 p-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
                                    <span class="text-xs text-gray-400">Masuk {{ $pendaftaran->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <p class="font-semibold text-gray-900">{{ Str::limit($pendaftaran->judul_penelitian, 70) }}</p>
                                <p class="text-sm text-gray-500">{{ $pendaftaran->mahasiswa->name }} | {{ $pendaftaran->mahasiswa->nim }}</p>
                            </div>
                            <a href="{{ route('panitia.verifikasi.berkas') }}" class="px-4 py-2 bg-orange-700 text-white rounded-xl hover:bg-orange-800 text-sm font-medium text-center">Verifikasi</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Progress Berkas</h2>
            <div class="rounded-2xl bg-orange-50 p-5">
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-sm text-orange-700">Terselesaikan</p>
                        <p class="text-4xl font-bold text-orange-900">{{ $progressVerifikasi }}%</p>
                    </div>
                    <span class="text-sm text-orange-700">{{ $totalDiverifikasi }} dari {{ $totalDiverifikasi + $totalPending }}</span>
                </div>
                <div class="mt-4 h-3 rounded-full bg-orange-100 overflow-hidden">
                    <div class="h-full rounded-full bg-orange-600" style="width: {{ $progressVerifikasi }}%"></div>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-amber-50 p-4 text-center">
                    <p class="text-2xl font-bold text-amber-700">{{ $totalPending }}</p>
                    <p class="text-xs text-amber-700">Menunggu</p>
                </div>
                <div class="rounded-xl bg-green-50 p-4 text-center">
                    <p class="text-2xl font-bold text-green-700">{{ $totalDisetujui }}</p>
                    <p class="text-xs text-green-700">Disetujui</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-amber-700">{{ $totalPending }}</p>
            <p class="text-sm text-amber-600">Menunggu</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $totalDisetujui }}</p>
            <p class="text-sm text-green-600">Diteruskan ke Sekjur</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-red-700">{{ $totalDitolak }}</p>
            <p class="text-sm text-red-600">Ditolak</p>
        </div>
    </div>

    <!-- Recent Verifications -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Verifikasi Terbaru</h2>
            <a href="{{ route('panitia.verifikasi.berkas') }}"
                class="text-sm text-orange-600 hover:underline font-medium">Lihat Semua →</a>
        </div>
        <div class="p-6">
            @if($recentVerifications->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-500">Belum ada pendaftaran yang perlu diverifikasi.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentVerifications as $p)
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-sm transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span
                                            class="px-2 py-1 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs font-medium">
                                            {{ $p->statusLabel }}
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                            {{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $p->created_at->format('d M Y H:i') }}</span>
                                    </div>

                                    <h3 class="font-semibold text-gray-900">{{ Str::limit($p->judul_penelitian, 60) }}</h3>

                                    <!-- Detail Ringkas -->
                                    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-500">Mahasiswa</p>
                                            <p class="font-medium">{{ $p->mahasiswa->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Pembimbing</p>
                                            <p class="font-medium text-sm">{{ $p->pembimbing1?->dosen?->name ?? '-' }}</p>
                                            @if($p->pembimbing2?->dosen)
                                                <p class="text-xs text-gray-400">{{ $p->pembimbing2->dosen->name }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Berkas</p>
                                            <div class="flex gap-1 mt-0.5">
                                                @if($p->file_proposal)
                                                    <a href="{{ asset('storage/' . $p->file_proposal) }}" target="_blank"
                                                        class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">Proposal</a>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                                @if($p->file_krs)
                                                    <a href="{{ asset('storage/' . $p->file_krs) }}" target="_blank"
                                                        class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">KRS</a>
                                                @endif
                                                @if($p->file_transkrip)
                                                    <a href="{{ asset('storage/' . $p->file_transkrip) }}" target="_blank"
                                                        class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs hover:bg-purple-200">Transkrip</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Bidang Keahlian</p>
                                            @if($p->bidangKeahlians->count() > 0)
                                                <div class="flex flex-wrap gap-1 mt-0.5">
                                                    @foreach($p->bidangKeahlians->take(2) as $bk)
                                                        <span
                                                            class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                                                    @endforeach
                                                    @if($p->bidangKeahlians->count() > 2)
                                                        <span
                                                            class="text-xs text-gray-400">+{{ $p->bidangKeahlians->count() - 2 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Abstrak (collapsed) -->
                                    @if($p->abstrak)
                                        <div class="mt-3" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-xs text-blue-600 hover:underline">
                                                <span x-text="open ? 'Sembunyikan' : 'Lihat'"></span> Abstrak
                                            </button>
                                            <p x-show="open" x-cloak class="text-xs text-gray-600 mt-2 p-3 bg-gray-50 rounded-lg">
                                                {{ $p->abstrak }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($p->status === 'pending')
                                <div class="mt-4 flex items-center gap-3 pt-4 border-t">
                                    <a href="{{ route('panitia.verifikasi.berkas') }}"
                                        class="px-4 py-2 bg-orange-700 text-white rounded-xl hover:bg-orange-800 text-sm font-medium">
                                        Verifikasi Sekarang
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('panitia.verifikasi.berkas') }}"
                class="flex flex-col items-center p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition">
                <svg class="w-8 h-8 text-orange-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-orange-800">Verifikasi Berkas</span>
            </a>

            <a href="{{ route('panitia.verifikasi.berkas') }}?statusFilter=disetujui_panitia"
                class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                <svg class="w-8 h-8 text-green-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-green-800">Lihat Disetujui</span>
            </a>

            <a href="{{ route('panitia.verifikasi.berkas') }}?statusFilter=ditolak_panitia"
                class="flex flex-col items-center p-4 bg-red-50 rounded-xl hover:bg-red-100 transition">
                <svg class="w-8 h-8 text-red-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-red-800">Lihat Ditolak</span>
            </a>

            <a href="{{ route('panitia.verifikasi.profile') }}"
                class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                <svg class="w-8 h-8 text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm font-medium text-gray-800">Profile</span>
            </a>
        </div>
    </div>
</div>
