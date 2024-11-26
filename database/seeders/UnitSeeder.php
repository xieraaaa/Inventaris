<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder {
    public function run()
    {
        Unit::factory()->count(10)->create();
    }
}
