<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@pinkbunny.com'],
            [
                'name'     => 'Pink Bunny Admin',
                'phone'    => '+201000000000',
                'password' => Hash::make('Admin@123456'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('admin');
    }
}
