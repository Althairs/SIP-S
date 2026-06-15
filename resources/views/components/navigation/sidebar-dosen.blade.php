<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar" type="button"
                    class="sm:hidden text-gray-700 bg-transparent p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
                    </svg>
                </button>
                <a href="{{ route('dosen.dashboard') }}" class="flex ms-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="self-center text-lg font-semibold text-gray-800 ms-3">SIP-<span
                            class="text-indigo-700">S</span> <span class="text-xs text-gray-500">| Dosen</span></span>
                </a>
            </div>
            <div class="flex items-center">
                <img class="w-8 h-8 rounded-full"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff"
                    alt="">
            </div>
        </div>
    </div>
</nav>

<aside id="top-bar-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-14">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
        <ul class="space-y-2 font-medium">
            {{-- <li>
                <a href="{{ route('dosen.dashboard') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 {{ request()->routeIs('dosen.dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li> --}}

            <li class="pt-4"><span class="px-2 text-xs font-semibold text-gray-400 uppercase">Tugas Saya</span></li>

            {{-- Revisi --}}
            <li>
                <a href="{{ route('dosen.revisi.index') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 {{ request()->routeIs('dosen.revisi.*') ? 'bg-amber-50 text-amber-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="flex-1 ms-3">Revisi</span>
                    @php $pendingRevisi = \App\Models\Revisi::byDosen(Auth::id())->pending()->count(); @endphp
                    @if($pendingRevisi > 0)<span
                    class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs">{{ $pendingRevisi }}</span>@endif
                </a>
            </li>

            {{-- Nilai --}}
            <li>
                <a href="{{ route('dosen.nilai.index') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('dosen.nilai.*') ? 'bg-green-50 text-green-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="flex-1 ms-3">Nilai</span>
                </a>
            </li>

            <li class="pt-4"><span class="px-2 text-xs font-semibold text-gray-400 uppercase">Informasi</span></li>

            {{-- Jadwal Menguji --}}
            <li>
                <a href="{{ route('dosen.jadwal') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 {{ request()->routeIs('dosen.jadwal') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="flex-1 ms-3">Jadwal Menguji</span>
                </a>
            </li>

            {{-- Kuota Saya --}}
            <li>
                <a href="{{ route('dosen.kuota') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-700 {{ request()->routeIs('dosen.kuota') ? 'bg-purple-50 text-purple-700' : '' }}">
                    <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="flex-1 ms-3">Kuota Saya</span>
                </a>
            </li>

            <li class="pt-4"><span class="px-2 text-xs font-semibold text-gray-400 uppercase">Akun</span></li>

            <li>
                <a href="{{ route('dosen.profile') }}"
                    class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700">Profile</a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                        class="flex items-center w-full px-2 py-1.5 text-red-600 rounded-lg hover:bg-red-50">Sign
                        Out</button></form>
            </li>
        </ul>
    </div>
</aside>
<script>document.addEventListener('DOMContentLoaded', function () { document.querySelector('[data-drawer-toggle="top-bar-sidebar"]')?.addEventListener('click', function () { document.getElementById('top-bar-sidebar').classList.toggle('-translate-x-full') }) });</script>
