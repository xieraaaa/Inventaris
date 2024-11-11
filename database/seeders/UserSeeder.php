<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name'          => 'admin',
            'email'         => 'admin@wbi.ac.id',
            'profile_photo' => null,
            'password'      => Hash::make('adminadmin')
        ]);

        User::create([
            'name'          => 'user',
            'email'         => 'user@wbi.ac.id',
            'profile_photo' => null,
            'password'      => Hash::make('userpassword')
        ]);

        $admin->assignRole('admin');
    }
}
