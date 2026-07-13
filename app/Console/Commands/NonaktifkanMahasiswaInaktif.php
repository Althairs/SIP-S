<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NonaktifkanMahasiswaInaktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mahasiswa:nonaktifkan-inaktif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menonaktifkan akun mahasiswa yang tidak mendaftar dalam 5 bulan terakhir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan mahasiswa inaktif...');
        Log::info('Memulai otomatisasi pemeriksaan mahasiswa inaktif...');

        $fiveMonthsAgo = Carbon::now()->subMonths(5);
        $deactivatedCount = 0;

        // Ambil semua mahasiswa yang berstatus aktif
        $students = User::role('mahasiswa')->where('is_active', true)->get();

        foreach ($students as $student) {
            // Dapatkan pendaftaran terakhir
            $latestPendaftaran = $student->pendaftaranAktif ?? $student->pendaftarans()->latest()->first();

            $shouldDeactivate = false;

            if ($latestPendaftaran) {
                // Cek apakah pendaftaran terakhir lebih lama dari 5 bulan lalu
                if ($latestPendaftaran->created_at->lt($fiveMonthsAgo)) {
                    $shouldDeactivate = true;
                }
            } else {
                // Jika tidak pernah mendaftar sama sekali, cek umur pembuatan akun
                if ($student->created_at->lt($fiveMonthsAgo)) {
                    $shouldDeactivate = true;
                }
            }

            if ($shouldDeactivate) {
                $student->update(['is_active' => false]);
                $deactivatedCount++;
                $this->info("Menonaktifkan mahasiswa: {$student->name} (NIM: {$student->nim})");
                Log::info("Akun mahasiswa {$student->name} (NIM: {$student->nim}) dinonaktifkan otomatis karena tidak mendaftar ujian selama 5 bulan.");
            }
        }

        $this->info("Selesai. Total akun dinonaktifkan: {$deactivatedCount}");
        Log::info("Selesai memeriksa mahasiswa inaktif. Total dinonaktifkan: {$deactivatedCount}");
    }
}
