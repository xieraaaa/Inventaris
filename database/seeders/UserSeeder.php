<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Isi tabel 'user' dengan data
     */
    public function run(): void
    {
        // superadmin memang hanya 1!
        User::create([
            'name'          => 'superadmin',
            'email'         => 'superadmin@wbi.ac.id',
            'profile_photo' => null,
            'password'      => Hash::make('superadminsecure')
        ])->removeRole('user')->assignRole('superadmin');
        
        if (is_null(User::where('name', 'admin')->first()))
        {
            \Illuminate\Support\Facades\Log::info('UserSeeder.php\n\tUser dengan nama \'admin\' sudah dibuat');

            User::create([
                'name'          => 'admin',
                'email'         => 'admin@wbi.ac.id',
                'profile_photo' => null,
                'password'      => Hash::make('adminadmin')
            ])->removeRole('user')->assignRole('admin');
        }

        if (is_null(User::firstWhere('name', 'user')))
        {
            \Illuminate\Support\Facades\Log::info('UserSeeder.php\n\tUser dengan nama \'user\' sudah dibuat');
            
            User::create([
                'name'          => 'user',
                'email'         => 'user@wbi.ac.id',
                'profile_photo' => null,
                'password'      => Hash::make('userpassword')
            ]);
        }
    }
}
