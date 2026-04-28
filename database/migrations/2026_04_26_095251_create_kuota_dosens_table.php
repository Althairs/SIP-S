<?php
// database/migrations/xxxx_xx_xx_create_kuota_dosens_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuota_dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->integer('kuota_pembimbing')->default(5);
            $table->integer('kuota_penguji')->default(10);
            $table->integer('terpakai_pembimbing')->default(0);
            $table->integer('terpakai_penguji')->default(0);
            $table->timestamps();

            $table->unique('dosen_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuota_dosens');
    }
};
