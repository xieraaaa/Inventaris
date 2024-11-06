<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $view_barang = Permission::create(['name' => 'view barang']);

        Role::where('name', 'user')->first()->givePermissionTo($view_barang);
    }
}
