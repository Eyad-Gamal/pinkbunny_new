<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * 10 realistic Egyptian customers with addresses.
     * Password for all: Password@123
     */
    public function run(): void
    {
        $customers = [
            [
                'name'  => 'Nour El-Sayed',
                'email' => 'nour.elsayed@gmail.com',
                'phone' => '+201012345678',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Nour El-Sayed',
                    'phone'       => '+201012345678',
                    'street'      => '14 Tahrir Square, Apt 3',
                    'city'        => 'Cairo',
                    'governorate' => 'Cairo',
                ],
            ],
            [
                'name'  => 'Mariam Hassan',
                'email' => 'mariam.hassan@outlook.com',
                'phone' => '+201123456789',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Mariam Hassan',
                    'phone'       => '+201123456789',
                    'street'      => '7 Corniche El-Nil, Floor 2',
                    'city'        => 'Giza',
                    'governorate' => 'Giza',
                ],
            ],
            [
                'name'  => 'Sara Mahmoud',
                'email' => 'sara.mahmoud@yahoo.com',
                'phone' => '+201234567890',
                'address' => [
                    'label'       => 'Apartment',
                    'full_name'   => 'Sara Mahmoud',
                    'phone'       => '+201234567890',
                    'street'      => '22 El-Nasr Road, New Cairo',
                    'city'        => 'New Cairo',
                    'governorate' => 'Cairo',
                ],
            ],
            [
                'name'  => 'Layla Karim',
                'email' => 'layla.karim@gmail.com',
                'phone' => '+201098765432',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Layla Karim',
                    'phone'       => '+201098765432',
                    'street'      => '5 Smouha Street',
                    'city'        => 'Alexandria',
                    'governorate' => 'Alexandria',
                ],
            ],
            [
                'name'  => 'Dina Farouk',
                'email' => 'dina.farouk@gmail.com',
                'phone' => '+201187654321',
                'address' => [
                    'label'       => 'Work',
                    'full_name'   => 'Dina Farouk',
                    'phone'       => '+201187654321',
                    'street'      => '3 Makram Ebeid, Nasr City',
                    'city'        => 'Nasr City',
                    'governorate' => 'Cairo',
                ],
            ],
            [
                'name'  => 'Hana Youssef',
                'email' => 'hana.youssef@hotmail.com',
                'phone' => '+201276543210',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Hana Youssef',
                    'phone'       => '+201276543210',
                    'street'      => '9 El-Geish Road',
                    'city'        => 'Mansoura',
                    'governorate' => 'Dakahlia',
                ],
            ],
            [
                'name'  => 'Rania Ibrahim',
                'email' => 'rania.ibrahim@gmail.com',
                'phone' => '+201365432109',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Rania Ibrahim',
                    'phone'       => '+201365432109',
                    'street'      => '18 Port Said Street',
                    'city'        => 'Zagazig',
                    'governorate' => 'Sharqia',
                ],
            ],
            [
                'name'  => 'Yasmine Adel',
                'email' => 'yasmine.adel@gmail.com',
                'phone' => '+201454321098',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Yasmine Adel',
                    'phone'       => '+201454321098',
                    'street'      => '6 October City, District 3',
                    'city'        => '6th of October',
                    'governorate' => 'Giza',
                ],
            ],
            [
                'name'  => 'Mona Tarek',
                'email' => 'mona.tarek@gmail.com',
                'phone' => '+201543210987',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Mona Tarek',
                    'phone'       => '+201543210987',
                    'street'      => '11 El-Horreya Avenue',
                    'city'        => 'Tanta',
                    'governorate' => 'Gharbia',
                ],
            ],
            [
                'name'  => 'Salma Nasser',
                'email' => 'salma.nasser@gmail.com',
                'phone' => '+201632109876',
                'address' => [
                    'label'       => 'Home',
                    'full_name'   => 'Salma Nasser',
                    'phone'       => '+201632109876',
                    'street'      => '25 El-Azhar Street',
                    'city'        => 'Assiut',
                    'governorate' => 'Assiut',
                ],
            ],
        ];

        foreach ($customers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'phone'             => $data['phone'],
                    'password'          => Hash::make('Password@123'),
                    'email_verified_at' => now(),
                    'is_active'         => true,
                    'preferred_language' => 'ar',
                ]
            );

            $user->assignRole('customer');

            // Create address only if user has none
            if ($user->addresses()->count() === 0) {
                $user->addresses()->create(array_merge($data['address'], [
                    'country'    => 'Egypt',
                    'is_default' => true,
                ]));
            }
        }

        $this->command->info('✅ 10 customers seeded.');
    }
}
