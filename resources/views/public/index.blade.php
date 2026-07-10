<x-layouts.app>
    @section('title', 'Beranda')

    <!-- Hero Section - Style AgriSched -->
    <section class="relative z-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <div class="grid md:grid-cols-2 gap-8 items-center min-h-[80vh]">
            <!-- Left Content -->
            <div class="space-y-6">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                    <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                    Fakultas Pertanian
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-green-700 leading-tight">
                    Selamat Datang di SIP-S
                </h1>

                <p class="text-lg text-gray-600 leading-relaxed">
                    Sistem Informasi Pengelolaan Skripsi Fakultas Pertanian.
                    Platform terintegrasi untuk memudahkan proses bimbingan skripsi
                    antara mahasiswa, dosen, dan administrator.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('jadwal') }}"
                       class="px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition-colors duration-200 font-medium shadow-sm">
                        Lihat Jadwal
                    </a>
                    {{-- <a href="#panduan"
                       class="px-6 py-3 border-2 border-green-700 text-green-700 rounded-lg hover:bg-green-50 transition-colors duration-200 font-medium">
                        Panduan Penggunaan
                    </a> --}}
                </div>

                <!-- Stats Mini -->
                <div class="flex items-center space-x-6 pt-4">
                    <div class="flex -space-x-2">
                        <div class="w-10 h-10 rounded-full bg-green-200 border-2 border-white flex items-center justify-center">
                            <span class="text-green-800 font-semibold text-sm">M</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-amber-200 border-2 border-white flex items-center justify-center">
                            <span class="text-amber-800 font-semibold text-sm">D</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-200 border-2 border-white flex items-center justify-center">
                            <span class="text-emerald-800 font-semibold text-sm">J</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-bold text-gray-900">{{ $totalMahasiswa }}+</span> Mahasiswa •
                        <span class="font-bold text-gray-900">{{ $totalDosen }}+</span> Dosen •
                        <span class="font-bold text-gray-900">{{ $totalJurusans }}</span> Jurusan
                    </div>
                </div>
            </div>

            <!-- Right Animation -->
            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-green-100 rounded-full opacity-20 blur-3xl"></div>
                    <lottie-player
                        src="{{ asset('animasi/animasi.json') }}"
                        background="transparent"
                        speed="0.4"
                        class="w-full max-w-[400px] h-auto relative z-10"
                        loop
                        autoplay>
                    </lottie-player>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Section - Style AgriSched -->
    <section class="bg-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-semibold text-green-700 mb-8 text-center">
                Statistik Jadwal Minggu Ini
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Total Jadwal -->
                <div class="bg-green-50 rounded-lg p-6 text-center hover:shadow-md transition-shadow duration-200">
                    <div class="text-3xl font-bold text-green-700">{{ $totalUjian }}</div>
                    <div class="text-sm text-green-600 mt-2">Total Ujian</div>
                </div>

                <!-- Seminar Proposal -->
                <div class="bg-blue-50 rounded-lg p-6 text-center hover:shadow-md transition-shadow duration-200">
                    <div class="text-3xl font-bold text-blue-700">
                        {{ $jadwalMingguIni->where('jenis_ujian', 'seminar_proposal')->count() }}
                    </div>
                    <div class="text-sm text-blue-600 mt-2">Seminar Proposal</div>
                </div>

                <!-- Seminar Hasil -->
                <div class="bg-yellow-50 rounded-lg p-6 text-center hover:shadow-md transition-shadow duration-200">
                    <div class="text-3xl font-bold text-yellow-700">
                        {{ $jadwalMingguIni->where('jenis_ujian', 'seminar_hasil')->count() }}
                    </div>
                    <div class="text-sm text-yellow-600 mt-2">Seminar Hasil</div>
                </div>

                <!-- Sidang Skripsi -->
                <div class="bg-red-50 rounded-lg p-6 text-center hover:shadow-md transition-shadow duration-200">
                    <div class="text-3xl font-bold text-red-700">
                        {{ $jadwalMingguIni->where('jenis_ujian', 'sidang_skripsi')->count() }}
                    </div>
                    <div class="text-sm text-red-600 mt-2">Sidang Skripsi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jadwal Minggu Ini - Style AgriSched (Tabel) -->
    <section class="bg-gray-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-semibold text-green-700 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Jadwal Skripsi Minggu Ini
            </h3>

            @if($jadwalMingguIni->count() > 0)
            <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="bg-green-700 text-white">
                            <th class="p-4 text-left font-medium">Tanggal & Waktu</th>
                            <th class="p-4 text-left font-medium">Nama Mahasiswa</th>
                            <th class="p-4 text-left font-medium">Jenis Ujian</th>
                            <th class="p-4 text-left font-medium">Judul Penelitian</th>
                            <th class="p-4 text-left font-medium">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($jadwalMingguIni as $jadwal)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="p-4">
                                <div class="font-medium">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('d M Y') }}</div>
                                <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($jadwal->tanggal_ujian)->format('H:i') }} WIB</div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium">{{ $jadwal->mahasiswa->name }}</div>
                                <div class="text-sm text-gray-600">{{ $jadwal->mahasiswa->nim ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($jadwal->jenis_ujian == 'seminar_proposal') bg-blue-100 text-blue-800
                                    @elseif($jadwal->jenis_ujian == 'seminar_hasil') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucwords(str_replace('_', ' ', $jadwal->jenis_ujian)) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="line-clamp-2">{{ Str::limit($jadwal->judul_penelitian, 50) }}</span>
                            </td>
                            <td class="p-4 font-medium">{{ $jadwal->ruangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <p class="text-yellow-700 text-lg">Tidak ada jadwal sidang untuk minggu ini.</p>
                <p class="text-yellow-600 text-sm mt-2">Silakan cek halaman jadwal lengkap untuk melihat semua jadwal yang tersedia.</p>
            </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('jadwal') }}"
                   class="inline-flex items-center px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 transition-colors duration-200 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Lihat Detail Jadwal Lengkap
                </a>
            </div>
        </div>
    </section>

    <!-- Informasi Sistem - Style AgriSched -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-semibold text-green-700 mb-8 text-center">
                Informasi Sistem Penjadwalan
            </h3>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Aturan Ruangan -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Aturan Ruangan
                    </h4>
                    <ul class="space-y-3 text-blue-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong>Seminar Proposal & Hasil:</strong> Maksimal 5 mahasiswa dalam 1 ruangan</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong>Sidang Skripsi:</strong> 1 ruangan hanya untuk 1 mahasiswa</span>
                        </li>
                    </ul>
                </div>

                <!-- Informasi Penting -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Penting
                    </h4>
                    <ul class="space-y-3 text-green-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Jadwal dapat berubah sewaktu-waktu</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Mahasiswa diharapkan hadir 15 menit sebelum jadwal</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Dosen tidak boleh memiliki jadwal bentrok</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan SIP-S - Style AgriSched -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-semibold text-green-700 mb-10 text-center">
                Mengapa Memilih SIP-S?
            </h3>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Efisien -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Efisien</h4>
                    <p class="text-gray-600">Proses penjadwalan yang cepat dan tidak memakan waktu lama</p>
                </div>

                <!-- Terpercaya -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Terpercaya</h4>
                    <p class="text-gray-600">Sistem yang aman dan terpercaya untuk semua pengguna</p>
                </div>

                <!-- Cepat -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Cepat</h4>
                    <p class="text-gray-600">Respon cepat dalam pengelolaan jadwal dan notifikasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Panduan Section (Dipertahankan dari SIP-S) -->
    {{-- <section id="panduan" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-green-700 mb-4">
                    Panduan Penggunaan
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Panduan lengkap untuk setiap peran dalam sistem SIP-S
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Mahasiswa -->
                <div class="bg-white rounded-lg p-6 border border-green-200 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Mahasiswa</h3>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Pendaftaran ujian
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Upload berkas
                        </li>
                    </ul>
                    <a href="{{ route('pdf.mahasiswa') }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </a>
                </div>

                <!-- Dosen -->
                <div class="bg-white rounded-lg p-6 border border-amber-200 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-amber-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Dosen</h3>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-amber-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Revisi & penilaian
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-amber-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Monitoring kuota
                        </li>
                    </ul>
                    <a href="{{ route('pdf.dosen') }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </a>
                </div>

                <!-- Admin -->
                <div class="bg-white rounded-lg p-6 border border-emerald-200 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Admin & Panitia</h3>
                    <ul class="space-y-2 text-sm text-gray-600 mb-4">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-emerald-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Verifikasi berkas
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-emerald-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Penjadwalan ujian
                        </li>
                    </ul>
                    <div class="space-y-2">
                        <a href="{{ route('pdf.panitia-verifikasi') }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition text-sm w-full justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Panitia Verifikasi
                        </a>
                        <a href="{{ route('pdf.panitia-penjadwalan') }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-cyan-700 text-white rounded-lg hover:bg-cyan-800 transition text-sm w-full justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Panitia Penjadwalan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
</x-layouts.app>
