<div>
    @section('title', 'Jadwal Ujian')
    @section('page-title', 'Jadwal Ujian')

    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-6" x-data="{ tab: 'upcoming' }">
        <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium border-b-2 transition">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Akan Datang
            @if($upcomingUjian->count() > 0)
            <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $upcomingUjian->count() }}</span>
            @endif
        </button>
        <button @click="tab = 'history'" :class="tab === 'history' ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 text-sm font-medium border-b-2 transition">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat
        </button>
    </div>

    <!-- Upcoming Ujian -->
    <div x-data="{ tab: 'upcoming' }" x-show="tab === 'upcoming'">
        @if($upcomingUjian->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Ujian Mendatang</h3>
            <p class="text-gray-500">Anda belum memiliki jadwal ujian yang akan datang.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($upcomingUjian as $ujian)
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 hover:shadow-md transition cursor-pointer" wire:click="showDetailUjian({{ $ujian->id }})">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <!-- Tanggal Box -->
                            <div class="bg-blue-600 text-white rounded-xl p-3 text-center min-w-[70px]">
                                <p class="text-xs font-medium opacity-80">{{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('M') }}</p>
                                <p class="text-2xl font-bold">{{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d') }}</p>
                                <p class="text-xs opacity-80">{{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('Y') }}</p>
                            </div>

                            <div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ ucwords(str_replace('_', ' ', $ujian->jenis_ujian)) }}
                                </span>
                                <h3 class="font-semibold text-gray-900 mt-2 text-lg">{{ Str::limit($ujian->judul_penelitian, 60) }}</h3>

                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ Carbon\Carbon::parse($ujian->tanggal_ujian)->format('H:i') }} WIB
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $ujian->ruangan ?? 'Ruangan belum ditentukan' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Sesi {{ $ujian->sesi ?? 'Belum ditentukan' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $ujian->dosens->where('peran', 'pembimbing_1')->first()?->dosen?->name ?? 'Belum ditentukan' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
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
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($riwayatUjian as $ujian)
                        <tr class="hover:bg-gray-50 cursor-pointer" wire:click="showDetailUjian({{ $ujian->id }})">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $ujian->tanggal_ujian ? Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                    {{ ucwords(str_replace('_', ' ', $ujian->jenis_ujian)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                                {{ Str::limit($ujian->judul_penelitian, 40) }}
                            </td>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Detail Modal -->
    @if($showDetail && $selectedUjian)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" wire:click="closeDetail"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Detail Ujian</h3>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="px-3 py-1 bg-{{ $selectedUjian->statusColor }}-100 text-{{ $selectedUjian->statusColor }}-800 rounded-full text-sm font-medium">
                        {{ $selectedUjian->statusLabel }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Jenis Ujian</p>
                            <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $selectedUjian->jenis_ujian)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bidang Keahlian</p>
                            <p class="font-medium text-gray-900">{{ $selectedUjian->bidang_keahlian }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Ujian</p>
                            <p class="font-medium text-gray-900">{{ $selectedUjian->tanggal_ujian ? Carbon\Carbon::parse($selectedUjian->tanggal_ujian)->format('d M Y, H:i') : 'Belum ditentukan' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ruangan</p>
                            <p class="font-medium text-gray-900">{{ $selectedUjian->ruangan ?? 'Belum ditentukan' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sesi</p>
                            <p class="font-medium text-gray-900">{{ $selectedUjian->sesi ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Judul Penelitian</p>
                        <p class="font-medium text-gray-900 text-lg">{{ $selectedUjian->judul_penelitian }}</p>
                    </div>

                    <!-- Dosen -->
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Dosen Terlibat</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($selectedUjian->dosens as $dosenPengampu)
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $dosenPengampu->peran)) }}</p>
                                <p class="font-medium text-gray-900">{{ $dosenPengampu->dosen->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">NIP: {{ $dosenPengampu->dosen->nip ?? '-' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Nilai (jika sudah ada) -->
                    @if($selectedUjian->nilai_total)
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-sm text-green-600">Nilai Total</p>
                                <p class="text-2xl font-bold text-green-800">{{ $selectedUjian->nilai_total }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-green-600">Grade</p>
                                <p class="text-2xl font-bold text-green-800">{{ $selectedUjian->grade }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-green-600">Status</p>
                                <p class="text-2xl font-bold text-green-800">Lulus</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
