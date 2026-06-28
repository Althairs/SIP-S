<?php

namespace App\Console\Commands;

use App\Models\Jurusan;
use App\Services\KuotaDosenService;
use Illuminate\Console\Command;

class ResetKuotaDosenBulanan extends Command
{
    protected $signature = 'kuota:reset-bulanan {--jurusan= : ID jurusan tertentu}';

    protected $description = 'Reset kuota dosen ke default bulanan per jurusan';

    public function handle(KuotaDosenService $service): int
    {
        $jurusanId = $this->option('jurusan');

        if ($jurusanId) {
            $jurusan = Jurusan::findOrFail($jurusanId);
            $count = $service->resetBulananForJurusan($jurusan);
            $this->info("Kuota {$count} dosen di jurusan {$jurusan->nama_jurusan} direset.");

            return self::SUCCESS;
        }

        $count = $service->resetBulananAll();
        $this->info("Kuota {$count} dosen direset untuk semua jurusan.");

        return self::SUCCESS;
    }
}
