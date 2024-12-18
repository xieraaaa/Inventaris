<?php

namespace Database\Seeders;

use App\Models\Merek;
use Illuminate\Database\Seeder;

class MerekSeeder extends Seeder {
    public function run()
    {
        $amount = 10 - count(Merek::all());

        if (!$amount) {
            return;
        }
        
        Merek::factory()->count($amount)->create();
    }
}
