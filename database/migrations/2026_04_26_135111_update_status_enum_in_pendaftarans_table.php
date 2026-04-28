<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update data lama dulu
        DB::table('pendaftarans')
            ->where('status', 'disetujui_kaprodi')
            ->update(['status' => 'disetujui_panitia']);

        // Ubah kolom status untuk menambah enum baru
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status ENUM(
            'draft',
            'pending',
            'disetujui_panitia',
            'ditolak_panitia',
            'disetujui_sekjur',
            'ditolak_sekjur',
            'disetujui_kajur',
            'ditolak_kajur',
            'dijadwalkan',
            'selesai',
            'revisi'
        ) DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status ENUM(
            'draft',
            'pending',
            'disetujui_kaprodi',
            'ditolak_kaprodi',
            'disetujui_kajur',
            'ditolak_kajur',
            'dijadwalkan',
            'selesai',
            'revisi'
        ) DEFAULT 'draft'");
    }
};
