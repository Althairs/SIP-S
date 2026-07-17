<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar" type="button" class="sm:hidden text-gray-700 bg-transparent p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/></svg>
        </button>
        <a href="{{ route('panitia.administrasi.dashboard') }}" class="flex ms-2">
          <img src="{{ asset('images/logo_ung.png') }}" class="h-8" />
          <span class="self-center text-lg font-semibold text-gray-800 ms-3">SIP-<span class="text-gray-700">S</span> <span class="text-xs text-gray-500">| Administrasi</span></span>
        </a>
      </div>
    </div>
  </div>
</nav>

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-16">
   <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('panitia.administrasi.dashboard') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-700">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/></svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li class="pt-4">
            <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Administrasi</span>
         </li>
         <li>
            <a href="{{ route('panitia.administrasi.nilai-berkas') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-700 {{ request()->routeIs('panitia.administrasi.nilai-berkas') ? 'bg-gray-100 text-gray-900 font-medium' : '' }}">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
               <span class="ms-3">Nilai Berkas</span>
            </a>
         </li>
         <li>
            <a href="{{ route('panitia.administrasi.laporan') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-700 {{ request()->routeIs('panitia.administrasi.laporan*') ? 'bg-gray-100 text-gray-900 font-medium' : '' }}">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
               <span class="ms-3">Laporan</span>
            </a>
         </li>
         <li>
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="flex items-center w-full px-2 py-1.5 text-red-600 rounded-lg hover:bg-red-50">Sign Out</button>
            </form>
         </li>
      </ul>
   </div>
</aside>
