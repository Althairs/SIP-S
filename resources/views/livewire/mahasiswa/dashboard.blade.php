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

    <!-- Reminder -->
    @if($reminder)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
        <h3 class="font-semibold text-amber-900">Reminder Ujian</h3>
        <p class="text-amber-700">{{ $reminder->jenis_ujian_label }} - {{ $reminder->tanggal_ujian?->format('d M Y, H:i') }}</p>
    </div>
    @endif

    <!-- Daftar Pendaftaran -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Riwayat Pendaftaran</h2>
            <a href="{{ route('mahasiswa.pendaftaran.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition text-sm font-medium">
                Daftar Ujian
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pendaftarans as $p)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ str_replace('_', ' ', ucwords($p->jenis_ujian)) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 truncate max-w-xs">{{ Str::limit($p->judul_penelitian, 40) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-{{ $p->statusColor }}-100 text-{{ $p->statusColor }}-800 rounded-full text-xs">{{ $p->statusLabel }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada pendaftaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
