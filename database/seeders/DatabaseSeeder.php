<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Database\Seeders\RolesSeeder;
use Database\Seeders\PermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class
        ]);
    }
}
