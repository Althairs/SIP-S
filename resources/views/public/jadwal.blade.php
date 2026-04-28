<x-layouts.app>
    @section('title', 'Jadwal')

    <section class="pt-24 pb-16 bg-gradient-to-br from-green-50 via-white to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Jadwal <span class="text-green-700">Kegiatan</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Informasi lengkap jadwal seminar proposal, sidang skripsi, dan kegiatan akademik lainnya
                </p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm border border-green-100">
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kegiatan</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            <option>Semua Kegiatan</option>
                            <option>Seminar Proposal</option>
                            <option>Sidang Skripsi</option>
                            <option>Bimbingan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            <option>Semua Prodi</option>
                            <option>Agroteknologi</option>
                            <option>Agribisnis</option>
                            <option>Ilmu Tanah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <!-- Jadwal Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-green-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Waktu</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Kegiatan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Program Studi</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Ruangan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Data akan di-loop dari database -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">15 Jan 2024</td>
                                <td class="px-6 py-4 text-sm text-gray-700">09:00 - 11:00</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Seminar Proposal - Andi Pratama</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Agroteknologi</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Ruang Seminar 1</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Akan Datang</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">16 Jan 2024</td>
                                <td class="px-6 py-4 text-sm text-gray-700">13:00 - 15:00</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Sidang Skripsi - Siti Rahma</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Agribisnis</td>
                                <td class="px-6 py-4 text-sm text-gray-700">Ruang Sidang 2</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Persiapan</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
