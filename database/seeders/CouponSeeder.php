<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::firstOrCreate(['code' => 'BUNNY10'], [
            'description'       => '10% off for newsletter subscribers',
            'type'              => 'percentage',
            'value'             => 10,
            'min_order_amount'  => 0,
            'is_active'         => true,
            'max_uses_per_user' => 1,
        ]);

        Coupon::firstOrCreate(['code' => 'WELCOME20'], [
            'description'       => '20% off first order',
            'type'              => 'percentage',
            'value'             => 20,
            'min_order_amount'  => 200,
            'is_active'         => true,
            'max_uses_per_user' => 1,
        ]);
    }
}
