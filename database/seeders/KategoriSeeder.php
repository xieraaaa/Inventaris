<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder {
    public function run()
    {
        Kategori::factory()->count(10)->create();
    }
}
