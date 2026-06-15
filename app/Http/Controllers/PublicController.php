<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Jurusan;
use App\Models\User;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        // Statistik untuk landing page
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalDosen = User::role('dosen')->count();
        $totalJurusans = Jurusan::active()->count();
        $totalUjian = Pendaftaran::whereIn('status', ['dijadwalkan', 'selesai'])->count();

        // Jadwal minggu ini untuk section jadwal
        $jadwalMingguIni = Pendaftaran::with(['mahasiswa', 'jurusan'])
            ->where('status', 'dijadwalkan')
            ->whereBetween('tanggal_ujian', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('tanggal_ujian')
            ->take(6)
            ->get();

        // Jadwal yang akan datang
        $jadwalMendatang = Pendaftaran::with(['mahasiswa', 'jurusan'])
            ->where('status', 'dijadwalkan')
            ->where('tanggal_ujian', '>=', Carbon::now())
            ->orderBy('tanggal_ujian')
            ->take(3)
            ->get();

        return view('public.index', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalJurusans',
            'totalUjian',
            'jadwalMingguIni',
            'jadwalMendatang'
        ));
    }

    /**
     * Display the schedule page.
     */
    public function jadwal(Request $request)
    {
        $jenisFilter = $request->get('jenis');
        $search = $request->get('search');
        $tanggal = $request->get('tanggal');

        $query = Pendaftaran::with(['mahasiswa', 'jurusan', 'prodi', 'pengujis.dosen'])
            ->whereIn('status', ['dijadwalkan', 'selesai']);

        // Filter jenis ujian
        if ($jenisFilter) {
            $query->where('jenis_ujian', $jenisFilter);
        }

        // Filter tanggal
        if ($tanggal) {
            $query->whereDate('tanggal_ujian', $tanggal);
        }

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul_penelitian', 'like', '%' . $search . '%')
                  ->orWhereHas('mahasiswa', function($mq) use ($search) {
                      $mq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('nim', 'like', '%' . $search . '%');
                  });
            });
        }

        $jadwals = $query->orderBy('tanggal_ujian')->paginate(15);

        // Statistik
        $totalSeminarProposal = Pendaftaran::where('jenis_ujian', 'seminar_proposal')
            ->whereIn('status', ['dijadwalkan', 'selesai'])->count();
        $totalSeminarHasil = Pendaftaran::where('jenis_ujian', 'seminar_hasil')
            ->whereIn('status', ['dijadwalkan', 'selesai'])->count();
        $totalSidang = Pendaftaran::where('jenis_ujian', 'sidang_skripsi')
            ->whereIn('status', ['dijadwalkan', 'selesai'])->count();

        return view('public.jadwal', compact(
            'jadwals',
            'totalSeminarProposal',
            'totalSeminarHasil',
            'totalSidang',
            'jenisFilter',
            'search',
            'tanggal'
        ));
    }
    public function wire()
    {
        return view('wireframe');
    }
}

