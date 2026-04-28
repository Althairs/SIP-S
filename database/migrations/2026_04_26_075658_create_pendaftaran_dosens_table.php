<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->enum('peran', ['pembimbing_1', 'pembimbing_2', 'penguji_1', 'penguji_2', 'penguji_3']);
            $table->boolean('is_approved')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi peran
            $table->unique(['pendaftaran_id', 'peran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_dosens');
    }
};
