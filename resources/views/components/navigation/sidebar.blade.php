@php
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
        $user->hasRole('super_admin') => 'green',
        $user->hasRole('kajur') => 'green',
        $user->hasRole('sekjur') => 'green',
        $user->hasRole('mahasiswa') => 'green',
        $user->hasRole('dosen') => 'green',
        $user->hasRole('panitia_verifikasi') => 'green',
        $user->hasRole('panitia_penjadwalan') => 'green',
        $user->hasRole('panitia_administrasi') => 'gray',
        default => 'green',
    };

    $avatarBg = match(true) {
        $user->hasRole('super_admin') => '16a34a',
        $user->hasRole('kajur') => '16a34a',
        $user->hasRole('sekjur') => '16a34a',
        $user->hasRole('mahasiswa') => '16a34a',
        $user->hasRole('dosen') => '16a34a',
        $user->hasRole('panitia_verifikasi') => '16a34a',
        $user->hasRole('panitia_penjadwalan') => '16a34a',
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
            {{-- SUPER ADMIN MENU --}}
            {{-- ======================== --}}
            @role('super_admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('admin.dashboard') ? 'text-green-700' : 'text-gray-500' }}"
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

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Manajemen Data</span>
                </li>

                <li>
                    <a href="{{ route('admin.jurusans.index') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('admin.jurusans.*') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('admin.jurusans.*') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Jurusan</span>
                        <span
                            class="bg-green-100 border border-green-200 text-green-800 text-xs font-medium px-1.5 py-0.5 rounded-sm">{{ \App\Models\Jurusan::count() }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.prodis.index') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('admin.prodis.*') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('admin.prodis.*') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 13h3.439a.991.991 0 0 1 .908.6 3.978 3.978 0 0 0 7.306 0 .99.99 0 0 1 .908-.6H20M4 13v6a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-6M4 13l2-9h12l2 9M9 7h6m-7 3h8" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Program Studi</span>
                        <span
                            class="bg-green-100 border border-green-200 text-green-800 text-xs font-medium px-1.5 py-0.5 rounded-sm">{{ \App\Models\Prodi::count() }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 group {{ request()->routeIs('admin.users.*') ? 'bg-amber-50 text-amber-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-amber-700 {{ request()->routeIs('admin.users.*') ? 'text-amber-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                        <span
                            class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-amber-800 bg-amber-100 border border-amber-200 rounded-full">{{ \App\Models\User::count() }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.roles.index') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 group {{ request()->routeIs('admin.roles.*') ? 'bg-rose-50 text-rose-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-rose-700 {{ request()->routeIs('admin.roles.*') ? 'text-rose-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2Zm10-10V7a4 4 0 00-8 0v4h8Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Role & Akses</span>
                        <span
                            class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-rose-800 bg-rose-100 border border-rose-200 rounded-full">{{ \Spatie\Permission\Models\Role::count() }}</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Notifikasi</span>
                </li>

                <li>
                    <a href="{{ route('admin.notification-settings') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('admin.notification-settings.*') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.notification-settings.*') ? 'text-green-600' : 'text-gray-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Notifikasi Whatsapp</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
                </li>

                <li>
                    <a href="{{ route('admin.profile') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('admin.profile') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('admin.profile') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.settings') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('admin.settings') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('admin.settings') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- KAJUR MENU --}}
            {{-- ======================== --}}
            @role('kajur')
                <li>
                    <a href="{{ route('kajur.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 group {{ request()->routeIs('kajur.dashboard') ? 'bg-emerald-50 text-emerald-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-emerald-700 {{ request()->routeIs('kajur.dashboard') ? 'text-emerald-700' : 'text-gray-500' }}"
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

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Master</span>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.dosen') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.data-master.dosen') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('kajur.data-master.dosen') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data Dosen</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.mahasiswa') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.data-master.mahasiswa') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('kajur.data-master.mahasiswa') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data Mahasiswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.panitia') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 group {{ request()->routeIs('kajur.data-master.panitia') ? 'bg-amber-50 text-amber-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-amber-700 {{ request()->routeIs('kajur.data-master.panitia') ? 'text-amber-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data Panitia</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.kuota-dosen') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 group {{ request()->routeIs('kajur.data-master.kuota-dosen') ? 'bg-rose-50 text-rose-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-rose-700 {{ request()->routeIs('kajur.data-master.kuota-dosen') ? 'text-rose-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0-4-4m4 4-4 4m0 6H4m0 0 4 4m-4-4 4-4" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Kuota Dosen</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.atur-atribut-dosen') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.data-master.atur-atribut-dosen') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('kajur.data-master.atur-atribut-dosen') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Atribut Dosen</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.bidang-keahlian') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.data-master.bidang-keahlian') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('kajur.data-master.bidang-keahlian') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Bidang Keahlian</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.kepakaran') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.data-master.kepakaran') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('kajur.data-master.kepakaran') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Kepakaran</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.data-master.pengaturan-reminder') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.data-master.pengaturan-reminder') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Pengaturan Reminder</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Verifikasi</span>
                </li>

                <li>
                    <a href="{{ route('kajur.verifikasi.seminar-proposal') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('kajur.verifikasi.seminar-proposal') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('kajur.verifikasi.seminar-proposal') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Seminar Proposal</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.verifikasi.seminar-hasil') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-yellow-50 hover:text-yellow-700 group {{ request()->routeIs('kajur.verifikasi.seminar-hasil') ? 'bg-yellow-50 text-yellow-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-yellow-700 {{ request()->routeIs('kajur.verifikasi.seminar-hasil') ? 'text-yellow-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Seminar Hasil</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.verifikasi.sidang-skripsi') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-700 group {{ request()->routeIs('kajur.verifikasi.sidang-skripsi') ? 'bg-red-50 text-red-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-red-700 {{ request()->routeIs('kajur.verifikasi.sidang-skripsi') ? 'text-red-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sidang Skripsi</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
                </li>

                <li>
                    <a href="{{ route('kajur.profile') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 group {{ request()->routeIs('kajur.profile') ? 'bg-emerald-50 text-emerald-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-emerald-700 {{ request()->routeIs('kajur.profile') ? 'text-emerald-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kajur.settings') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 group {{ request()->routeIs('kajur.settings') ? 'bg-emerald-50 text-emerald-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-emerald-700 {{ request()->routeIs('kajur.settings') ? 'text-emerald-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- SEKJUR MENU --}}
            {{-- ======================== --}}
            @role('sekjur')
                <li>
                    <a href="{{ route('sekjur.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('sekjur.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('sekjur.dashboard') ? 'text-green-700' : 'text-gray-500' }}"
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

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Master (View)</span>
                </li>

                <li>
                    <a href="{{ route('sekjur.data-master.dosen') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('sekjur.data-master.dosen') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('sekjur.data-master.dosen') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data Dosen</span>
                        <span class="text-xs text-gray-400">View</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sekjur.data-master.mahasiswa') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('sekjur.data-master.mahasiswa') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('sekjur.data-master.mahasiswa') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data Mahasiswa</span>
                        <span class="text-xs text-gray-400">View</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sekjur.data-master.panitia') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 group {{ request()->routeIs('sekjur.data-master.panitia') ? 'bg-amber-50 text-amber-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-amber-700 {{ request()->routeIs('sekjur.data-master.panitia') ? 'text-amber-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Data Panitia</span>
                        <span class="text-xs text-gray-400">View</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sekjur.data-master.penguji') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('sekjur.data-master.penguji') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('sekjur.data-master.penguji') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 2v8.586a2 2 0 01-.586 1.414L5.172 16.242A1 1 0 005.93 18h12.14a1 1 0 00.758-1.758L14.586 11A2 2 0 0114 9.586V2" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Penguji</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Verifikasi (View)</span>
                </li>

                <li>
                    <a href="{{ route('sekjur.verifikasi.seminar-proposal') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('sekjur.verifikasi.seminar-proposal') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Seminar Proposal</span>
                        <span class="text-xs text-gray-400">View</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sekjur.verifikasi.seminar-hasil') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-yellow-50 hover:text-yellow-700 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Seminar Hasil</span>
                        <span class="text-xs text-gray-400">View</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sekjur.verifikasi.sidang-skripsi') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-700 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sidang Skripsi</span>
                        <span class="text-xs text-gray-400">View</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
                </li>

                <li>
                    <a href="{{ route('sekjur.profile') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('sekjur.settings') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- MAHASISWA MENU --}}
            {{-- ======================== --}}
            @role('mahasiswa')
                <li>
                    <a href="{{ route('mahasiswa.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 group {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-emerald-50 text-emerald-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-emerald-700 {{ request()->routeIs('mahasiswa.dashboard') ? 'text-emerald-700' : 'text-gray-500' }}"
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

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendaftaran</span>
                </li>

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

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Informasi</span>
                </li>

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

                <li>
                    <a href="{{ route('mahasiswa.nilai') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('mahasiswa.nilai') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs('mahasiswa.nilai') ? 'text-green-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Nilai</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
                </li>

                <li>
                    <a href="{{ route('mahasiswa.profile') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 group {{ request()->routeIs('mahasiswa.profile') ? 'bg-emerald-50 text-emerald-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-emerald-700 {{ request()->routeIs('mahasiswa.profile') ? 'text-emerald-700' : 'text-gray-500' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- DOSEN MENU --}}
            {{-- ======================== --}}
            @role('dosen')
                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Tugas Saya</span>
                </li>

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

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Informasi</span>
                </li>

                <li>
                    <a href="{{ route('dosen.jadwal') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('dosen.jadwal') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="flex-1 ms-3">Jadwal Menguji</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dosen.kuota') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('dosen.kuota') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="flex-1 ms-3">Kuota Saya</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Akun</span>
                </li>

                <li>
                    <a href="{{ route('dosen.profile') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3">Profile</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- PANITIA VERIFIKASI MENU --}}
            {{-- ======================== --}}
            @role('panitia_verifikasi')
                <li>
                    <a href="{{ route('panitia.verifikasi.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('panitia.verifikasi.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Verifikasi</span>
                </li>

                <li>
                    <a href="{{ route('panitia.verifikasi.berkas') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('panitia.verifikasi.berkas') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="flex-1 ms-3">Verifikasi Berkas</span>
                        <span
                            class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-green-800 bg-green-100 rounded-full">!</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Akun</span>
                </li>

                <li>
                    <a href="{{ route('panitia.verifikasi.profile') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3">Profile</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- PANITIA PENJADWALAN MENU --}}
            {{-- ======================== --}}
            @role('panitia_penjadwalan')
                <li>
                    <a href="{{ route('panitia.penjadwalan.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('panitia.penjadwalan.dashboard') ? 'bg-green-50 text-green-700' : '' }}">
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

                <li>
                    <a href="{{ route('panitia.penjadwalan.jadwal') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('panitia.penjadwalan.jadwal') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="flex-1 ms-3">Jadwal Ujian</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Pengaturan</span>
                </li>

                <li>
                    <a href="{{ route('panitia.penjadwalan.setting-waktu') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('panitia.penjadwalan.setting-waktu') ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="flex-1 ms-3">Waktu & Sesi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('panitia.penjadwalan.setting-ruangan') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 {{ request()->routeIs('panitia.penjadwalan.setting-ruangan') ? 'bg-green-50 text-green-700' : '' }}">
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
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700">
                        <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3">Profile</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- PANITIA ADMINISTRASI MENU --}}
            {{-- ======================== --}}
            @role('panitia_administrasi')
                <li>
                    <a href="{{ route('panitia.administrasi.dashboard') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <li class="pt-4">
                    <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Administrasi</span>
                </li>

                <li>
                    <a href="{{ route('panitia.administrasi.nilai-berkas') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-700 {{ request()->routeIs('panitia.administrasi.nilai-berkas') ? 'bg-gray-100 text-gray-900 font-medium' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Nilai Berkas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('panitia.administrasi.laporan') }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-700 {{ request()->routeIs('panitia.administrasi.laporan*') ? 'bg-gray-100 text-gray-900 font-medium' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Laporan</span>
                    </a>
                </li>
            @endrole

            {{-- ======================== --}}
            {{-- FALLBACK: USER WITHOUT ROLE --}}
            {{-- ======================== --}}
            @hasanyrole('super_admin|kajur|sekjur|mahasiswa|dosen|panitia_verifikasi|panitia_penjadwalan|panitia_administrasi')
            @else
                <li>
                    <a href="{{ route($homeRoute) }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs($homeRoute) ? 'bg-green-50 text-green-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-green-700 {{ request()->routeIs($homeRoute) ? 'text-green-700' : 'text-gray-500' }}"
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

                <li>
                    <a href="{{ route($profileRoute) }}"
                        class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group">
                        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-green-700 text-gray-500"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
                    </a>
                </li>
            @endhasanyrole

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
