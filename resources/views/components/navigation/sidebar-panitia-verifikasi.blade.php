<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button data-drawer-target="top-bar-sidebar" data-drawer-toggle="top-bar-sidebar" type="button" class="sm:hidden text-gray-700 bg-transparent p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/></svg>
        </button>
        <a href="{{ route('panitia.verifikasi.dashboard') }}" class="flex ms-2">
          <div class="w-8 h-8 bg-gradient-to-br from-orange-600 to-orange-700 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <span class="self-center text-lg font-semibold text-gray-800 ms-3">SIP-<span class="text-orange-700">S</span> <span class="text-xs text-gray-500">| Verifikasi</span></span>
        </a>
      </div>
      <div class="flex items-center">
        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ea580c&color=fff" alt="">
      </div>
    </div>
  </div>
</nav>

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0 pt-16">
   <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-gray-200">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('panitia.verifikasi.dashboard') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 {{ request()->routeIs('panitia.verifikasi.dashboard') ? 'bg-orange-50 text-orange-700' : '' }}">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/></svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         <li class="pt-4">
            <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Verifikasi</span>
         </li>
         <li>
            <a href="{{ route('panitia.verifikasi.berkas') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700 {{ request()->routeIs('panitia.verifikasi.berkas') ? 'bg-orange-50 text-orange-700' : '' }}">
               <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
               <span class="flex-1 ms-3">Verifikasi Berkas</span>
               <span class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-orange-800 bg-orange-100 rounded-full">!</span>
            </a>
         </li>
         <li class="pt-4">
            <span class="px-2 text-xs font-semibold text-gray-400 uppercase">Akun</span>
         </li>
         <li>
            <a href="{{ route('panitia.verifikasi.profile') }}" class="flex items-center px-2 py-1.5 text-gray-700 rounded-lg hover:bg-orange-50 hover:text-orange-700">
               <svg class="shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
               <span class="flex-1 ms-3">Profile</span>
            </a>
         </li>
         <li>
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="flex items-center w-full px-2 py-1.5 text-red-600 rounded-lg hover:bg-red-50">
                  <svg class="shrink-0 w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/></svg>
                  <span class="flex-1 ms-3">Sign Out</span>
               </button>
            </form>
         </li>
      </ul>
   </div>
</aside>
<script>document.addEventListener('DOMContentLoaded',function(){document.querySelector('[data-drawer-toggle="top-bar-sidebar"]')?.addEventListener('click',function(){document.getElementById('top-bar-sidebar').classList.toggle('-translate-x-full')})});</script>
