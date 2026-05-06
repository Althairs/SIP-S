<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_bidang_keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bidang_keahlian_id')->constrained('bidang_keahlians')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'bidang_keahlian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_bidang_keahlian');
    }
};
