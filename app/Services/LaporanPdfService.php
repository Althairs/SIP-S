<?php

namespace App\Services;

use App\Models\KuotaDosen;
use App\Models\Pendaftaran;
use App\Models\Prodi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class LaporanPdfService
{
    public function logoBase64(): string
    {
        $path = public_path('images/logo_ung.png');

        if (! file_exists($path)) {
            return '';
        }

        return 'data:image/png;base64,'.base64_encode(file_get_contents($path));
    }

    public function download(string $jenis, int $jurusanId, array $filters = []): Response
    {
        $data = match ($jenis) {
            'pendaftaran' => $this->dataPendaftaran($jurusanId, $filters),
            'kuota-dosen' => $this->dataKuotaDosen($jurusanId, $filters),
            'nilai' => $this->dataNilai($jurusanId, $filters),
            'ujian-selesai' => $this->dataUjianSelesai($jurusanId, $filters),
            default => abort(404, 'Jenis laporan tidak ditemukan.'),
        };

        $view = 'pdf.laporan-'.$jenis;

        $pdf = Pdf::loadView($view, array_merge($data, [
            'logoBase64' => $this->logoBase64(),
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
            'filterLabel' => $this->buildFilterLabel($filters),
        ]))->setPaper('a4', 'portrait');

        return $pdf->download($this->fileName($jenis));
    }

    protected function fileName(string $jenis): string
    {
        $map = [
            'pendaftaran' => 'Laporan_Pendaftaran',
            'kuota-dosen' => 'Laporan_Kuota_Dosen',
            'nilai' => 'Laporan_Nilai',
            'ujian-selesai' => 'Laporan_Ujian_Selesai',
        ];

        return ($map[$jenis] ?? 'Laporan').'_'.now()->format('Y-m-d_His').'.pdf';
    }

    protected function buildFilterLabel(array $filters): string
    {
        $parts = [];

        if (! empty($filters['prodi_id'])) {
            $prodi = Prodi::find($filters['prodi_id']);
            $parts[] = 'Prodi: '.($prodi?->nama_prodi ?? '-');
        }

        if (! empty($filters['jenis_ujian'])) {
            $parts[] = 'Jenis Ujian: '.ucwords(str_replace('_', ' ', $filters['jenis_ujian']));
        }

        if (! empty($filters['bulan']) && ! empty($filters['tahun'])) {
            $parts[] = 'Periode: '.Carbon::createFromDate($filters['tahun'], $filters['bulan'], 1)->translatedFormat('F Y');
        }

        return $parts ? implode(' | ', $parts) : 'Semua data';
    }

    protected function dataPendaftaran(int $jurusanId, array $filters): array
    {
        $items = $this->basePendaftaranQuery($jurusanId, $filters)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'judulLaporan' => 'LAPORAN PENDAFTARAN MAHASISWA',
            'items' => $items,
        ];
    }

    protected function dataKuotaDosen(int $jurusanId, array $filters): array
    {
        $query = KuotaDosen::with(['dosen.prodi'])
            ->where('jurusan_id', $jurusanId);

        if (! empty($filters['prodi_id'])) {
            $query->whereHas('dosen', fn ($q) => $q->where('prodi_id', $filters['prodi_id']));
        }

        $items = $query->get()->sortBy('dosen.name');

        return [
            'judulLaporan' => 'LAPORAN KUOTA DOSEN MEMBIMBING DAN MENGUJI',
            'items' => $items,
        ];
    }

    protected function dataNilai(int $jurusanId, array $filters): array
    {
        $items = $this->basePendaftaranQuery($jurusanId, $filters)
            ->where('status', 'selesai')
            ->whereNotNull('nilai_total')
            ->with(['pengujis.dosen', 'dosens.dosen'])
            ->orderBy('completed_at', 'desc')
            ->get();

        return [
            'judulLaporan' => 'LAPORAN NILAI MAHASISWA',
            'items' => $items,
        ];
    }

    protected function dataUjianSelesai(int $jurusanId, array $filters): array
    {
        $items = $this->basePendaftaranQuery($jurusanId, $filters)
            ->where('status', 'selesai')
            ->orderBy('completed_at', 'desc')
            ->get();

        return [
            'judulLaporan' => 'LAPORAN MAHASISWA YANG SUDAH UJIAN',
            'items' => $items,
        ];
    }

    protected function basePendaftaranQuery(int $jurusanId, array $filters)
    {
        $query = Pendaftaran::with(['mahasiswa', 'prodi'])
            ->where('jurusan_id', $jurusanId);

        if (! empty($filters['prodi_id'])) {
            $query->where('prodi_id', $filters['prodi_id']);
        }

        if (! empty($filters['jenis_ujian'])) {
            $query->where('jenis_ujian', $filters['jenis_ujian']);
        }

        if (! empty($filters['bulan']) && ! empty($filters['tahun'])) {
            $query->whereYear('created_at', $filters['tahun'])
                ->whereMonth('created_at', $filters['bulan']);
        }

        return $query;
    }
}
