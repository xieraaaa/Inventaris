<?php

namespace Database\Seeders;

use App\Models\Merek;
use Illuminate\Database\Seeder;

class MerekSeeder extends Seeder {
    public function run()
    {
        Merek::factory()->count(10)->create();
    }
}
