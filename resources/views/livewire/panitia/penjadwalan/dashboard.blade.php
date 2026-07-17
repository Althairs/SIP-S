<div>
    @section('title', 'Dashboard Penjadwalan')
    @section('page-title', 'Dashboard Penjadwalan Ujian')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Panel Penjadwalan Ujian</h2>
                <p class="text-green-100 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                <p class="text-green-200 text-sm mt-2">{{ Carbon\Carbon::now()->format('l, d F Y') }}</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 text-green-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menunggu Generate</p>
                    <p class="text-3xl font-bold text-yellow-700 mt-2">{{ $pendingApproval }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            @if($pendingApproval > 0)
            <a href="{{ route('panitia.penjadwalan.jadwal') }}" class="inline-block mt-3 text-sm text-green-700 font-medium hover:text-green-800">
                Proses Sekarang →
            </a>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Siap Dijadwalkan</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalDisetujui }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Sudah Dijadwalkan</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalDijadwalkan }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Penguji</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalPenguji }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduling Queue -->
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Prioritas Penjadwalan</h2>
                    <p class="text-sm text-gray-500">Pendaftaran yang sudah melewati masa tunggu dan siap ditindaklanjuti.</p>
                </div>
                <a href="{{ route('panitia.penjadwalan.jadwal') }}" class="text-sm font-medium text-green-700 hover:text-green-800">Kelola Jadwal</a>
            </div>

            @if($siapDijadwalkan->isEmpty())
                <div class="rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500">Belum ada pendaftaran yang siap dijadwalkan.</div>
            @else
                <div class="space-y-3">
                    @foreach($siapDijadwalkan as $pendaftaran)
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 rounded-xl border border-gray-100 p-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $pendaftaran->jenis_ujian)) }}</span>
                                    <span class="text-xs text-gray-400">Masuk {{ $pendaftaran->first_registered_at?->format('d M Y') ?? '-' }}</span>
                                </div>
                                <p class="font-semibold text-gray-900">{{ Str::limit($pendaftaran->judul_penelitian, 70) }}</p>
                                <p class="text-sm text-gray-500">{{ $pendaftaran->mahasiswa->name }} | {{ $pendaftaran->mahasiswa->nim }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Pembimbing:
                                    @if($pendaftaran->pembimbing1 && $pendaftaran->pembimbing1->dosen)
                                        {{ $pendaftaran->pembimbing1->dosen->name }}
                                    @endif
                                    @if($pendaftaran->pembimbing2 && $pendaftaran->pembimbing2->dosen)
                                        {{ $pendaftaran->pembimbing1 ? ' & ' : '' }}{{ $pendaftaran->pembimbing2->dosen->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('panitia.penjadwalan.jadwal') }}" class="px-4 py-2 bg-green-700 text-white rounded-xl hover:bg-green-800 text-sm font-medium text-center">Jadwalkan</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Penjadwalan</h2>
            <div class="space-y-3">
                <div class="rounded-xl bg-yellow-50 p-4">
                    <p class="text-2xl font-bold text-yellow-700">{{ $pendingApproval }}</p>
                    <p class="text-sm text-yellow-700">Siap generate/jadwal</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-2xl font-bold text-gray-700">{{ $menungguMasaTunggu }}</p>
                    <p class="text-sm text-gray-600">Masih menunggu 7 hari</p>
                </div>
                <div class="rounded-xl bg-green-50 p-4">
                    <p class="text-2xl font-bold text-green-700">{{ $jadwalMingguIni->count() }}</p>
                    <p class="text-sm text-green-700">Agenda minggu ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Jadwal Hari Ini</h2>
                <span class="text-sm text-gray-500">{{ Carbon\Carbon::today()->format('d M Y') }}</span>
            </div>
            <div class="p-6">
                @if($jadwalHariIni->isEmpty())
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Tidak ada ujian hari ini</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($jadwalHariIni as $jadwal)
                    <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-xl">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-lg font-bold text-green-700">{{ Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('H') }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ Str::limit($jadwal->judul_penelitian, 40) }}</p>
                            <p class="text-sm text-gray-500">{{ $jadwal->mahasiswa->name }} | {{ $jadwal->ruangan ?? '-' }}</p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ $jadwal->sesi ? 'Sesi '.$jadwal->sesi : '-' }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Jadwal Minggu Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Jadwal Minggu Ini</h2>
                <span class="text-sm text-gray-500">{{ $jadwalMingguIni->count() }} ujian</span>
            </div>
            <div class="p-6">
                @if($jadwalMingguIni->isEmpty())
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Tidak ada ujian minggu ini</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($jadwalMingguIni->take(5) as $jadwal)
                    <div class="flex items-center space-x-3 p-3 border border-gray-100 rounded-xl">
                        <div class="text-center min-w-[50px]">
                            <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('D') }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('d') }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">{{ Str::limit($jadwal->judul_penelitian, 35) }}</p>
                            <p class="text-xs text-gray-500">{{ $jadwal->mahasiswa->name }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('H:i') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('panitia.penjadwalan.jadwal') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                <svg class="w-8 h-8 text-green-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-medium text-green-800">Kelola Jadwal</span>
            </a>

            <a href="{{ route('panitia.penjadwalan.jadwal') }}?tab=pending" class="flex flex-col items-center p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition">
                <svg class="w-8 h-8 text-yellow-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium text-yellow-800">Generate Penguji</span>
            </a>

            <a href="#" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                <svg class="w-8 h-8 text-green-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-sm font-medium text-green-800">Export Jadwal</span>
            </a>

            <a href="{{ route('panitia.penjadwalan.profile') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                <svg class="w-8 h-8 text-green-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-sm font-medium text-green-800">Profile</span>
            </a>
        </div>
    </div>
</div>
