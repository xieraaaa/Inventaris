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
            'kode_barang' => fake()->unique()->numberBetween(100000, 999999),
            'nama_barang' => fake()->unique()->sentence(2),
            'id_kategori' => fake()->numberBetween(Kategori::first()['id'], $categoryMaxIndex),
            'id_unit'     => fake()->numberBetween(Unit::first()['id'], $unitMaxIndex),
            'id_merek'    => fake()->numberBetween(Merek::first()['id'], $merekMaxIndex),
            'jumlah'      => fake()->numberBetween(0, 500),
            'kondisi'     => fake()->boolean(),
            'keterangan'  => fake()->sentence(5)
        ];
    }
}
