<?php

namespace Database\Seeders;

use App\Models\{Product, Review, User};
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            // Bunny Blush Palette
            ['email' => 'nour.elsayed@gmail.com',    'slug' => 'bunny-blush-palette',   'rating' => 5, 'comment' => 'Absolutely love this palette! The colors are so pigmented and blend beautifully. 💕'],
            ['email' => 'mariam.hassan@outlook.com', 'slug' => 'bunny-blush-palette',   'rating' => 5, 'comment' => 'Best blush I have ever tried. Lasts all day without fading.'],
            ['email' => 'dina.farouk@gmail.com',     'slug' => 'bunny-blush-palette',   'rating' => 4, 'comment' => 'Great quality, the packaging is so cute too!'],

            // Glow Serum Pro
            ['email' => 'sara.mahmoud@yahoo.com',    'slug' => 'glow-serum-pro',        'rating' => 5, 'comment' => 'My skin has never looked this bright. I use it every morning and the results are amazing.'],
            ['email' => 'hana.youssef@hotmail.com',  'slug' => 'glow-serum-pro',        'rating' => 4, 'comment' => 'Really good serum, noticed a difference in 2 weeks. Slightly sticky but worth it.'],
            ['email' => 'layla.karim@gmail.com',     'slug' => 'glow-serum-pro',        'rating' => 5, 'comment' => 'Incredible product. My dark spots are visibly lighter after one month.'],

            // Hydrating Cloud Cream
            ['email' => 'rania.ibrahim@gmail.com',   'slug' => 'hydrating-cloud-cream', 'rating' => 5, 'comment' => 'So lightweight yet so moisturizing. Perfect for my combination skin.'],
            ['email' => 'yasmine.adel@gmail.com',    'slug' => 'hydrating-cloud-cream', 'rating' => 4, 'comment' => 'Nice cream, absorbs quickly. Would love a bigger size.'],

            // Bond Repair Shampoo
            ['email' => 'layla.karim@gmail.com',     'slug' => 'bond-repair-shampoo',   'rating' => 5, 'comment' => 'My hair feels so much stronger after just 3 washes. Highly recommend!'],
            ['email' => 'mona.tarek@gmail.com',      'slug' => 'bond-repair-shampoo',   'rating' => 4, 'comment' => 'Good shampoo, smells amazing and leaves hair soft.'],

            // Velvet Body Lotion
            ['email' => 'dina.farouk@gmail.com',     'slug' => 'velvet-body-lotion',    'rating' => 5, 'comment' => 'The scent is divine and my skin stays moisturized all day. Will repurchase!'],
            ['email' => 'salma.nasser@gmail.com',    'slug' => 'velvet-body-lotion',    'rating' => 4, 'comment' => 'Very nice lotion, not greasy at all. Great for daily use.'],

            // Rose Lip Tint
            ['email' => 'nour.elsayed@gmail.com',    'slug' => 'rose-lip-tint',         'rating' => 4, 'comment' => 'Pretty color, very natural look. Lasts about 3-4 hours.'],
            ['email' => 'mariam.hassan@outlook.com', 'slug' => 'rose-lip-tint',         'rating' => 4, 'comment' => 'Love the shade! Feels comfortable on the lips.'],
        ];

        foreach ($reviews as $data) {
            $user    = User::where('email', $data['email'])->first();
            $product = Product::where('slug', $data['slug'])->first();

            if (!$user || !$product) continue;

            Review::firstOrCreate(
                ['product_id' => $product->id, 'user_id' => $user->id],
                [
                    'rating'      => $data['rating'],
                    'comment'     => $data['comment'],
                    'is_approved' => true,
                    'created_at'  => now()->subDays(rand(1, 20)),
                ]
            );
        }

        // Recalculate ratings for all products
        Product::all()->each(fn($p) => $p->recalculateRating());

        $this->command->info('✅ ' . count($reviews) . ' reviews seeded.');
    }
}
