<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE revisis MODIFY status ENUM('pending', 'diperiksa', 'disetujui', 'selesai', 'ditolak') NOT NULL DEFAULT 'pending'");

        DB::table('revisis')->where('status', 'selesai')->update(['status' => 'disetujui']);
    }

    public function down(): void
    {
        DB::table('revisis')->where('status', 'disetujui')->update(['status' => 'selesai']);

        DB::statement("ALTER TABLE revisis MODIFY status ENUM('pending', 'selesai', 'ditolak') NOT NULL DEFAULT 'pending'");
    }
};
