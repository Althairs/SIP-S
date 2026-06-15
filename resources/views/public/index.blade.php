<x-layouts.app>
    @section('title', 'Beranda')

    <!-- Hero Section -->
    <section class="pt-24 pb-16 bg-gradient-to-br from-green-50 via-white to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div>
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
                        Fakultas Pertanian
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                        Selamat Datang di
                        <span class="text-green-700">SIP-S</span>
                    </h1>

                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Sistem Informasi Pengelolaan Skripsi Fakultas Pertanian.
                        Platform terintegrasi untuk memudahkan proses bimbingan skripsi
                        antara mahasiswa, dosen, dan administrator.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('jadwal') }}" class="px-8 py-3 bg-green-700 text-white rounded-full hover:bg-green-800 transition shadow-lg shadow-green-200 font-semibold">
                            Lihat Jadwal
                        </a>
                        <a href="#panduan" class="px-8 py-3 border-2 border-green-700 text-green-700 rounded-full hover:bg-green-50 transition font-semibold">
                            Panduan Penggunaan
                        </a>
                    </div>

                    <div class="mt-8 flex items-center space-x-4">
                        <div class="flex -space-x-2">
                            <div class="w-10 h-10 rounded-full bg-green-200 border-2 border-white flex items-center justify-center text-green-800 font-semibold text-sm">M</div>
                            <div class="w-10 h-10 rounded-full bg-amber-200 border-2 border-white flex items-center justify-center text-amber-800 font-semibold text-sm">D</div>
                            <div class="w-10 h-10 rounded-full bg-emerald-200 border-2 border-white flex items-center justify-center text-emerald-800 font-semibold text-sm">A</div>
                        </div>
                        <div class="text-gray-600 text-sm">
                            <span class="font-bold text-gray-900">{{ $totalMahasiswa }}+</span> Mahasiswa •
                            <span class="font-bold text-gray-900">{{ $totalDosen }}+</span> Dosen •
                            <span class="font-bold text-gray-900">{{ $totalJurusans }}</span> Jurusan
                        </div>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-200/20 to-amber-200/20 rounded-3xl"></div>
                    <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?w=600"
                         alt="Pertanian Modern"
                         class="relative rounded-3xl shadow-2xl w-full h-auto object-cover"
                         style="min-height: 400px;">
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-green-100 rounded-full opacity-20"></div>
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-100 rounded-full opacity-20"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-green-50 rounded-2xl">
                    <p class="text-4xl font-bold text-green-700">{{ $totalMahasiswa }}+</p>
                    <p class="text-sm text-green-600 mt-2">Mahasiswa</p>
                </div>
                <div class="text-center p-6 bg-blue-50 rounded-2xl">
                    <p class="text-4xl font-bold text-blue-700">{{ $totalDosen }}+</p>
                    <p class="text-sm text-blue-600 mt-2">Dosen</p>
                </div>
                <div class="text-center p-6 bg-amber-50 rounded-2xl">
                    <p class="text-4xl font-bold text-amber-700">{{ $totalJurusans }}</p>
                    <p class="text-sm text-amber-600 mt-2">Jurusan</p>
                </div>
                <div class="text-center p-6 bg-purple-50 rounded-2xl">
                    <p class="text-4xl font-bold text-purple-700">{{ $totalUjian }}+</p>
                    <p class="text-sm text-purple-600 mt-2">Ujian Terlaksana</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Jadwal Minggu Ini Section -->
    <section class="py-16 bg-gradient-to-b from-white to-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Jadwal <span class="text-green-700">Minggu Ini</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Informasi jadwal seminar proposal, seminar hasil, dan sidang skripsi minggu ini
                </p>
            </div>

            @if($jadwalMingguIni->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal Minggu Ini</h3>
                <p class="text-gray-500">Belum ada ujian yang dijadwalkan untuk minggu ini.</p>
            </div>
            @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwalMingguIni as $jadwal)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-{{ $jadwal->jenis_ujian === 'seminar_proposal' ? 'blue' : ($jadwal->jenis_ujian === 'seminar_hasil' ? 'amber' : 'purple') }}-100 text-{{ $jadwal->jenis_ujian === 'seminar_proposal' ? 'blue' : ($jadwal->jenis_ujian === 'seminar_hasil' ? 'amber' : 'purple') }}-800 rounded-full text-xs font-medium">
                            {{ ucwords(str_replace('_', ' ', $jadwal->jenis_ujian)) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('d M Y') }}</span>
                    </div>

                    <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2">{{ Str::limit($jadwal->judul_penelitian, 60) }}</h3>

                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $jadwal->mahasiswa->name }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('H:i') }} WIB
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $jadwal->ruangan ?? '-' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="text-center mt-10">
                <a href="{{ route('jadwal') }}" class="inline-flex items-center px-6 py-3 bg-green-700 text-white rounded-full hover:bg-green-800 transition shadow-md shadow-green-200 font-medium">
                    Lihat Semua Jadwal
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Panduan Section -->
    <section id="panduan" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Panduan <span class="text-green-700">Penggunaan</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Panduan lengkap untuk setiap peran dalam sistem SIP-S
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Mahasiswa -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-green-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Mahasiswa</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Pendaftaran ujian (seminar proposal, hasil, sidang)
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Upload berkas persyaratan
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Lihat jadwal dan nilai ujian
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Terima reminder & notifikasi
                        </li>
                    </ul>
                </div>

                <!-- Dosen -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-amber-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-600 to-amber-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Dosen</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Memberikan revisi dan penilaian
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Melihat jadwal menguji
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Monitoring kuota bimbingan
                        </li>
                    </ul>
                </div>

                <!-- Admin/Panitia -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-emerald-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Admin & Panitia</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Verifikasi berkas pendaftaran
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Penjadwalan ujian
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Manajemen data master
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
