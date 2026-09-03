<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            WilayahSeeder::class,
            KompetensiMasterSeeder::class,
            UserSeeder::class,
        ]);
    }
}