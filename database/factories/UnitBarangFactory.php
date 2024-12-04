<?php

namespace Database\Factories;

use App\Models\{
    Barang,
    UnitBarang
};
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitBarangFactory extends Factory {
    public function definition()
    {
        $barangFirstIndex = Barang::first()['id'];
        $barangLastIndex = Barang::orderBy('id', 'desc')->first()['id'];
        
        $kondisiArray = ['Tersedia', 'Tidak Tersedia'];
        
        return [
            'id_barang'          => fake()->numberBetween($barangFirstIndex, $barangLastIndex),
            'kode_inventaris'    => fake()->unique()->numberBetween(10000000, 99999999),
            'lokasi'             => fake()->words(4, true),
            'kondisi'            => $kondisiArray[fake()->numberBetween(0, 1)],
            'tanggal_inventaris' => fake()->dateTimeBetween('-1 week', '+1 week')
        ];
    }
}
