<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\UnitBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder {
    public function run()
    {
        $length = 50;
        for ($count = 0; $count < $length; ++$count) {
            Barang::factory()
                ->has(
                    UnitBarang::factory()
                        ->count(fake()->numberBetween(10, 100)), 'unitBarang')
                ->create();
        }
    }
}
