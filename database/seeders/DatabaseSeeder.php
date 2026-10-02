<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── Foundation (must run first) ──────────────────────────────
            RoleSeeder::class,       // creates 'admin' and 'customer' roles
            AdminSeeder::class,      // admin@pinkbunny.com / Admin@123456

            // ── Catalogue ────────────────────────────────────────────────
            CategorySeeder::class,   // Makeup, Skincare, Haircare, Bodycare
            BrandSeeder::class,      // MAC, The Ordinary, CeraVe, Olaplex
            ProductSeeder::class,    // 6 products with images & flash sales
            HeroSlideSeeder::class,  // 3 hero banner slides
            CouponSeeder::class,     // BUNNY10, WELCOME20

            // ── Users & Activity ─────────────────────────────────────────
            CustomerSeeder::class,   // 10 Egyptian customers + addresses
            OrderSeeder::class,      // 14 orders across all statuses
            ReviewSeeder::class,     // 14 approved reviews + recalculates ratings
            VisitorLogSeeder::class, // 30 days of traffic for dashboard charts
        ]);
    }
}
