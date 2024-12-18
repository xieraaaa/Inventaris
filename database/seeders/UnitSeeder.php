<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder {
    public function run()
    {
        $amount = 10 - count(Unit::all());

        if (!$amount) {
            return;
        }
        
        Unit::factory()->count($amount)->create();
    }
}
