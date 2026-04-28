<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_bidang_keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('bidang_keahlian_id')->constrained('bidang_keahlians')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint
            $table->unique(['pendaftaran_id', 'bidang_keahlian_id'], 'pendaftaran_bidang_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_bidang_keahlian');
    }
};
