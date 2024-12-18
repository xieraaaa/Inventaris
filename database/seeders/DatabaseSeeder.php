<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
			UserSeeder::class,
            UnitSeeder::class,
            KategoriSeeder::class,
            MerekSeeder::class,
            BarangSeeder::class,
            StatusSeeder::class,
        ]);
    }
}
