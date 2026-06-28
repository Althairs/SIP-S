<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Services\LaporanPdfService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(
        protected LaporanPdfService $laporanPdfService
    ) {}

    public function download(Request $request, string $jenis)
    {
        $allowed = ['pendaftaran', 'kuota-dosen', 'nilai', 'ujian-selesai'];

        if (! in_array($jenis, $allowed, true)) {
            abort(404);
        }

        $filters = $request->validate([
            'prodi_id' => 'nullable|integer|exists:prodis,id',
            'jenis_ujian' => 'nullable|in:seminar_proposal,seminar_hasil,sidang_skripsi',
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2020|max:2100',
        ]);

        if (empty($filters['bulan']) xor empty($filters['tahun'])) {
            unset($filters['bulan'], $filters['tahun']);
        }

        return $this->laporanPdfService->download(
            $jenis,
            auth()->user()->jurusan_id,
            $filters
        );
    }
}
