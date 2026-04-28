<div>
    @section('title', 'Dashboard Sekjur')
    @section('page-title', 'Dashboard Sekretaris Jurusan')

    <div class="bg-gradient-to-r from-violet-600 to-violet-800 rounded-2xl p-6 mb-6 text-white">
        <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}</h2>
        <p class="text-violet-100 mt-1">Sekretaris Jurusan {{ Auth::user()->jurusan?->nama_jurusan ?? 'Belum ditentukan' }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Menu Utama</h2>
        <p class="text-gray-500">Anda memiliki akses view data master dan fitur penambahan penguji.</p>
    </div>
</div>
