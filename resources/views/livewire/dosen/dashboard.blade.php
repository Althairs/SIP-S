<div>
    @section('title', 'Dashboard Dosen')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-indigo-100 mt-1">NIP: {{ Auth::user()->nip }} | {{ Auth::user()->jurusan?->nama_jurusan }}</p>
                @if(Auth::user()->kepakaran)
                <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs mt-2 inline-block">{{ Auth::user()->kepakaran->nama_kepakaran }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-indigo-100">
            <p class="text-xs text-gray-500">Total Menguji</p>
            <p class="text-2xl font-bold text-indigo-700">{{ $totalMenguji }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-100">
            <p class="text-xs text-gray-500">Revisi Pending</p>
            <p class="text-2xl font-bold {{ $totalRevisi > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $totalRevisi }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-100">
            <p class="text-xs text-gray-500">Sisa Kuota Pembimbing</p>
            <p class="text-2xl font-bold text-green-700">{{ $kuotaPembimbing }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-purple-100">
            <p class="text-xs text-gray-500">Sisa Kuota Penguji</p>
            <p class="text-2xl font-bold text-purple-700">{{ $kuotaPenguji }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100"><h2 class="text-lg font-semibold">📅 Jadwal Menguji Hari Ini</h2></div>
            <div class="p-4">
                @if($jadwalHariIni->isEmpty())
                <div class="text-center py-8 text-gray-500">Tidak ada jadwal menguji hari ini</div>
                @else
                <div class="space-y-3">
                    @foreach($jadwalHariIni as $jp)
                    <div class="p-3 bg-indigo-50 rounded-xl">
                        <p class="font-medium text-sm">{{ $jp->pendaftaran->mahasiswa->name }}</p>
                        <p class="text-xs text-gray-500">{{ Str::limit($jp->pendaftaran->judul_penelitian, 50) }}</p>
                        <p class="text-xs text-indigo-600 mt-1">{{ $jp->pendaftaran->tanggal_ujian?->format('H:i') }} | {{ $jp->pendaftaran->ruangan }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Revisi Pending -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold">📝 Revisi Menunggu</h2>
                <a href="{{ route('dosen.revisi.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="p-4">
                @if($pendingRevisis->isEmpty())
                <div class="text-center py-8 text-gray-500">Tidak ada revisi menunggu</div>
                @else
                <div class="space-y-3">
                    @foreach($pendingRevisis as $rev)
                    <div class="p-3 bg-amber-50 rounded-xl">
                        <div class="flex justify-between">
                            <p class="font-medium text-sm">{{ $rev->pendaftaran->mahasiswa->name }}</p>
                            <span class="px-2 py-0.5 bg-{{ $rev->kategoriColor }}-100 text-{{ $rev->kategoriColor }}-800 rounded-full text-xs">{{ $rev->kategoriLabel }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($rev->isi_revisi, 80) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Deadline: {{ $rev->deadline?->format('d M Y') ?? '-' }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('dosen.jadwal') }}" class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <svg class="w-8 h-8 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-sm font-medium">Jadwal Menguji</span>
        </a>
        <a href="{{ route('dosen.nilai.index') }}" class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span class="text-sm font-medium">Beri Nilai</span>
        </a>
        <a href="{{ route('dosen.kuota') }}" class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span class="text-sm font-medium">Kuota Saya</span>
        </a>
        <a href="{{ route('dosen.profile') }}" class="flex flex-col items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-sm font-medium">Profile</span>
        </a>
    </div>
</div>
