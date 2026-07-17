@php
    use App\Services\PermissionService;

    $user = Auth::user();
    $role = $user->getRoleNames()->first();

    $homeRoute = match(true) {
        $user->hasRole('super_admin') => 'admin.dashboard',
        $user->hasRole('kajur') => 'kajur.dashboard',
        $user->hasRole('sekjur') => 'sekjur.dashboard',
        $user->hasRole('mahasiswa') => 'mahasiswa.dashboard',
        $user->hasRole('dosen') => 'dosen.dashboard',
        $user->hasRole('panitia_verifikasi') => 'panitia.verifikasi.dashboard',
        $user->hasRole('panitia_penjadwalan') => 'panitia.penjadwalan.dashboard',
        $user->hasRole('panitia_administrasi') => 'panitia.administrasi.dashboard',
        default => 'login',
    };

    $profileRoute = match(true) {
        $user->hasRole('super_admin') => 'admin.profile',
        $user->hasRole('kajur') => 'kajur.profile',
        $user->hasRole('sekjur') => 'sekjur.profile',
        $user->hasRole('mahasiswa') => 'mahasiswa.profile',
        $user->hasRole('dosen') => 'dosen.profile',
        $user->hasRole('panitia_verifikasi') => 'panitia.verifikasi.profile',
        $user->hasRole('panitia_penjadwalan') => 'panitia.penjadwalan.profile',
        $user->hasRole('panitia_administrasi') => 'panitia.administrasi.profile',
        default => 'login',
    };

    $settingsRoute = match(true) {
        $user->hasRole('super_admin') => 'admin.settings',
        $user->hasRole('kajur') => 'kajur.settings',
        $user->hasRole('sekjur') => 'sekjur.settings',
        default => null,
    };

    $accent = match(true) {
        $user->hasRole('panitia_administrasi') => 'gray',
        default => 'green',
    };

    $avatarBg = match(true) {
        $user->hasRole('panitia_administrasi') => '6b7280',
        default => '16a34a',
    };

    $roleLabel = match(true) {
        $user->hasRole('kajur') => 'Kajur',
        $user->hasRole('sekjur') => 'Sekjur',
        $user->hasRole('mahasiswa') => 'Mahasiswa',
        $user->hasRole('dosen') => 'Dosen',
        $user->hasRole('panitia_verifikasi') => 'Verifikasi',
        $user->hasRole('panitia_penjadwalan') => 'Penjadwalan',
        $user->hasRole('panitia_administrasi') => 'Administrasi',
        default => '',
    };

    // ── Resolve route for a menu item ──
    function resolveMenuRoute($item, $homeRoute, $profileRoute, $settingsRoute) {
        $route = $item['resolved_route'] ?? null;
        if ($route) return $route;

        // Fallback untuk Dashboard / Profile / Settings yang route-nya dinamis per role
        return match($item['label']) {
            'Dashboard' => $homeRoute,
            'Profile' => $profileRoute,
            'Settings' => $settingsRoute,
            default => null,
        };
    }

    function isRouteActive($routeName) {
        if (!$routeName) return false;
        return request()->routeIs($routeName) || request()->routeIs($routeName . '.*');
    }

    function menuItemClasses($routeName, $accent) {
        $active = isRouteActive($routeName);
        $base = 'flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group';
        if ($active) {
            $base .= ' bg-green-50 text-green-700';
        }
        return $base;
    }

    $menuGroups = PermissionService::getMenusForCurrentUser();
@endphp

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
                <a href="{{ route($homeRoute) }}" class="flex ms-2 md:me-24">
                    <img src="{{ asset('images/logo_ung.png') }}" class="h-8" />
                    <span class="self-center text-lg font-semibold whitespace-nowrap text-gray-800 ms-3">SIP-<span
                            class="text-{{ $accent }}-700">S</span>
                        @if($roleLabel)
                            <span class="text-xs text-gray-500">| {{ $roleLabel }}</span>
                        @endif
                    </span>
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
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $avatarBg }}&color=fff"
                                alt="user photo">
                        </button>
                    </div>
                    <div class="z-50 hidden bg-white border border-gray-200 rounded-lg shadow-lg w-44"
                        id="dropdown-user">
                        <div class="px-4 py-3 border-b border-gray-200" role="none">
                            <p class="text-sm font-medium text-gray-900" role="none">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 truncate" role="none">{{ $user->email }}</p>
                            <span
                                class="inline-block mt-1 px-2 py-0.5 bg-{{ $accent }}-100 text-{{ $accent }}-800 rounded-full text-xs font-medium capitalize">{{ $role }}</span>
                        </div>
                        <ul class="p-2 text-sm text-gray-700 font-medium" role="none">
                            <li><a href="{{ route($homeRoute) }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded"
                                    role="menuitem">Dashboard</a></li>
                            <li><a href="{{ route($profileRoute) }}"
                                    class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded"
                                    role="menuitem">Profile</a></li>
                            @if($settingsRoute)
                                <li><a href="{{ route($settingsRoute) }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded"
                                        role="menuitem">Settings</a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded text-left"
                                        role="menuitem">Sign out</button>
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
        <ul class="space-y-2 font-medium">

            {{-- ======================== --}}
            {{-- SIDEBAR DINAMIS BERBASIS PERMISSION --}}
            {{-- ======================== --}}

            {{-- Dashboard (always shown for authenticated) --}}
            {{-- @if($homeRoute && Route::has($homeRoute))
            <li>
                <a href="{{ route($homeRoute) }}"
                    class="{{ menuItemClasses($homeRoute, $accent) }}">
                    <svg class="w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs(rtrim($homeRoute, '.dashboard') . '.dashboard') ? 'text-green-700' : 'text-gray-500' }}"
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
            @endif --}}

            {{-- Menu groups from PermissionService --}}
            @foreach($menuGroups as $groupName => $items)
                @if(count($items) === 0) @continue @endif

                {{-- Group header --}}
                @if($groupName !== '_ungrouped')
                <li class="pt-2">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $groupName }}</span>
                </li>
                @endif

                {{-- Menu items --}}
                @foreach($items as $item)
                @php
                    $routeName = resolveMenuRoute($item, $homeRoute, $profileRoute, $settingsRoute);
                    if (!$routeName) continue;
                @endphp
                <li>
                    <a href="{{ route($routeName) }}"
                        class="{{ menuItemClasses($routeName, $accent) }}">
                        {{-- Icon --}}
                        @include('components.navigation.sidebar-icons', ['icon' => $item['icon'] ?? 'default', 'routeName' => $routeName, 'accent' => $accent])

                        <span class="flex-1 ms-3 whitespace-nowrap">{{ $item['label'] }}</span>

                        {{-- Badge count untuk beberapa item spesifik --}}
                        @if($item['label'] === 'Jurusan')
                            <span class="bg-green-100 border border-green-200 text-green-800 text-xs font-medium px-1.5 py-0.5 rounded-sm">{{ \App\Models\Jurusan::count() }}</span>
                        @elseif($item['label'] === 'Program Studi')
                            <span class="bg-green-100 border border-green-200 text-green-800 text-xs font-medium px-1.5 py-0.5 rounded-sm">{{ \App\Models\Prodi::count() }}</span>
                        @elseif($item['label'] === 'Users')
                            <span class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-amber-800 bg-amber-100 border border-amber-200 rounded-full">{{ \App\Models\User::count() }}</span>
                        @elseif($item['label'] === 'Role & Akses')
                            <span class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-rose-800 bg-rose-100 border border-rose-200 rounded-full">{{ \Spatie\Permission\Models\Role::count() }}</span>
                        @elseif($item['label'] === 'Pendaftaran')
                            <span class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-green-800 bg-green-100 border border-green-200 rounded-full">{{ \App\Models\Pendaftaran::where('mahasiswa_id', Auth::id())->count() }}</span>
                        @elseif($item['label'] === 'Revisi' && $user->hasRole('mahasiswa'))
                            @php $pendingRevisiMhs = \App\Models\Revisi::whereHas('pendaftaran', fn($q) => $q->where('mahasiswa_id', Auth::id()))->whereIn('status', ['pending', 'diperiksa'])->count(); @endphp
                            @if($pendingRevisiMhs > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-xs font-medium text-amber-800 bg-amber-100 border border-amber-200 rounded-full">{{ $pendingRevisiMhs }}</span>
                            @endif
                        @elseif($item['label'] === 'Revisi' && $user->hasRole('dosen'))
                            @php $pendingRevisi = \App\Models\Revisi::byDosen(Auth::id())->pending()->count(); @endphp
                            @if($pendingRevisi > 0)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs">{{ $pendingRevisi }}</span>
                            @endif
                        @elseif($item['label'] === 'Verifikasi Berkas')
                            <span class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">!</span>
                        @endif
                    </a>
                </li>
                @endforeach
            @endforeach

            {{-- ======================== --}}
            {{-- SIGN OUT (ALL ROLES) --}}
            {{-- ======================== --}}
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
