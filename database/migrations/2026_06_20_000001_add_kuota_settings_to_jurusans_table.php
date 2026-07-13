<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurusans', function (Blueprint $table) {
            $table->unsignedSmallInteger('default_kuota_pembimbing')->default(20)->after('is_active');
            $table->unsignedSmallInteger('default_kuota_penguji')->default(20)->after('default_kuota_pembimbing');
            $table->timestamp('kuota_last_reset_at')->nullable()->after('default_kuota_penguji');
        });
    }

    public function down(): void
    {
        Schema::table('jurusans', function (Blueprint $table) {
            $table->dropColumn(['default_kuota_pembimbing', 'default_kuota_penguji', 'kuota_last_reset_at']);
        });
    }
};
