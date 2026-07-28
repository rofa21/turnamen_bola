<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,    // Akun admin default
            EventSeeder::class,         // Data event turnamen
            AgeCategorySeeder::class,   // Kategori usia (KU-10, KU-12, dst)
            // DemoDataSeeder::class,   // DINONAKTIFKAN: hanya untuk testing lokal
        ]);
    }
}
