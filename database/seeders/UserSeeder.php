<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userCredentials = [
            'name'          => 'user',
            'email'         => 'user@wbi.ac.id',
            'profile_photo' => null,
            'password'      => Hash::make('userpassword')
        ];
    
        $adminCredentials = [
            'name'          => 'admin',
            'email'         => 'admin@wbi.ac.id',
            'profile_photo' => null,
            'password'      => Hash::make('adminadmin')
        ];
        
        $superadminCredentials = [
            'name'          => 'superadmin',
            'email'         => 'superadmin@wbi.ac.id',
            'profile_photo' => null,
            'password'      => Hash::make('superadminsecure')
        ];
        
        $user       = User::firstWhere('name', 'user');
        $admin      = User::firstWhere('name', 'admin');
        $superadmin = User::firstWhere('name', 'superadmin');

        if (is_null($user))
        {
            User::create($userCredentials);
        }

        if (is_null($admin))
        {
            $admin = User::create($adminCredentials);

            $admin->removeRole('user');
            $admin->assignRole('admin');
        }

        if (is_null($superadmin))
        {
            $superadmin = User::create($superadminCredentials);

            $superadmin->removeRole('user');
            $superadmin->assignRole('superadmin');
        }
    }
}
