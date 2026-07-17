<div>
    @section('title', 'Dashboard Sekjur')
    @section('page-title', 'Dashboard Sekretaris Jurusan')

    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-6 mb-6 text-white">
        <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}</h2>
        <p class="text-green-100 mt-1">Sekretaris Jurusan {{ Auth::user()->jurusan?->nama_jurusan ?? 'Belum ditentukan' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ikhtisar Pendaftaran</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">Menunggu Verifikasi</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">Disetujui Panitia</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['disetujui_panitia'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-xs text-green-600">Menunggu Penguji</div>
                    <div class="text-2xl font-bold text-green-700">{{ $stats['menunggu_penguji'] ?? 0 }}</div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">Dijadwalkan</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $stats['dijadwalkan'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">Selesai</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $stats['selesai'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">Perlu Revisi</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $stats['revisi'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <p class="text-gray-500 mb-4">Tautan cepat untuk tugas Sekjur.</p>
            <div class="flex flex-col gap-2">
                <a href="{{ route('sekjur.data-master.penguji') }}" class="text-left px-4 py-2 bg-green-600 text-white rounded-md">Kelola Penguji</a>
                <a href="{{ route('sekjur.data-master.penguji') }}" class="text-left px-4 py-2 border border-green-600 text-green-600 rounded-md">Lihat Penguji</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-md font-semibold mb-3">Jadwal Mendatang (7 hari)</h4>
            @if($upcoming && $upcoming->count())
                <ul class="space-y-3">
                    @foreach($upcoming as $p)
                        <li class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold">{{ $p->mahasiswa?->name ?? 'Mahasiswa' }}</div>
                                <div class="text-sm text-gray-500">{{ $p->jenis_ujian }} — {{ $p->judul_penelitian }}</div>
                            </div>
                            <div class="text-sm text-gray-600">{{ optional($p->tanggal_ujian)->format('d M H:i') }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Tidak ada jadwal dalam 7 hari ke depan.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-md font-semibold mb-3">Pendaftaran Terbaru</h4>
            @if($recent && $recent->count())
                <ul class="space-y-3">
                    @foreach($recent as $p)
                        <li class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold">{{ $p->mahasiswa?->name ?? 'Mahasiswa' }}</div>
                                <div class="text-sm text-gray-500">{{ $p->jenis_ujian }} — {{ Str::limit($p->judul_penelitian, 60) }}</div>
                            </div>
                            <div class="text-sm text-gray-600">{{ $p->created_at->diffForHumans() }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Belum ada pendaftaran.</p>
            @endif
        </div>
    </div>
</div>
