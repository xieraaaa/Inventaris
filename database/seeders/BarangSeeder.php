<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\UnitBarang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder {
    private $totalAmountBarang = 50;
    private $totalAmountUnitL = 25;
    private $totalAmountUnitH = 25;
    
    public function run()
    {
        $barangCount = count(Barang::all());

        $totalAmountBarang = $this->totalAmountBarang - $barangCount;

        if (!$totalAmountBarang) {
            return;
        }
        
        // Menggunakan loop daripada Factory::count karena setiap barang
        // memiliki unit dengan jumlah yang tidak tentu
        $count = 0;
        do {
            $unitBarangFactory = UnitBarang::factory()
                ->count(
                    fake()->numberBetween($this->totalAmountUnitL, $this->totalAmountUnitH)
                );

            Barang::factory()
                ->has($unitBarangFactory, 'unitBarang')
                ->create();
        } while (++$count < $totalAmountBarang);
    }
}
