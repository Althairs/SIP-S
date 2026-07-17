<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start rtl:justify-end">
        <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar" aria-controls="top-bar-sidebar" type="button" class="sm:hidden text-gray-700 bg-transparent box-border border border-transparent hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium leading-5 rounded-lg text-sm p-2 focus:outline-none">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
            </svg>
        </button>
        <a href="{{ route('sekjur.dashboard') }}" class="flex ms-2 md:me-24">
          <img src="{{ asset('images/logo_ung.png') }}" class="h-8" />
          <span class="self-center text-lg font-semibold whitespace-nowrap text-gray-800 ms-3">SIP-<span class="text-violet-700">S</span> <span class="text-xs text-gray-500">| Sekjur</span></span>
        </a>
      </div>
      <div class="flex items-center">
          <div class="flex items-center ms-3">
            <div>
              <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=7c3aed&color=fff" alt="user photo">
              </button>
            </div>
            <div class="z-50 hidden bg-white border border-gray-200 rounded-lg shadow-lg w-44" id="dropdown-user">
              <div class="px-4 py-3 border-b border-gray-200" role="none">
                <p class="text-sm font-medium text-gray-900" role="none">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-500 truncate" role="none">{{ Auth::user()->email }}</p>
                <span class="inline-block mt-1 px-2 py-0.5 bg-violet-100 text-violet-800 rounded-full text-xs font-medium capitalize">Sekjur</span>
              </div>
              <ul class="p-2 text-sm text-gray-700 font-medium" role="none">
                <li><a href="{{ route('sekjur.dashboard') }}" class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded" role="menuitem">Dashboard</a></li>
                <li><a href="{{ route('sekjur.profile') }}" class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded" role="menuitem">Profile</a></li>
                <li><a href="{{ route('sekjur.settings') }}" class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded" role="menuitem">Settings</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center w-full p-2 hover:bg-gray-100 hover:text-gray-900 rounded text-left" role="menuitem">Sign out</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-14" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
      {{-- <a href="{{ route('sekjur.dashboard') }}" class="flex items-center ps-2.5 mb-5">
         <div class="w-8 h-8 bg-gradient-to-br from-violet-600 to-violet-700 rounded-lg flex items-center justify-center">
           <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
           </svg>
         </div>
         <span class="self-center text-lg text-gray-800 font-semibold whitespace-nowrap ms-3">SIP-<span class="text-violet-700">S</span></span>
      </a> --}}
      <ul class="space-y-2 font-medium">
         <!-- Dashboard -->
         <li>
            <a href="{{ route('sekjur.dashboard') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-violet-50 hover:text-violet-700 group {{ request()->routeIs('sekjur.dashboard') ? 'bg-violet-50 text-violet-700' : '' }}">
               <svg class="w-5 h-5 transition duration-75 group-hover:text-violet-700 {{ request()->routeIs('sekjur.dashboard') ? 'text-violet-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/>
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>

         <!-- Divider: Data Master -->
         <li class="pt-4">
            <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Master (View)</span>
         </li>

         <!-- Data Dosen (View Only) -->
         <li>
            <a href="{{ route('sekjur.data-master.dosen') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 group {{ request()->routeIs('sekjur.data-master.dosen') ? 'bg-blue-50 text-blue-700' : '' }}">
               <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-blue-700 {{ request()->routeIs('sekjur.data-master.dosen') ? 'text-blue-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Data Dosen</span>
               <span class="text-xs text-gray-400">View</span>
            </a>
         </li>

         <!-- Data Mahasiswa (View Only) -->
         <li>
            <a href="{{ route('sekjur.data-master.mahasiswa') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-700 group {{ request()->routeIs('sekjur.data-master.mahasiswa') ? 'bg-purple-50 text-purple-700' : '' }}">
               <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-purple-700 {{ request()->routeIs('sekjur.data-master.mahasiswa') ? 'text-purple-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Data Mahasiswa</span>
               <span class="text-xs text-gray-400">View</span>
            </a>
         </li>

         <!-- Data Panitia (View Only) -->
         <li>
            <a href="{{ route('sekjur.data-master.panitia') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-amber-50 hover:text-amber-700 group {{ request()->routeIs('sekjur.data-master.panitia') ? 'bg-amber-50 text-amber-700' : '' }}">
               <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-amber-700 {{ request()->routeIs('sekjur.data-master.panitia') ? 'text-amber-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Data Panitia</span>
               <span class="text-xs text-gray-400">View</span>
            </a>
         </li>

         <!-- Penguji (KHUSUS SEKRJUR) -->
         <li>
            <a href="{{ route('sekjur.data-master.penguji') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 group {{ request()->routeIs('sekjur.data-master.penguji') ? 'bg-orange-50 text-orange-700' : '' }}">
               <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-orange-700 {{ request()->routeIs('sekjur.data-master.penguji') ? 'text-orange-700' : 'text-gray-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 2v8.586a2 2 0 01-.586 1.414L5.172 16.242A1 1 0 005.93 18h12.14a1 1 0 00.758-1.758L14.586 11A2 2 0 0114 9.586V2"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Penguji</span>
               {{-- <span class="bg-orange-100 border border-orange-200 text-orange-800 text-xs font-medium px-1.5 py-0.5 rounded-sm">Baru</span> --}}
            </a>
         </li>

         <!-- Divider: Verifikasi -->
         <li class="pt-4">
            <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Verifikasi (View)</span>
         </li>

         <li>
            <a href="{{ route('sekjur.verifikasi.seminar-proposal') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 group {{ request()->routeIs('sekjur.verifikasi.seminar-proposal') ? 'bg-green-50 text-green-700' : '' }}">
               <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Seminar Proposal</span>
               <span class="text-xs text-gray-400">View</span>
            </a>
         </li>

         <li>
            <a href="{{ route('sekjur.verifikasi.seminar-hasil') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-yellow-50 hover:text-yellow-700 group">
               <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v14M9 5v14M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Seminar Hasil</span>
               <span class="text-xs text-gray-400">View</span>
            </a>
         </li>

         <li>
            <a href="{{ route('sekjur.verifikasi.sidang-skripsi') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-700 group">
               <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Sidang Skripsi</span>
               <span class="text-xs text-gray-400">View</span>
            </a>
         </li>

         <!-- Divider: Pengaturan -->
         <li class="pt-4">
            <span class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
         </li>

         <li>
            <a href="{{ route('sekjur.profile') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-violet-50 hover:text-violet-700 group">
               <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
            </a>
         </li>

         <li>
            <a href="{{ route('sekjur.settings') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-violet-50 hover:text-violet-700 group">
               <svg class="shrink-0 w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
            </a>
         </li>

         <li>
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="flex items-center w-full px-2 py-1.5 text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 group">
                  <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-red-700 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Sign Out</span>
               </button>
            </form>
         </li>
      </ul>
   </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('[data-drawer-toggle="top-bar-sidebar"]');
    const sidebar = document.getElementById('top-bar-sidebar');

    sidebarToggle?.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.toggle('sm:translate-x-0');
    });
});
</script>
