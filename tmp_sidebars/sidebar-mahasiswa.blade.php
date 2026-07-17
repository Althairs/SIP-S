<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar"
                    aria-controls="top-bar-sidebar" type="button"
                    class="sm:hidden text-gray-700 bg-transparent box-border border border-transparent hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium leading-5 rounded-lg text-sm p-2 focus:outline-none">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M5 7h14M5 12h14M5 17h10" />
                    </svg>
                </button>
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex ms-2 md:me-24">
                    {{-- <div
                        class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <span class="self-center text-lg font-semibold whitespace-nowrap text-gray-800 ms-3">SIP-<span
                            class="text-blue-700">S</span> <span class="text-xs text-gray-500">| Mahasiswa</span></span>
                    --}}
                    <img src="{{ asset('images/logo_ung.png') }}" class="h-8" />
                    <span class="self-center text-lg font-semibold whitespace-nowrap text-gray-800 ms-3">SIP-<span
                            class="text-emerald-700">S</span> <span class="text-xs text-gray-500">| Mahasiswa</span></span>
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button"
                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300"
                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff"
                                alt="user photo">
                        </button>
                    </div>
                    <div class="z-50 hidden bg-white border border-gray-200 rounded-lg shadow-lg w-44"
                        id="dropdown-user">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            <span
                                class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium capitalize">Mahasiswa</span>
                        </div>
                        <ul class="p-2 text-sm text-gray-700 font-medium">
                            <li><a href="{{ route('mahasiswa.dashboard') }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded">Dashboard</a>
                            </li>
                            <li><a href="{{ route('mahasiswa.profile') }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded">Profile</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded text-left">Sign
                                        out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside id="top-bar-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-14"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
        {{-- <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center ps-2.5 mb-5">
            <div
                class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <span class="self-center text-lg text-gray-800 font-semibold whitespace-nowrap ms-3">SIP-<span
                    class="text-blue-700">S</span></span>
        </a> --}}
        <ul class="space-y-2 font-medium">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('mahasiswa.dashboard') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 group {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="w-5 h-5 transition duration-75 group-hover:text-blue-700 {{ request()->routeIs('mahasiswa.dashboard') ? 'text-blue-700' : 'text-gray-500' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            <!-- Divider: Pendaftaran -->
            <li class="pt-4">
                <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendaftaran</span>
            </li>

            <!-- Pendaftaran Ujian -->
            <li>
                <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('mahasiswa.pendaftaran.*') ? 'bg-green-50 text-green-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('mahasiswa.pendaftaran.*') ? 'text-green-700' : 'text-gray-500' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Pendaftaran</span>
                    <span
                        class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-green-800 bg-green-100 border border-green-200 rounded-full">{{ \App\Models\Pendaftaran::where('mahasiswa_id', Auth::id())->count() }}</span>
                </a>
            </li>

            <!-- Divider: Informasi -->
            <li class="pt-4">
                <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Informasi</span>
            </li>

            <!-- Jadwal Ujian -->
            <li>
                <a href="{{ route('mahasiswa.jadwal') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 group {{ request()->routeIs('mahasiswa.jadwal') ? 'bg-amber-50 text-amber-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-amber-700 {{ request()->routeIs('mahasiswa.jadwal') ? 'text-amber-700' : 'text-gray-500' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Jadwal Ujian</span>
                </a>
            </li>

            <!-- Revisi -->
            <li>
                <a href="{{ route('mahasiswa.revisi') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 group {{ request()->routeIs('mahasiswa.revisi') ? 'bg-amber-50 text-amber-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-amber-700 {{ request()->routeIs('mahasiswa.revisi') ? 'text-amber-700' : 'text-gray-500' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Revisi</span>
                    @php $pendingRevisiMhs = \App\Models\Revisi::whereHas('pendaftaran', fn($q) => $q->where('mahasiswa_id', Auth::id()))->whereIn('status', ['pending', 'diperiksa'])->count(); @endphp
                    @if($pendingRevisiMhs > 0)<span
                    class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-xs font-medium text-amber-800 bg-amber-100 border border-amber-200 rounded-full">{{ $pendingRevisiMhs }}</span>@endif
                </a>
            </li>

            <!-- Nilai -->
            <li>
                <a href="{{ route('mahasiswa.nilai') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-700 group {{ request()->routeIs('mahasiswa.nilai') ? 'bg-purple-50 text-purple-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-purple-700 {{ request()->routeIs('mahasiswa.nilai') ? 'text-purple-700' : 'text-gray-500' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Nilai</span>
                </a>
            </li>

            <!-- Divider: Pengaturan -->
            <li class="pt-4">
                <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
            </li>

            <!-- Profile -->
            <li>
                <a href="{{ route('mahasiswa.profile') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 group {{ request()->routeIs('mahasiswa.profile') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-blue-700 {{ request()->routeIs('mahasiswa.profile') ? 'text-blue-700' : 'text-gray-500' }}"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                </a>
            </li>

            <!-- Logout -->
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-2 py-1.5 text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-red-700 text-red-500"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sign Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.querySelector('[data-drawer-toggle="top-bar-sidebar"]');
        const sidebar = document.getElementById('top-bar-sidebar');

        sidebarToggle?.addEventListener('click', function () {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('sm:translate-x-0');
        });
    });
</script>
