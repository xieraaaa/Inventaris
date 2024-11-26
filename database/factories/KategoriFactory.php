<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory {
    public function definition()
    {
        return [
            'kategori' => fake()->unique()->word()
        ];
    }
}
