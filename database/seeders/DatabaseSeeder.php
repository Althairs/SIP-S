<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            JurusanUserSeeder::class,
            BidangKeahlianSeeder::class,
            KepakaranKuotaSeeder::class,
            PengaturanJadwalSeeder::class,
        ]);
    }
}
