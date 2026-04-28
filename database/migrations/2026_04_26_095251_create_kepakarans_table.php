<?php
// database/migrations/xxxx_xx_xx_create_kepakarans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kepakarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kepakaran');
            $table->integer('hierarki_level')->default(1); // 1 tertinggi (Profesor), 2, 3, dst
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kepakarans');
    }
};
