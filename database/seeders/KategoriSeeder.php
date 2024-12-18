<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder {
    public function run()
    {
        $amount = 10 - count(Kategori::all());

        if (!$amount) {
            return;
        }
        
        Kategori::factory()->count($amount)->create();
    }
}
