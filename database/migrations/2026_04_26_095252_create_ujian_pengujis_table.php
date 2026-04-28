<?php
// database/migrations/xxxx_xx_xx_create_ujian_pengujis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_pengujis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->enum('peran', ['penguji_1', 'penguji_2']);
            $table->foreignId('bidang_keahlian_id')->nullable()->constrained('bidang_keahlians')->onDelete('set null');
            $table->foreignId('kepakaran_id')->nullable()->constrained('kepakarans')->onDelete('set null');
            $table->integer('kuota_tersisa')->nullable();
            $table->boolean('is_overload')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['pendaftaran_id', 'peran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_pengujis');
    }
};
