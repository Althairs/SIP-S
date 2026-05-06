<div>
    @section('title', 'Jadwal Ujian')
    @section('page-title', 'Jadwal Ujian Saya')

    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-6" x-data="{ tab: 'upcoming' }">
        <button @click="tab = 'upcoming'"
                :class="tab === 'upcoming' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-6 py-3 text-sm font-medium border-b-2 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Akan Datang
            @if($upcomingUjian->count() > 0)
            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $upcomingUjian->count() }}</span>
            @endif
        </button>
        <button @click="tab = 'history'"
                :class="tab === 'history' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-6 py-3 text-sm font-medium border-b-2 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Riwayat
            @if($riwayatUjian->count() > 0)
            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs">{{ $riwayatUjian->count() }}</span>
            @endif
        </button>
    </div>

    <!-- Upcoming Ujian -->
    <div x-data="{ tab: 'upcoming' }" x-show="tab === 'upcoming'">
        @if($upcomingUjian->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Ujian Mendatang</h3>
            <p class="text-gray-500 mb-4">Anda belum memiliki jadwal ujian yang akan datang.</p>
            <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="inline-flex px-5 py-2.5 bg-blue-700 text-white rounded-xl hover:bg-blue-800 font-medium">Daftar Ujian</a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($upcomingUjian as $ujian)
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 hover:shadow-md transition cursor-pointer" wire:click="showDetailUjian({{ $ujian->id }})">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <!-- Tanggal Box -->
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl p-3 text-center min-w-[75px] shadow-lg shadow-blue-200">
                                <p class="text-xs font-medium opacity-80">{{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('M') }}</p>
                                <p class="text-2xl font-bold">{{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d') }}</p>
                                <p class="text-xs opacity-80">{{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('Y') }}</p>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ ucwords(str_replace('_', ' ', $ujian->jenis_ujian)) }}
                                    </span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">Terjadwal</span>
                                </div>

                                <h3 class="font-semibold text-gray-900 text-lg">{{ Str::limit($ujian->judul_penelitian, 70) }}</h3>

                                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('H:i') }} WIB
                                        @if($ujian->sesi)
                                        @php $si = ($ujian->sesi ?? 1) - 1; @endphp
                                        <span class="ml-1 text-blue-600">(Sesi {{ $ujian->sesi }})</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $ujian->ruangan ?? 'Ruangan belum ditentukan' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Pembimbing: {{ $ujian->pembimbing1?->dosen?->name ?? 'Belum ditentukan' }}
                                    </div>
                                </div>

                                <!-- Bidang Keahlian -->
                                @if($ujian->bidangKeahlians->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-3">
                                    @foreach($ujian->bidangKeahlians as $bk)
                                    <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Riwayat Ujian -->
    <div x-data="{ tab: 'upcoming' }" x-show="tab === 'history'">
        @if($riwayatUjian->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat</h3>
            <p class="text-gray-500">Riwayat ujian akan muncul setelah Anda menyelesaikan ujian.</p>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jenis Ujian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nilai</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($riwayatUjian as $ujian)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $ujian->tanggal_ujian ? Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $ujian->jenis_ujian)) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ Str::limit($ujian->judul_penelitian, 40) }}</td>
                            <td class="px-6 py-4">
                                @if($ujian->nilai_total)
                                <span class="font-bold text-gray-900">{{ $ujian->nilai_total }}</span>
                                <span class="text-xs text-gray-500 ml-1">({{ $ujian->grade }})</span>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="showDetailUjian({{ $ujian->id }})" class="text-sm text-blue-700 hover:text-blue-800 font-medium">Lihat Detail</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- ============= MODAL DETAIL UJIAN (BESAR) ============= --}}
    @if($showDetail && $selectedUjian)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDetail"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header dengan gradient -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-t-3xl p-6 text-white sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="px-3 py-1 bg-white/20 text-white rounded-full text-xs font-medium">
                                {{ ucwords(str_replace('_', ' ', $selectedUjian->jenis_ujian)) }}
                            </span>
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
                        <span class="px-3 py-1.5 bg-{{ $selectedUjian->statusColor }}-100 text-{{ $selectedUjian->statusColor }}-800 rounded-full text-sm font-medium">
                            {{ $selectedUjian->statusLabel }}
                        </span>
                        @if($selectedUjian->tanggal_ujian && $selectedUjian->tanggal_ujian->isPast())
                        <span class="text-xs text-gray-500">Ujian telah berlalu</span>
                        @elseif($selectedUjian->tanggal_ujian)
                        <span class="text-xs text-blue-600">
                            {{ (int) ceil(now()->diffInDays($selectedUjian->tanggal_ujian)) }} hari lagi
                        </span>
                        @endif
                    </div>

                    <!-- Judul & Abstrak -->
                    <div class="bg-gray-50 rounded-2xl p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $selectedUjian->judul_penelitian }}</h3>
                        @if($selectedUjian->abstrak)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-1">Abstrak</p>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $selectedUjian->abstrak }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Grid Info Utama -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Tanggal Ujian</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ $selectedUjian->tanggal_ujian ? $selectedUjian->tanggal_ujian->format('d M Y') : 'Belum ditentukan' }}
                            </p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Waktu</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ $selectedUjian->tanggal_ujian ? $selectedUjian->tanggal_ujian->format('H:i') . ' WIB' : '-' }}
                            </p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Sesi</p>
                            <p class="font-bold text-gray-900 text-lg">Sesi {{ $selectedUjian->sesi ?? '-' }}</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Ruangan</p>
                            <p class="font-bold text-gray-900">{{ $selectedUjian->ruangan ?? 'Belum ditentukan' }}</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Jurusan</p>
                            <p class="font-bold text-gray-900">{{ $selectedUjian->jurusan?->nama_jurusan ?? '-' }}</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Prodi</p>
                            <p class="font-bold text-gray-900">{{ $selectedUjian->prodi?->nama_prodi ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Bidang Keahlian -->
                    @if($selectedUjian->bidangKeahlians->count() > 0)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Bidang Keahlian</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($selectedUjian->bidangKeahlians as $bk)
                            <span class="px-3 py-1.5 bg-teal-100 text-teal-800 rounded-full text-sm font-medium">{{ $bk->nama_bidang }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Pembimbing -->
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-3">Dosen Pembimbing</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php $p1 = $selectedUjian->pembimbing1; @endphp
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <p class="text-xs text-blue-600 font-medium mb-1">Pembimbing 1</p>
                                @if($p1 && $p1->dosen)
                                <div class="flex items-center space-x-3">
                                    <img class="w-12 h-12 rounded-full bg-blue-200" src="https://ui-avatars.com/api/?name={{ urlencode($p1->dosen->name) }}&background=2563eb&color=fff" alt="">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $p1->dosen->name }}</p>
                                        <p class="text-xs text-gray-500">NIP: {{ $p1->dosen->nip ?? '-' }}</p>
                                        @if($p1->dosen->kepakaran)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs">{{ $p1->dosen->kepakaran->nama_kepakaran }}</span>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <p class="text-sm text-gray-400">Belum ditentukan</p>
                                @endif
                            </div>

                            @php $p2 = $selectedUjian->pembimbing2; @endphp
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <p class="text-xs text-blue-600 font-medium mb-1">Pembimbing 2</p>
                                @if($p2 && $p2->dosen)
                                <div class="flex items-center space-x-3">
                                    <img class="w-12 h-12 rounded-full bg-blue-200" src="https://ui-avatars.com/api/?name={{ urlencode($p2->dosen->name) }}&background=2563eb&color=fff" alt="">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $p2->dosen->name }}</p>
                                        <p class="text-xs text-gray-500">NIP: {{ $p2->dosen->nip ?? '-' }}</p>
                                        @if($p2->dosen->kepakaran)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs">{{ $p2->dosen->kepakaran->nama_kepakaran }}</span>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <p class="text-sm text-gray-400">Belum ditentukan</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ============= DOSEN PENGUJI (TAMBAHAN BARU) ============= --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-3">Dosen Penguji</p>
                        @php $pengujiList = $selectedUjian->pengujis; @endphp
                        @if($pengujiList->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pengujiList as $penguji)
                            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-800 rounded-full text-xs font-bold uppercase">
                                        {{ str_replace('_', ' ', $penguji->peran) }}
                                    </span>
                                    @if($penguji->is_overload)
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium flex items-center gap-1">
                                        ⚠️ Overload
                                    </span>
                                    @endif
                                </div>
                                @if($penguji->dosen)
                                <div class="flex items-center space-x-3">
                                    <img class="w-12 h-12 rounded-full bg-amber-200" src="https://ui-avatars.com/api/?name={{ urlencode($penguji->dosen->name) }}&background=d97706&color=fff" alt="">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $penguji->dosen->name }}</p>
                                        <p class="text-xs text-gray-500">NIP: {{ $penguji->dosen->nip ?? '-' }}</p>
                                        @if($penguji->dosen->kepakaran)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs">{{ $penguji->dosen->kepakaran->nama_kepakaran }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($penguji->kuota_tersisa !== null)
                                <div class="mt-2 flex items-center gap-2 text-xs">
                                    <span class="text-gray-500">Kuota tersisa:</span>
                                    <span class="font-medium {{ $penguji->kuota_tersisa < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $penguji->kuota_tersisa }}
                                    </span>
                                </div>
                                @endif
                                @if($penguji->catatan)
                                <p class="mt-2 text-xs text-gray-500 bg-white/50 rounded-lg p-2">{{ $penguji->catatan }}</p>
                                @endif
                                @else
                                <p class="text-sm text-gray-400">Belum ditentukan</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-amber-700">Dosen penguji belum ditentukan</p>
                        </div>
                        @endif
                    </div>

                    {{-- Nilai (jika sudah ada) --}}
                    @if($selectedUjian->nilai_total)
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                        <h3 class="text-lg font-bold text-purple-800 mb-4">Hasil Ujian</h3>
                        <div class="grid grid-cols-3 gap-6 text-center">
                            <div>
                                <p class="text-sm text-purple-600 mb-1">Nilai Total</p>
                                <p class="text-4xl font-bold text-purple-800">{{ $selectedUjian->nilai_total }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-purple-600 mb-1">Grade</p>
                                <p class="text-4xl font-bold text-purple-800">{{ $selectedUjian->grade }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-purple-600 mb-1">Status</p>
                                <p class="text-4xl font-bold text-green-600">Lulus</p>
                            </div>
                        </div>
                        @if($selectedUjian->catatan_penguji)
                        <div class="mt-4 bg-white/50 rounded-xl p-3">
                            <p class="text-xs text-purple-600 font-medium mb-1">Catatan Penguji</p>
                            <p class="text-sm text-gray-700">{{ $selectedUjian->catatan_penguji }}</p>
                        </div>
                        @endif
                        @if($selectedUjian->catatan_revisi)
                        <div class="mt-3 bg-amber-50 rounded-xl p-3 border border-amber-200">
                            <p class="text-xs text-amber-700 font-medium mb-1">Catatan Revisi</p>
                            <p class="text-sm text-amber-800">{{ $selectedUjian->catatan_revisi }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Berkas -->
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-3">Berkas</p>
                        <div class="grid grid-cols-2 gap-3">
                            @if($selectedUjian->file_proposal)
                            <a href="{{ asset('storage/' . $selectedUjian->file_proposal) }}" target="_blank" class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition border border-gray-200">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="text-sm font-medium text-blue-700">File Proposal</span>
                            </a>
                            @endif
                            @if($selectedUjian->file_skripsi)
                            <a href="{{ asset('storage/' . $selectedUjian->file_skripsi) }}" target="_blank" class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition border border-gray-200">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="text-sm font-medium text-blue-700">File Skripsi</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="text-xs text-gray-400 border-t pt-4 space-y-1">
                        <p>Pendaftaran: {{ $selectedUjian->created_at->format('d M Y H:i') }}</p>
                        @if($selectedUjian->scheduled_at)
                        <p>Dijadwalkan: {{ $selectedUjian->scheduled_at->format('d M Y H:i') }}</p>
                        @endif
                        @if($selectedUjian->completed_at)
                        <p>Selesai: {{ $selectedUjian->completed_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
