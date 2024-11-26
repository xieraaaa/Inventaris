<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
			UserSeeder::class,
            UnitSeeder::class,
            KategoriSeeder::class,
            MerekSeeder::class,
            BarangSeeder::class
        ]);
    }
}
