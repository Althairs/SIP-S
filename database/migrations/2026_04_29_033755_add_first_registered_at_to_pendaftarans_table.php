<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans', 'first_registered_at')) {
                $table->timestamp('first_registered_at')->nullable()->after('created_at');
            }
        });

        // Set first_registered_at = created_at untuk data yang sudah ada
        DB::table('pendaftarans')->whereNull('first_registered_at')->update([
            'first_registered_at' => DB::raw('created_at')
        ]);
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('first_registered_at');
        });
    }
};
