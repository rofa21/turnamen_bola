<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@disdikpora.id'],
            [
                'name' => 'Super Admin Disdikpora',
                'email' => 'admin@disdikpora.id',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );
    }
}
