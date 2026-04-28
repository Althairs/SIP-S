<div>
    @section('title', 'Dashboard Verifikasi')

    <div class="bg-gradient-to-r from-orange-600 to-orange-800 rounded-2xl p-6 mb-6 text-white">
        <h2 class="text-2xl font-bold">Panel Verifikasi Berkas</h2>
        <p class="text-orange-100 mt-1">{{ Auth::user()->jurusan?->nama_jurusan }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-amber-100">
            <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
            <p class="text-3xl font-bold text-amber-700">{{ $totalPending }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-green-100">
            <p class="text-sm text-gray-500">Disetujui</p>
            <p class="text-3xl font-bold text-green-700">{{ $totalDiverifikasi }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-red-100">
            <p class="text-sm text-gray-500">Ditolak</p>
            <p class="text-3xl font-bold text-red-700">{{ $totalDitolak }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <a href="{{ route('panitia.verifikasi.berkas') }}" class="px-5 py-2.5 bg-orange-700 text-white rounded-xl hover:bg-orange-800 font-medium">
            Mulai Verifikasi Berkas
        </a>
    </div>
</div>
