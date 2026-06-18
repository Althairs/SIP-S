<div>
    @section('title', 'Dashboard Kajur')
    @section('page-title', 'Dashboard Ketua Jurusan')

    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-emerald-100 mt-1">Ketua Jurusan {{ $jurusanNama }}</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-16 h-16 text-emerald-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Dosen</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDosen }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMahasiswa }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Panitia</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPanitia }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards (Interactive) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan & Aksi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <a href="{{ route('kajur.data-master.dosen') }}" class="block bg-blue-50 p-4 rounded-xl hover:shadow-md transition">
                <p class="text-xs text-gray-500">Data Dosen</p>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xl font-semibold text-blue-800">{{ $totalDosen }}</p>
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4"/></svg>
                </div>
            </a>

            <a href="{{ route('kajur.data-master.mahasiswa') }}" class="block bg-purple-50 p-4 rounded-xl hover:shadow-md transition">
                <p class="text-xs text-gray-500">Mahasiswa</p>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xl font-semibold text-purple-800">{{ $totalMahasiswa }}</p>
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292"/></svg>
                </div>
            </a>

            <a href="{{ route('kajur.data-master.panitia') }}" class="block bg-amber-50 p-4 rounded-xl hover:shadow-md transition">
                <p class="text-xs text-gray-500">Panitia</p>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xl font-semibold text-amber-800">{{ $totalPanitia }}</p>
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                </div>
            </a>

            <a href="{{ route('kajur.verifikasi.seminar-proposal') }}" class="block bg-green-50 p-4 rounded-xl hover:shadow-md transition">
                <p class="text-xs text-gray-500">Verifikasi</p>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xl font-semibold text-green-800">Lihat</p>
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                </div>
            </a>
        </div>
    </div>
</div>
