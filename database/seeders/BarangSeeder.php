<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\UnitBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder {
    public function run()
    {
        Barang::factory()
            ->has(UnitBarang::factory()->count(50), 'unitBarang')
            ->count(50)
            ->create();
    }
}
