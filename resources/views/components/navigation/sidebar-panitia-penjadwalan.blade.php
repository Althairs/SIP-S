<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar" type="button"
                    class="sm:hidden text-gray-700 bg-transparent p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
                    </svg>
                </button>
                <a href="{{ route('panitia.penjadwalan.dashboard') }}" class="flex ms-2">
                    <img src="{{ asset('images/logo_ung.png') }}" class="h-8" />
                    <span class="self-center text-lg font-semibold text-gray-800 ms-3">SIP-<span
                            class="text-cyan-700">S</span> <span class="text-xs text-gray-500">|
                            Penjadwalan</span></span>
                </a>
            </div>
            <div class="flex items-center">
                <img class="w-8 h-8 rounded-full"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0891b2&color=fff"
                    alt="">
            </div>
        </div>
    </div>
</nav>

<aside id="top-bar-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-16">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('panitia.penjadwalan.dashboard') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-cyan-50 hover:text-cyan-700 {{ request()->routeIs('panitia.penjadwalan.dashboard') ? 'bg-cyan-50 text-cyan-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li class="pt-4">
                <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Penjadwalan</span>
            </li>
            {{-- Di bagian Penjadwalan, tambahkan setelah menu Jadwal Ujian --}}
            <li>
                <a href="{{ route('panitia.penjadwalan.jadwal') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-cyan-50 hover:text-cyan-700 {{ request()->routeIs('panitia.penjadwalan.jadwal') ? 'bg-cyan-50 text-cyan-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="flex-1 ms-3">Jadwal Ujian</span>
                </a>
            </li>

            {{-- TAMBAHAN: Pengaturan --}}
            <li class="pt-4">
                <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Pengaturan</span>
            </li>
            <li>
                <a href="{{ route('panitia.penjadwalan.setting-waktu') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-teal-50 hover:text-teal-700 {{ request()->routeIs('panitia.penjadwalan.setting-waktu') ? 'bg-teal-50 text-teal-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1 ms-3">Waktu & Sesi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('panitia.penjadwalan.setting-ruangan') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-teal-50 hover:text-teal-700 {{ request()->routeIs('panitia.penjadwalan.setting-ruangan') ? 'bg-teal-50 text-teal-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="flex-1 ms-3">Ruangan</span>
                </a>
            </li>
            <li class="pt-4">
                <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Akun</span>
            </li>
            <li>
                <a href="{{ route('panitia.penjadwalan.profile') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-cyan-50 hover:text-cyan-700">Profile</a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-2 py-1.5 text-red-600 rounded-lg hover:bg-red-50">Sign
                        Out</button>
                </form>
            </li>
        </ul>
    </div>
</aside>
