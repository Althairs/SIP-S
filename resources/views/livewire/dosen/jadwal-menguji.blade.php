<div>
    @section('title', 'Jadwal Menguji')
    @section('page-title', 'Jadwal Menguji Saya')

    <!-- Header Info -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Jadwal Menguji</h2>
                <p class="text-indigo-100 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
                <div class="flex gap-4 mt-3">
                    <div class="bg-white/20 rounded-lg px-3 py-1.5 text-sm">
                        <span class="text-indigo-200">Akan Datang:</span>
                        <span class="font-bold ml-1">{{ $jadwalAkanDatang->count() }}</span>
                    </div>
                    <div class="bg-white/20 rounded-lg px-3 py-1.5 text-sm">
                        <span class="text-indigo-200">Selesai:</span>
                        <span class="font-bold ml-1">{{ $jadwalSelesai->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <svg class="w-20 h-20 text-indigo-300 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6">
        <button wire:click="setTab('upcoming')" class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $tab === 'upcoming' ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Akan Datang
            @if($jadwalAkanDatang->count() > 0)
            <span class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded-full text-xs">{{ $jadwalAkanDatang->count() }}</span>
            @endif
        </button>
        <button wire:click="setTab('history')" class="px-6 py-3 text-sm font-medium border-b-2 transition {{ $tab === 'history' ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Riwayat
            @if($jadwalSelesai->count() > 0)
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs">{{ $jadwalSelesai->count() }}</span>
            @endif
        </button>
    </div>

    {{-- Tab Akan Datang --}}
    @if($tab === 'upcoming')
        @if($jadwalAkanDatang->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal</h3>
            <p class="text-gray-500">Anda belum memiliki jadwal menguji yang akan datang.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($jadwalAkanDatang as $jp)
            @php $p = $jp->pendaftaran; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 hover:shadow-md transition cursor-pointer" wire:click="showDetailUjian({{ $p->id }})">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <!-- Tanggal Box -->
                            <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 text-white rounded-xl p-3 text-center min-w-[75px] shadow-lg shadow-indigo-200">
                                <p class="text-xs font-medium opacity-80">{{ $p->tanggal_ujian ? Carbon\Carbon::parse($p->tanggal_ujian)->format('M') : '-' }}</p>
                                <p class="text-2xl font-bold">{{ $p->tanggal_ujian ? Carbon\Carbon::parse($p->tanggal_ujian)->format('d') : '-' }}</p>
                                <p class="text-xs opacity-80">{{ $p->tanggal_ujian ? Carbon\Carbon::parse($p->tanggal_ujian)->format('Y') : '-' }}</p>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span>
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">Sebagai: {{ ucwords(str_replace('_', ' ', $jp->peran)) }}</span>
                                </div>

                                <h3 class="font-semibold text-gray-900">{{ Str::limit($p->judul_penelitian, 70) }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $p->mahasiswa->name }} ({{ $p->mahasiswa->nim }})</p>

                                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $p->tanggal_ujian ? Carbon\Carbon::parse($p->tanggal_ujian)->format('H:i') . ' WIB' : '-' }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $p->ruangan ?? '-' }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                        Sesi {{ $p->sesi ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    @endif

    {{-- Tab Riwayat --}}
    @if($tab === 'history')
        @if($jadwalSelesai->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Belum ada riwayat menguji.</div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Mahasiswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Peran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($jadwalSelesai as $jp)
                    @php $p = $jp->pendaftaran; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $p->tanggal_ujian ? Carbon\Carbon::parse($p->tanggal_ujian)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $p->mahasiswa->name }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span></td>
                        <td class="px-6 py-4 text-sm">{{ ucwords(str_replace('_', ' ', $jp->peran)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ Str::limit($p->judul_penelitian, 40) }}</td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="showDetailUjian({{ $p->id }})" class="text-sm text-indigo-600 hover:underline">Lihat</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endif

    {{-- Modal Detail --}}
    @if($showDetail && $selectedUjian)
    @php $p = $selectedUjian->pendaftaran; @endphp
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDetail"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-t-3xl p-6 text-white sticky top-0 z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span>
                            <h2 class="text-xl font-bold mt-2">Detail Ujian</h2>
                        </div>
                        <button wire:click="closeDetail" class="text-white/80 hover:text-white bg-white/10 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Status -->
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1.5 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-sm font-medium">{{ $p->statusLabel }}</span>
                        <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs">Peran: {{ ucwords(str_replace('_', ' ', $selectedUjian->peran)) }}</span>
                    </div>

                    <!-- Judul -->
                    <div class="bg-gray-50 rounded-2xl p-5">
                        <h3 class="text-lg font-bold text-gray-900">{{ $p->judul_penelitian }}</h3>
                        @if($p->abstrak)
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($p->abstrak, 200) }}</p>
                        @endif
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="border rounded-xl p-3"><p class="text-xs text-gray-500">Tanggal</p><p class="font-bold">{{ $p->tanggal_ujian?->format('d M Y') ?? '-' }}</p></div>
                        <div class="border rounded-xl p-3"><p class="text-xs text-gray-500">Waktu</p><p class="font-bold">{{ $p->tanggal_ujian?->format('H:i') ?? '-' }} WIB</p></div>
                        <div class="border rounded-xl p-3"><p class="text-xs text-gray-500">Ruangan</p><p class="font-bold">{{ $p->ruangan ?? '-' }}</p></div>
                        <div class="border rounded-xl p-3"><p class="text-xs text-gray-500">Sesi</p><p class="font-bold">{{ $p->sesi ?? '-' }}</p></div>
                    </div>

                    <!-- Mahasiswa -->
                    <div class="bg-blue-50 rounded-xl p-4 flex items-center space-x-3">
                        <img class="w-12 h-12 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($p->mahasiswa->name) }}&background=4f46e5&color=fff" alt="">
                        <div>
                            <p class="font-bold">{{ $p->mahasiswa->name }}</p>
                            <p class="text-sm text-gray-600">{{ $p->mahasiswa->nim }} | {{ $p->jurusan?->nama_jurusan }}</p>
                        </div>
                    </div>

                    <!-- Penguji -->
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-3">Tim Penguji</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($p->pengujis->count() > 0)
                                @foreach($p->pengujis as $penguji)
                                <div class="border rounded-xl p-4 {{ $penguji->dosen_id == auth()->id() ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200' }}">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($penguji->dosen->name ?? '') }}&background=4f46e5&color=fff" alt="">
                                        <div>
                                            <p class="font-medium text-sm">{{ $penguji->dosen->name ?? '-' }} {{ $penguji->dosen_id == auth()->id() ? '(Anda)' : '' }}</p>
                                            <p class="text-xs text-gray-500">{{ str_replace('_', ' ', $penguji->peran) }}</p>
                                            @if($penguji->dosen->kepakaran)<p class="text-xs text-purple-600">{{ $penguji->dosen->kepakaran->nama_kepakaran }}</p>@endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            @if($p->pembimbing1?->dosen)
                            <div class="border rounded-xl p-4 border-green-200 bg-green-50">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($p->pembimbing1->dosen->name) }}&background=059669&color=fff" alt="">
                                    <div>
                                        <p class="font-medium text-sm">{{ $p->pembimbing1->dosen->name }}</p>
                                        <p class="text-xs text-gray-500">Pembimbing 1</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($p->pembimbing2?->dosen)
                            <div class="border rounded-xl p-4 border-green-200 bg-green-50">
                                <div class="flex items-center space-x-3">
                                    <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($p->pembimbing2->dosen->name) }}&background=059669&color=fff" alt="">
                                    <div>
                                        <p class="font-medium text-sm">{{ $p->pembimbing2->dosen->name }}</p>
                                        <p class="text-xs text-gray-500">Pembimbing 2</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bidang Keahlian -->
                    @if($p->bidangKeahlians->count() > 0)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Bidang Keahlian</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($p->bidangKeahlians as $bk)
                            <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm">{{ $bk->nama_bidang }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Berkas -->
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Berkas</p>
                        <div class="flex flex-wrap gap-3">
                            @if($p->file_proposal)
                            <a href="{{ asset('storage/' . $p->file_proposal) }}" target="_blank" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-sm hover:bg-blue-100 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Proposal
                            </a>
                            @endif
                            @if($p->file_skripsi)
                            <a href="{{ asset('storage/' . $p->file_skripsi) }}" target="_blank" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-sm hover:bg-blue-100 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Skripsi
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
