<div>
    @section('title', 'Penjadwalan Ujian')
    @section('page-title', 'Atur Jadwal Ujian')

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6 gap-1 overflow-x-auto">
        <button wire:click="setTab('siap')"
            class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ $tab === 'siap' ? 'border-cyan-600 text-cyan-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Siap Dijadwalkan
            @if($countSiap > 0)<span
            class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs">{{ $countSiap }}</span>@endif
        </button>
        <button wire:click="setTab('scheduled')"
            class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ $tab === 'scheduled' ? 'border-cyan-600 text-cyan-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Terjadwal
            @if($countScheduled > 0)<span
            class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $countScheduled }}</span>@endif
        </button>
        <button wire:click="setTab('completed')"
            class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap {{ $tab === 'completed' ? 'border-cyan-600 text-cyan-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Selesai
            @if($countCompleted > 0)<span
            class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">{{ $countCompleted }}</span>@endif
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari mahasiswa, NIM, atau judul..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.change="jenisFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl">
                <option value="">Semua Jenis Ujian</option>
                <option value="seminar_proposal">Seminar Proposal</option>
                <option value="seminar_hasil">Seminar Hasil</option>
                <option value="sidang_skripsi">Sidang Skripsi</option>
            </select>
        </div>
    </div>

    {{-- ============= TAB: SIAP DIJADWALKAN ============= --}}
    @if($tab === 'siap')
        @if($countSiap > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="selectAll"
                            class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                        <span class="text-sm text-gray-600">
                            Pilih Semua ({{ $countSiap }})
                            @if(count($selectedIds) > 0)<span class="ml-2 font-medium text-cyan-700">{{ count($selectedIds) }}
                            terpilih</span>@endif
                        </span>
                    </div>
                    <button wire:click="openBatchModal"
                        class="px-5 py-2 bg-cyan-700 text-white rounded-xl hover:bg-cyan-800 transition font-medium text-sm {{ count($selectedIds) == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ count($selectedIds) == 0 ? 'disabled' : '' }}>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Jadwalkan yang Dipilih
                    </button>
                </div>
            </div>
        @endif

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-amber-800">Aturan Penjadwalan</p>
                    <p class="text-xs text-amber-700 mt-1">Tanggal ujian minimal <strong>7 hari</strong> setelah
                        pendaftaran. Tombol <strong>"Jadwalkan"</strong> bisa langsung ditekan.</p>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($pendaftarans as $p)
                @php
                    $tanggalDaftar = $p->first_registered_at ?? $p->created_at;
                    $tglDaftar = \Carbon\Carbon::parse($tanggalDaftar);
                    $minimalUjian = $tglDaftar->copy()->addDays(7);
                    $hariKe = (int) $tglDaftar->diffInDays(\Carbon\Carbon::now());
                    $bisaDijadwalkan = \Carbon\Carbon::now()->gte($minimalUjian);
                    $hariTersisa = $bisaDijadwalkan ? 0 : (int) ceil(\Carbon\Carbon::now()->diffInDays($minimalUjian));
                @endphp
                <div
                    class="bg-white rounded-2xl shadow-sm border {{ $bisaDijadwalkan ? 'border-green-200' : 'border-gray-100' }} p-6 hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ $p->id }}"
                                class="mt-1 w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Disetujui
                                        Kajur</span>
                                    <span
                                        class="px-2 py-1 {{ $bisaDijadwalkan ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }} rounded-full text-xs">{{ $bisaDijadwalkan ? 'Siap' : 'Tunggu' }}</span>
                                </div>

                                <h3 class="font-semibold text-gray-900">{{ Str::limit($p->judul_penelitian, 60) }}</h3>

                                <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <p class="text-xs text-gray-500">Mahasiswa</p>
                                        <p class="font-medium">{{ $p->mahasiswa->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Daftar</p>
                                        <p class="font-medium">{{ $tglDaftar->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $hariKe }} hari yang lalu</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Minimal Ujian</p>
                                        <p class="font-medium {{ $bisaDijadwalkan ? 'text-green-600' : 'text-amber-600' }}">
                                            {{ $minimalUjian->format('d M Y') }}</p>
                                        @if(!$bisaDijadwalkan)
                                        <p class="text-xs text-amber-500">{{ $hariTersisa }} hari lagi</p>@endif
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Penguji</p>
                                        @php $pengujiList = \App\Models\UjianPenguji::where('pendaftaran_id', $p->id)->with('dosen.kepakaran')->get(); @endphp
                                        @if($pengujiList->count() > 0)
                                            @foreach($pengujiList as $penguji)
                                                <div class="flex items-center gap-1">
                                                    <span class="text-xs font-medium">{{ $penguji->dosen->name ?? '-' }}</span>
                                                    <span
                                                        class="text-xs text-gray-400">({{ str_replace('_', ' ', $penguji->peran) }})</span>
                                                    @if($penguji->is_overload)<span class="text-xs text-red-500">⚠️</span>@endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>

                                @if($p->bidangKeahlians->count() > 0)
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($p->bidangKeahlians as $bk)
                                            <span
                                                class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded-full text-xs">{{ $bk->nama_bidang }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="md:text-right md:flex-shrink-0">
                            <button wire:click="openScheduleModal({{ $p->id }})"
                                class="px-5 py-2.5 bg-cyan-700 text-white rounded-xl hover:bg-cyan-800 text-sm font-medium whitespace-nowrap shadow-sm shadow-cyan-200 transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Jadwalkan
                            </button>
                            <p class="text-xs text-gray-400 mt-1">Min. {{ $minimalUjian->format('d M') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Semua Sudah Dijadwalkan!</h3>
                    <p class="text-gray-500">Tidak ada pendaftaran yang menunggu jadwal.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- ============= TAB: TERJADWAL ============= --}}
    @if($tab === 'scheduled')
        <div class="space-y-4">
            @forelse($pendaftarans as $p)
                @php
                    $si = ($p->sesi ?? 1) - 1;
                    $jm = $jamMulaiOptions[$si] ?? '08:00';
                    $js = $jamSelesaiOptions[$si] ?? '10:00';
                    $ls = $labelSesiOptions[$si] ?? 'Sesi ' . ($p->sesi ?? 1);
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-blue-200 p-6">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', $p->jenis_ujian)) }}</span>
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Terjadwal</span>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ Str::limit($p->judul_penelitian, 60) }}</h3>
                            <div class="mt-3 bg-blue-50 rounded-xl p-4">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-blue-600">Tanggal</p>
                                        <p class="font-bold text-blue-800 text-sm">
                                            {{ $p->tanggal_ujian ? \Carbon\Carbon::parse($p->tanggal_ujian)->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600">Sesi</p>
                                        <p class="font-bold text-blue-800 text-sm">{{ $ls }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600">Waktu</p>
                                        <p class="font-bold text-blue-800 text-sm">{{ $jm }} - {{ $js }} WIB</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600">Ruangan</p>
                                        <p class="font-bold text-blue-800 text-sm">{{ $p->ruangan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-500">{{ $p->mahasiswa->name }} ({{ $p->mahasiswa->nim }})</p>
                        </div>
                        <div class="flex flex-col gap-2 md:flex-shrink-0">
                            <button wire:click="rescheduleUjian({{ $p->id }})"
                                class="px-4 py-2 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 text-sm font-medium whitespace-nowrap">Ubah</button>
                            <button wire:click="cancelJadwal({{ $p->id }})" wire:confirm="Batalkan jadwal?"
                                class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-xl hover:bg-red-100 text-sm font-medium whitespace-nowrap">Batalkan</button>
                            <button wire:click="markAsCompleted({{ $p->id }})" wire:confirm="Tandai selesai?"
                                class="px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-xl hover:bg-green-100 text-sm font-medium whitespace-nowrap">Selesai</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Belum ada
                    ujian dijadwalkan.</div>
            @endforelse
        </div>
    @endif

    {{-- ============= TAB: SELESAI ============= --}}
    @if($tab === 'completed')
        <div class="space-y-3">
            @forelse($pendaftarans as $p)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 opacity-80">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                        <span
                            class="text-xs text-gray-500">{{ $p->completed_at ? \Carbon\Carbon::parse($p->completed_at)->format('d M Y') : '-' }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-700">{{ Str::limit($p->judul_penelitian, 60) }}</h3>
                    <p class="text-sm text-gray-500">{{ $p->mahasiswa->name }} ({{ $p->mahasiswa->nim }})</p>
                    @if($p->nilai_total)
                        <p class="text-sm mt-1">Nilai: <span class="font-bold text-purple-700">{{ $p->nilai_total }}
                    ({{ $p->grade }})</span></p>@endif
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center text-gray-500">Belum ada
                    ujian selesai.</div>
            @endforelse
        </div>
    @endif

    <div class="mt-4">{{ $pendaftarans->links() }}</div>

    {{-- ============= MODAL: JADWALKAN SATU ============= --}}
    @if($showScheduleModal && $selectedPendaftaran)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="closeScheduleModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-xl w-full p-6 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $selectedPendaftaran->tanggal_ujian ? 'Ubah Jadwal' : 'Atur Jadwal Ujian' }}</h3>
                        <button wire:click="closeScheduleModal" class="text-gray-400 hover:text-gray-600"><svg
                                class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>

                    <div class="mb-4 p-4 bg-gray-50 rounded-xl space-y-2">
                        <span
                            class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">{{ ucwords(str_replace('_', ' ', $selectedPendaftaran->jenis_ujian)) }}</span>
                        <p class="text-sm font-medium">{{ $selectedPendaftaran->mahasiswa->name }}
                            ({{ $selectedPendaftaran->mahasiswa->nim }})</p>
                        <p class="text-sm text-gray-600">{{ Str::limit($selectedPendaftaran->judul_penelitian, 70) }}</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div><span class="text-gray-500">Tgl Daftar:</span> <span
                                    class="font-medium">{{ $tanggalDaftar ? $tanggalDaftar->format('d M Y') : '-' }}</span>
                            </div>
                            <div><span class="text-gray-500">Min. Ujian:</span> <span
                                    class="font-medium text-orange-600">{{ $tanggal_minimal ? $tanggal_minimal->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        @php $pengujiList = \App\Models\UjianPenguji::where('pendaftaran_id', $selectedPendaftaran->id)->with('dosen.kepakaran')->get(); @endphp
                        @if($pengujiList->count() > 0)
                            <div class="pt-2 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Penguji:</p>
                                @foreach($pengujiList as $penguji)
                                    <p class="text-xs"><span class="font-medium">{{ $penguji->dosen->name ?? '-' }}</span>
                                        ({{ str_replace('_', ' ', $penguji->peran) }}) @if($penguji->dosen->kepakaran)-
                                        {{ $penguji->dosen->kepakaran->nama_kepakaran }}@endif @if($penguji->is_overload)<span
                                        class="text-red-500">⚠️</span>@endif</p>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm">
                        <p class="font-medium text-amber-800">Aturan Penjadwalan</p>
                        <p class="text-xs text-amber-700 mt-1">• Minimal <strong>7 hari</strong> setelah pendaftaran →
                            <strong>{{ $tanggal_minimal ? $tanggal_minimal->format('d M Y') : '-' }}</strong><br>• Jam
                            mengikuti sesi yang dipilih<br>• Sesi diatur di <a
                                href="{{ route('panitia.penjadwalan.setting-waktu') }}"
                                class="text-cyan-600 underline">Pengaturan Waktu</a></p>
                    </div>

                    <div class="flex gap-3 mb-4">
                        <button type="button" wire:click="autoGenerateJadwal"
                            class="px-4 py-2 {{ $scheduleMode === 'auto' ? 'bg-cyan-700 text-white' : 'bg-gray-100 text-gray-700' }} rounded-xl text-sm font-medium transition">🎲
                            Auto Random</button>
                        <span class="text-xs text-gray-400 self-center">atau atur manual</span>
                    </div>

                    <form wire:submit="scheduleUjian">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ujian <span
                                        class="text-red-500">*</span></label>
                                <input type="date" wire:model="tanggal_ujian"
                                    min="{{ $tanggal_minimal ? $tanggal_minimal->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('tanggal_ujian') border-red-500 @enderror">
                                <p class="text-xs text-gray-400 mt-1">Minimal:
                                    {{ $tanggal_minimal ? $tanggal_minimal->format('d M Y') : '-' }}</p>
                                @error('tanggal_ujian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- SESI (Radio Card) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sesi Ujian <span
                                        class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($labelSesiOptions as $index => $label)
                                        <label
                                            class="flex items-center p-3 border-2 rounded-xl cursor-pointer transition {{ ($sesi == ($index + 1)) ? 'border-cyan-500 bg-cyan-50' : 'border-gray-200 hover:border-gray-300' }}"
                                            wire:click="$set('sesi', {{ $index + 1 }})">
                                            <input type="radio" wire:model="sesi" value="{{ $index + 1 }}" class="hidden">
                                            <div class="flex-1">
                                                <p
                                                    class="text-sm font-medium {{ ($sesi == ($index + 1)) ? 'text-cyan-700' : 'text-gray-900' }}">
                                                    {{ $label }}</p>
                                                <p
                                                    class="text-xs {{ ($sesi == ($index + 1)) ? 'text-cyan-600' : 'text-gray-500' }}">
                                                    {{ $jamMulaiOptions[$index] ?? '-' }} -
                                                    {{ $jamSelesaiOptions[$index] ?? '-' }} WIB</p>
                                            </div>
                                            @if($sesi == ($index + 1))
                                                <svg class="w-5 h-5 text-cyan-600 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-400 mt-1"><a
                                        href="{{ route('panitia.penjadwalan.setting-waktu') }}"
                                        class="text-cyan-600 hover:underline">+ Kelola Waktu & Sesi</a></p>
                                @error('sesi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="ruangan"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('ruangan') border-red-500 @enderror">
                                    <option value="">Pilih Ruangan</option>
                                    @foreach($ruanganOptions as $r)<option value="{{ $r }}">{{ $r }}</option>@endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1"><a
                                        href="{{ route('panitia.penjadwalan.setting-ruangan') }}"
                                        class="text-cyan-600 hover:underline">+ Kelola Ruangan</a></p>
                                @error('ruangan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            @if($tanggal_ujian && $sesi)
                                @php $si = $sesi - 1; @endphp
                                <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-200">
                                    <p class="text-sm font-semibold text-cyan-800">📋 Preview Jadwal</p>
                                    <div class="grid grid-cols-3 gap-3 mt-2 text-sm">
                                        <div>
                                            <p class="text-xs text-cyan-600">Tanggal</p>
                                            <p class="font-bold text-cyan-800">
                                                {{ \Carbon\Carbon::parse($tanggal_ujian)->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-cyan-600">Sesi</p>
                                            <p class="font-bold text-cyan-800">{{ $labelSesiOptions[$si] ?? 'Sesi ' . $sesi }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-cyan-600">Waktu</p>
                                            <p class="font-bold text-cyan-800">{{ $jamMulaiOptions[$si] ?? '-' }} -
                                                {{ $jamSelesaiOptions[$si] ?? '-' }} WIB</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea wire:model="catatan" rows="2"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500"
                                    placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <button type="button" wire:click="closeScheduleModal"
                                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-cyan-700 text-white rounded-xl hover:bg-cyan-800 font-medium shadow-sm shadow-cyan-200">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $selectedPendaftaran->tanggal_ujian ? 'Perbarui Jadwal' : 'Simpan Jadwal' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ============= MODAL: JADWALKAN BATCH ============= --}}
    @if($showBatchModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" wire:click="closeBatchModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Jadwalkan {{ count($selectedIds) }} Ujian</h3>
                        <button wire:click="closeBatchModal" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg></button>
                    </div>
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm">
                        <p class="font-medium text-amber-800">Batasan Tanggal</p>
                        <p class="text-xs text-amber-700">Minimal <strong>7 hari</strong> setelah pendaftaran paling awal →
                            <strong>{{ $tanggal_minimal ? $tanggal_minimal->format('d M Y') : '-' }}</strong></p>
                    </div>
                    <form wire:submit="scheduleBatchUjian">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ujian <span
                                        class="text-red-500">*</span></label>
                                <input type="date" wire:model="tanggal_ujian"
                                    min="{{ $tanggal_minimal ? $tanggal_minimal->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 @error('tanggal_ujian') border-red-500 @enderror">
                                @error('tanggal_ujian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sesi <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="sesi"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                                    @foreach($labelSesiOptions as $index => $label)
                                        <option value="{{ $index + 1 }}">{{ $label }} ({{ $jamMulaiOptions[$index] ?? '-' }} -
                                            {{ $jamSelesaiOptions[$index] ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="ruangan"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500">
                                    <option value="">Pilih Ruangan</option>
                                    @foreach($ruanganOptions as $r)<option value="{{ $r }}">{{ $r }}</option>@endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-4">
                            <button type="button" wire:click="closeBatchModal"
                                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium">Batal</button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-cyan-700 text-white rounded-xl hover:bg-cyan-800 font-medium">Jadwalkan
                                {{ count($selectedIds) }} Ujian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
