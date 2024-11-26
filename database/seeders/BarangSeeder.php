<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

require_once 'vendor/autoload.php';

class BarangSeeder extends Seeder {
    public function run()
    {
        Barang::factory()->count(200)->create();
    }
}
