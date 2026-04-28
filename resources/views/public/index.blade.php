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
                            <div class="w-10 h-10 rounded-full bg-green-200 border-2 border-white flex items-center justify-center text-green-800 font-semibold">M</div>
                            <div class="w-10 h-10 rounded-full bg-amber-200 border-2 border-white flex items-center justify-center text-amber-800 font-semibold">D</div>
                            <div class="w-10 h-10 rounded-full bg-emerald-200 border-2 border-white flex items-center justify-center text-emerald-800 font-semibold">A</div>
                        </div>
                        <p class="text-gray-600">
                            <span class="font-bold text-gray-900">500+</span> Mahasiswa •
                            <span class="font-bold text-gray-900">50+</span> Dosen
                        </p>
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

    <!-- Jadwal Minggu Ini Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Jadwal <span class="text-green-700">Minggu Ini</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Informasi jadwal bimbingan, seminar proposal, dan sidang skripsi minggu ini
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 border border-green-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-sm font-medium">Seminar Proposal</span>
                        <span class="text-green-700 font-bold">3 Jadwal</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Senin, 15 Jan 2024</p>
                                <p class="text-sm text-gray-600">09:00 - 12:00 WIB</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Rabu, 17 Jan 2024</p>
                                <p class="text-sm text-gray-600">13:00 - 16:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gradient-to-br from-amber-50 to-white rounded-2xl p-6 border border-amber-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-amber-200 text-amber-800 rounded-full text-sm font-medium">Sidang Skripsi</span>
                        <span class="text-amber-700 font-bold">2 Jadwal</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Selasa, 16 Jan 2024</p>
                                <p class="text-sm text-gray-600">10:00 - 12:00 WIB</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Kamis, 18 Jan 2024</p>
                                <p class="text-sm text-gray-600">14:00 - 16:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl p-6 border border-emerald-100 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-emerald-200 text-emerald-800 rounded-full text-sm font-medium">Bimbingan</span>
                        <span class="text-emerald-700 font-bold">5 Jadwal</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Sepanjang Minggu</p>
                                <p class="text-sm text-gray-600">Sesuai Kesepakatan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
    <section id="panduan" class="py-16 bg-gradient-to-b from-white to-green-50">
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
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Pengajuan judul skripsi
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Bimbingan dengan dosen
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Upload progress skripsi
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Pendaftaran seminar & sidang
                        </li>
                    </ul>
                    <a href="#" class="inline-block mt-6 text-green-700 font-semibold hover:text-green-800">
                        Selengkapnya →
                    </a>
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
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Persetujuan judul skripsi
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Jadwal bimbingan
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Review progress mahasiswa
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Penilaian seminar & sidang
                        </li>
                    </ul>
                    <a href="#" class="inline-block mt-6 text-amber-700 font-semibold hover:text-amber-800">
                        Selengkapnya →
                    </a>
                </div>

                <!-- Admin -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-emerald-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Admin</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Kelola data master
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Manajemen pengguna
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Penjadwalan kegiatan
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Laporan dan statistik
                        </li>
                    </ul>
                    <a href="#" class="inline-block mt-6 text-emerald-700 font-semibold hover:text-emerald-800">
                        Selengkapnya →
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
