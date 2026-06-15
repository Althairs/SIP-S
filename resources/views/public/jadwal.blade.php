<x-layouts.app>
    @section('title', 'Jadwal')

    <section class="pt-24 pb-16 bg-gradient-to-br from-green-50 via-white to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Jadwal <span class="text-green-700">Ujian</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Informasi lengkap jadwal seminar proposal, seminar hasil, dan sidang skripsi
                </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8 max-w-2xl mx-auto">
                <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-blue-100">
                    <p class="text-2xl font-bold text-blue-700">{{ $totalSeminarProposal }}</p>
                    <p class="text-xs text-blue-600">Seminar Proposal</p>
                </div>
                <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-amber-100">
                    <p class="text-2xl font-bold text-amber-700">{{ $totalSeminarHasil }}</p>
                    <p class="text-xs text-amber-600">Seminar Hasil</p>
                </div>
                <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-purple-100">
                    <p class="text-2xl font-bold text-purple-700">{{ $totalSidang }}</p>
                    <p class="text-xs text-purple-600">Sidang Skripsi</p>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm border border-green-100">
                <form method="GET" action="{{ route('jadwal') }}">
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kegiatan</label>
                            <select name="jenis" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Semua Kegiatan</option>
                                <option value="seminar_proposal" {{ $jenisFilter === 'seminar_proposal' ? 'selected' : '' }}>Seminar Proposal</option>
                                <option value="seminar_hasil" {{ $jenisFilter === 'seminar_hasil' ? 'selected' : '' }}>Seminar Hasil</option>
                                <option value="sidang_skripsi" {{ $jenisFilter === 'sidang_skripsi' ? 'selected' : '' }}>Sidang Skripsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Nama, NIM, atau judul..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-2.5 bg-green-700 text-white rounded-lg hover:bg-green-800 transition font-medium">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Jadwal List -->
            @if($jadwals->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal</h3>
                <p class="text-gray-500">Tidak ada jadwal ujian yang ditemukan dengan filter yang dipilih.</p>
            </div>
            @else
            <div class="space-y-4">
                @foreach($jadwals as $jadwal)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition p-6">
                    <div class="flex flex-col md:flex-row md:items-start gap-4">
                        <!-- Tanggal -->
                        <div class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-xl p-3 text-center min-w-[80px]">
                            <p class="text-xs font-medium opacity-80">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('M') }}</p>
                            <p class="text-2xl font-bold">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('d') }}</p>
                            <p class="text-xs opacity-80">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('Y') }}</p>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span class="px-2 py-1 bg-{{ $jadwal->jenis_ujian === 'seminar_proposal' ? 'blue' : ($jadwal->jenis_ujian === 'seminar_hasil' ? 'amber' : 'purple') }}-100 text-{{ $jadwal->jenis_ujian === 'seminar_proposal' ? 'blue' : ($jadwal->jenis_ujian === 'seminar_hasil' ? 'amber' : 'purple') }}-800 rounded-full text-xs font-medium">
                                    {{ ucwords(str_replace('_', ' ', $jadwal->jenis_ujian)) }}
                                </span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                    {{ $jadwal->status === 'dijadwalkan' ? 'Akan Datang' : 'Selesai' }}
                                </span>
                            </div>

                            <h3 class="font-semibold text-gray-900 text-lg">{{ $jadwal->judul_penelitian }}</h3>

                            <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Mahasiswa</p>
                                    <p class="font-medium">{{ $jadwal->mahasiswa->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $jadwal->mahasiswa->nim }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Waktu</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('H:i') }} WIB</p>
                                    @if($jadwal->sesi)
                                    <p class="text-xs text-gray-400">Sesi {{ $jadwal->sesi }}</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Ruangan</p>
                                    <p class="font-medium">{{ $jadwal->ruangan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Jurusan</p>
                                    <p class="font-medium text-sm">{{ $jadwal->jurusan?->nama_jurusan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $jadwals->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </section>
</x-layouts.app>
