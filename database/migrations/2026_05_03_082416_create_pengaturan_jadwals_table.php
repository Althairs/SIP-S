<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->json('jam_mulai')->nullable(); // ["08:00", "10:00", "13:00", "15:00"]
            $table->json('jam_selesai')->nullable(); // ["10:00", "12:00", "15:00", "17:00"]
            $table->string('label_sesi')->nullable(); // ["Sesi 1", "Sesi 2", "Sesi 3", "Sesi 4"]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->string('lokasi')->nullable();
            $table->integer('kapasitas')->default(20);
            $table->boolean('is_active')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangans');
        Schema::dropIfExists('pengaturan_jadwals');
    }
};
