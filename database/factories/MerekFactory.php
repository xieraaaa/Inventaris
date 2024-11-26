<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MerekFactory extends Factory {
    public function definition()
    {
        return [
            'merek' => fake()->word()
        ];
    }
}
