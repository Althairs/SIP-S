<div>
    @section('title', 'Kuota Saya')
    @section('page-title', 'Kuota & Beban Kerja')

    <!-- Kuota Cards -->
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-4">Kuota Pembimbing</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-blue-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Kuota</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $kuota?->kuota_pembimbing ?? 5 }}</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Terpakai</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $kuota?->terpakai_pembimbing ?? 0 }}</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Sisa</p>
                    <p class="text-2xl font-bold text-green-700">{{ $kuota?->sisa_pembimbing ?? 5 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold mb-4">Kuota Penguji</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-purple-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Kuota</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $kuota?->kuota_penguji ?? 10 }}</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Terpakai</p>
                    <p class="text-2xl font-bold text-amber-700">{{ $kuota?->terpakai_penguji ?? 0 }}</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3">
                    <p class="text-xs text-gray-500">Sisa</p>
                    <p class="text-2xl font-bold text-green-700">{{ $kuota?->sisa_penguji ?? 10 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail -->
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold mb-4">Daftar Bimbingan ({{ $pembimbingList->count() }})</h3>
            <div class="space-y-2 max-h-80 overflow-y-auto">
                @forelse($pembimbingList as $pb)
                <div class="p-2 bg-gray-50 rounded-lg text-sm">
                    <span class="text-xs text-gray-500">{{ str_replace('_', ' ', $pb->peran) }}:</span>
                    <span class="font-medium ml-1">{{ $pb->pendaftaran->mahasiswa->name ?? '-' }}</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Belum ada bimbingan</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold mb-4">Daftar Menguji ({{ $pengujiList->count() }})</h3>
            <div class="space-y-2 max-h-80 overflow-y-auto">
                @forelse($pengujiList as $pj)
                <div class="p-2 bg-gray-50 rounded-lg text-sm">
                    <span class="text-xs text-gray-500">{{ str_replace('_', ' ', $pj->peran) }}:</span>
                    <span class="font-medium ml-1">{{ $pj->pendaftaran->mahasiswa->name ?? '-' }}</span>
                    @if($pj->is_overload)<span class="text-red-500 text-xs inline-flex" title="Kuota overload"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg></span>@endif
                </div>
                @empty
                <p class="text-gray-400 text-sm">Belum ada jadwal menguji</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
