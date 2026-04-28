<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->nullable()->after('remember_token')->constrained('jurusans')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->after('jurusan_id')->constrained('prodis')->nullOnDelete();
            $table->string('nip', 20)->nullable()->unique()->after('prodi_id');
            $table->string('nim', 15)->nullable()->unique()->after('nip');
            $table->string('nomor_hp', 15)->nullable()->after('nim');
            $table->text('alamat')->nullable()->after('nomor_hp');
            $table->string('foto')->nullable()->after('alamat');
            $table->boolean('is_active')->default(true)->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropForeign(['prodi_id']);
            $table->dropColumn([
                'jurusan_id', 'prodi_id', 'nip', 'nim',
                'nomor_hp', 'alamat', 'foto', 'is_active'
            ]);
        });
    }
};
