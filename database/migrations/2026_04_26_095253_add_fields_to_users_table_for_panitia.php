<?php
// database/migrations/xxxx_xx_xx_add_kepakaran_to_users.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kepakaran_id')) {
                $table->foreignId('kepakaran_id')->nullable()->after('alamat')
                    ->constrained('kepakarans')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kepakaran_id']);
            $table->dropColumn('kepakaran_id');
        });
    }
};
