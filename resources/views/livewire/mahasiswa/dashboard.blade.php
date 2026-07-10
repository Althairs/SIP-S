<div>
    @section('title', 'Dashboard Mahasiswa')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-blue-100 mt-1">NIM: {{ Auth::user()->nim }} | {{ Auth::user()->jurusan?->nama_jurusan }}</p>
            </div>
        </div>
    </div>

    <!-- Next Action -->
    @php
        $nextActionTheme = [
            'blue' => 'bg-blue-50 border-blue-100 text-blue-700 hover:bg-blue-100',
            'amber' => 'bg-amber-50 border-amber-100 text-amber-700 hover:bg-amber-100',
            'yellow' => 'bg-yellow-50 border-yellow-100 text-yellow-700 hover:bg-yellow-100',
            'green' => 'bg-green-50 border-green-100 text-green-700 hover:bg-green-100',
            'purple' => 'bg-purple-50 border-purple-100 text-purple-700 hover:bg-purple-100',
            'gray' => 'bg-gray-50 border-gray-100 text-gray-700 hover:bg-gray-100',
        ][$nextAction['color'] ?? 'gray'];
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-blue-700">Langkah Berikutnya</p>
                <h2 class="text-xl font-semibold text-gray-900 mt-1">{{ $nextAction['title'] }}</h2>
                <p class="text-sm text-gray-500 mt-2">{{ $nextAction['description'] }}</p>
                @if($pendaftaranAktif)
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="px-2 py-1 bg-{{ $pendaftaranAktif->statusColor }}-100 text-{{ $pendaftaranAktif->statusColor }}-800 rounded-full text-xs font-medium">{{ $pendaftaranAktif->statusLabel }}</span>
                        <span class="text-xs text-gray-400">{{ ucwords(str_replace('_', ' ', $pendaftaranAktif->jenis_ujian)) }}</span>
                    </div>
                @endif
            </div>
            <a href="{{ $nextAction['url'] }}" class="px-5 py-2.5 rounded-xl border text-sm font-medium transition {{ $nextActionTheme }}">
                {{ $nextAction['label'] }}
            </a>
        </div>
    </div>

    {{-- ============= REMINDER URGENT ============= --}}
    @if($reminderTerdekat)
    <div class="bg-gradient-to-r from-red-50 to-amber-50 border border-red-200 rounded-2xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs font-medium inline-flex items-center gap-1">
                        @if($reminderTerdekat->prioritas === 'tinggi')
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        Prioritas Tinggi
                        @else
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Reminder
                        @endif
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 mt-2">{{ $reminderTerdekat->judul }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $reminderTerdekat->pesan }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-xs text-gray-500 inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $reminderTerdekat->tanggal_tampil->format('d M Y') }}
                        </span>
                        @if($reminderTerdekat->tanggal_kadaluarsa)
                        <span class="text-xs text-red-500 inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Deadline: {{ $reminderTerdekat->tanggal_kadaluarsa->format('d M Y') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <button wire:click="markReminderRead({{ $reminderTerdekat->id }})" class="text-sm text-gray-400 hover:text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Tahapan -->
    <div class="grid md:grid-cols-3 gap-6 mb-6">
        @foreach($tahapan as $key => $tahap)
        <div class="bg-white rounded-2xl p-6 shadow-sm border {{ $tahap['completed'] ? 'border-green-200' : 'border-gray-100' }}">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 {{ $tahap['completed'] ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                    @if($tahap['completed'])
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @else
                        <span class="text-gray-400 text-xs font-bold">{{ $loop->iteration }}</span>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $tahap['label'] }}</p>
                    <p class="text-xs {{ $tahap['completed'] ? 'text-green-600' : 'text-gray-400' }}">{{ $tahap['completed'] ? 'Selesai' : 'Belum' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- ============= DAFTAR REMINDER ============= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Reminder & Notifikasi
                </h2>
                @if($reminders->where('is_read', false)->count() > 0)
                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">{{ $reminders->where('is_read', false)->count() }} baru</span>
                @endif
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                @if($reminders->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p>Tidak ada reminder</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($reminders as $reminder)
                    <div class="p-3 rounded-xl transition {{ $reminder->is_read ? 'bg-gray-50 opacity-60' : 'bg-blue-50 border border-blue-100' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium inline-flex items-center gap-1 {{ $reminder->prioritas === 'tinggi' ? 'bg-red-100 text-red-800' : ($reminder->prioritas === 'sedang' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600') }}">
                                        @if($reminder->prioritas === 'tinggi')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                        @endif
                                        {{ ucfirst($reminder->prioritas) }}
                                    </span>
                                    @if(!$reminder->is_read)
                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-gray-900 mt-1">{{ $reminder->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($reminder->pesan, 100) }}</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                    <span>{{ $reminder->tanggal_tampil->format('d M Y') }}</span>
                                    @if($reminder->tanggal_kadaluarsa)
                                    <span>Deadline: {{ $reminder->tanggal_kadaluarsa->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if(!$reminder->is_read)
                            <button wire:click="markReminderRead({{ $reminder->id }})" class="text-xs text-blue-600 hover:text-blue-800 flex-shrink-0 ml-2">Tandai Dibaca</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ============= DAFTAR PENDAFTARAN ============= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Pendaftaran</h2>
                <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded-xl hover:bg-blue-800 text-sm font-medium">Daftar Ujian</a>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                <div class="space-y-3">
                    @forelse($pendaftarans->take(5) as $p)
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs">{{ $p->statusLabel }}</span>
                            <span class="text-xs text-gray-500">{{ $p->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ Str::limit($p->judul_penelitian, 50) }}</p>
                    </div>
                    @empty
                    <p class="text-center py-8 text-gray-500">Belum ada pendaftaran</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
