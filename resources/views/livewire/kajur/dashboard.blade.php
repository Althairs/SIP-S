<div>
    @section('title', 'Dashboard Kajur')
    @section('page-title', 'Dashboard Ketua Jurusan')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div class="relative">
            <p class="text-green-100 text-sm font-medium">{{ $greeting }}</p>
            <h2 class="text-2xl font-bold mt-1">{{ Auth::user()->name }}</h2>
            <p class="text-green-200 mt-1 text-sm">{{ $jurusanNama }}</p>
        </div>
    </div>

    <!-- Status Pendaftaran - Flow -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-5">Alur Pendaftaran Skripsi</h3>

        @php
            $totalAll = $totalMenungguKajur + $totalSiapDijadwalkan + $totalDijadwalkan;
            $pct1 = $totalAll > 0 ? round($totalMenungguKajur / $totalAll * 100) : 0;
            $pct2 = $totalAll > 0 ? round($totalSiapDijadwalkan / $totalAll * 100) : 0;
            $pct3 = $totalAll > 0 ? round($totalDijadwalkan / $totalAll * 100) : 0;
        @endphp

        {{-- Progress Bar --}}
        @if($totalAll > 0)
        <div class="flex rounded-full overflow-hidden h-3 mb-6 bg-gray-100">
            <div class="bg-amber-400 transition-all duration-500" style="width: {{ $pct1 }}%"></div>
            <div class="bg-green-400 transition-all duration-500" style="width: {{ $pct2 }}%"></div>
            <div class="bg-emerald-600 transition-all duration-500" style="width: {{ $pct3 }}%"></div>
        </div>
        @endif

        {{-- Flow Steps --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-amber-700">{{ $totalMenungguKajur }}</p>
                    <p class="text-xs text-amber-600 mt-1 font-medium">Menunggu Persetujuan</p>
                </div>
            </div>

            <div class="relative flex items-center justify-center">
                <div class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 -ml-2 text-gray-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center w-full">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-green-700">{{ $totalSiapDijadwalkan }}</p>
                    <p class="text-xs text-green-600 mt-1 font-medium">Siap Dijadwalkan</p>
                </div>
            </div>

            <div class="relative flex items-center justify-center">
                <div class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 -ml-2 text-gray-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center w-full">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-2xl font-bold text-emerald-700">{{ $totalDijadwalkan }}</p>
                    <p class="text-xs text-emerald-600 mt-1 font-medium">Sudah Dijadwalkan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Dosen</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDosen }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMahasiswa }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Panitia</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPanitia }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>

            {{-- Panitia Breakdown Bar --}}
            @if($totalPanitia > 0)
            <div class="mt-4">
                <div class="flex rounded-full overflow-hidden h-2 bg-gray-100">
                    @php
                        $pv = $totalPanitia > 0 ? round($totalPanitiaVerifikasi / $totalPanitia * 100) : 0;
                        $pp = $totalPanitia > 0 ? round($totalPanitiaPenjadwalan / $totalPanitia * 100) : 0;
                        $pa = 100 - $pv - $pp;
                    @endphp
                    <div class="bg-amber-400" style="width: {{ $pv }}%"></div>
                    <div class="bg-green-500" style="width: {{ $pp }}%"></div>
                    <div class="bg-emerald-400" style="width: {{ $pa }}%"></div>
                </div>
                <div class="flex justify-between mt-2 text-xs">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Verifikasi ({{ $totalPanitiaVerifikasi }})</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Penjadwalan ({{ $totalPanitiaPenjadwalan }})</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Administrasi ({{ $totalPanitiaAdministrasi }})</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    @if(count($recentPendaftarans) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-3">
            @foreach($recentPendaftarans as $p)
            <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $p['nama'] }}</p>
                        <span class="text-xs text-gray-400">({{ $p['nim'] }})</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">{{ $p['judul'] }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @php
                        $statusColor = match($p['status']) {
                            'disetujui_sekjur' => 'amber',
                            'disetujui_kajur' => 'green',
                            'dijadwalkan' => 'emerald',
                            'pending' => 'gray',
                            default => 'gray',
                        };
                        $statusLabel = match($p['status']) {
                            'disetujui_sekjur' => 'Menunggu',
                            'disetujui_kajur' => 'Disetujui',
                            'dijadwalkan' => 'Dijadwalkan',
                            'pending' => 'Pending',
                            default => $p['status'],
                        };
                    @endphp
                    <span class="px-2 py-0.5 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs font-medium">{{ $statusLabel }}</span>
                    <p class="text-xs text-gray-400 mt-1">{{ $p['tanggal'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
