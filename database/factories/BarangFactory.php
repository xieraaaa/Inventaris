<?php

namespace Database\Factories;

use App\Models\{
    Merek,
    Kategori,
    Unit
};
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    public function definition(): array
    {
        $categoryMaxIndex = Kategori::orderBy('id', 'desc')->first()['id'];
        $unitMaxIndex     = Unit::orderBy('id', 'desc')->first()['id'];
        $merekMaxIndex    = Merek::orderBy('id', 'desc')->first()['id'];
        
        return [
            'nama_barang' => fake()->unique()->words(3, true),
            'id_kategori' => fake()->numberBetween(Kategori::first()['id'], $categoryMaxIndex),
            'id_unit'     => fake()->numberBetween(Unit::first()['id'], $unitMaxIndex),
            'id_merek'    => fake()->numberBetween(Merek::first()['id'], $merekMaxIndex),
        ];
    }
}
