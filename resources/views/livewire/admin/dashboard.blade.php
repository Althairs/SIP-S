<div>
    @section('title', 'Dashboard Admin')
    @section('page-title', 'Dashboard')

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Jurusan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalJurusan }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Prodi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProdi }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Dosen Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDosen }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    {{-- <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $totalKajur }}</p>
            <p class="text-sm text-green-600">Kajur</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $totalSekjur }}</p>
            <p class="text-sm text-green-600">Sekjur</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-amber-700">{{ $totalDosen }}</p>
            <p class="text-sm text-amber-600">Dosen</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $totalMahasiswa }}</p>
            <p class="text-sm text-green-600">Mahasiswa</p>
        </div>
    </div> --}}

    <!-- Recent Data -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Jurusan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Jurusan Terbaru</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentJurusans as $jurusan)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $jurusan->nama_jurusan }}</p>
                            <p class="text-sm text-gray-500">{{ $jurusan->kode_jurusan }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $jurusan->prodis_count }} Prodi</span>
                            <span class="text-sm text-gray-500">•</span>
                            <span class="text-sm text-gray-500">{{ $jurusan->users_count }} Users</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada jurusan</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Users Terbaru</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                    <div class="flex items-center space-x-4">
                        <img class="w-10 h-10 rounded-full bg-green-200" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=16a34a&color=fff" alt="">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium capitalize">
                                {{ $user->getRoleNames()->first() ?? 'No Role' }}
                            </span>
                            @if($user->jurusan)
                            <p class="text-xs text-gray-500 mt-1">{{ $user->jurusan->nama_jurusan }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada users</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
