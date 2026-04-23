<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title_en'      => 'Glow Like Never Before ✨',
                'title_ar'      => 'تألقي كما لم يسبق لك ✨',
                'subtitle_en'   => 'Discover our curated collection of premium skincare and makeup essentials.',
                'subtitle_ar'   => 'اكتشفي مجموعتنا المختارة من منتجات العناية بالبشرة والمكياج الفاخرة.',
                'badge_text'    => '✨ New Arrivals',
                'button_text'   => 'Shop Now',
                'button_link'   => '/products',
                'bg_color_from' => '#BAE6FD',
                'bg_color_to'   => '#FFB5C5',
                'text_color'    => '#0F172A',
                'image_1'       => 'https://images.unsplash.com/photo-1599305090598-fe179d501227?auto=format&fit=crop&w=600&q=80',
                'image_2'       => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=80',
                'image_3'       => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=80',
                'is_active'     => true,
                'sort_order'    => 0,
            ],
            [
                'title_en'      => 'Summer Beauty Sale 🌸',
                'title_ar'      => 'تخفيضات جمال الصيف 🌸',
                'subtitle_en'   => 'Up to 40% off on selected skincare and beauty products.',
                'subtitle_ar'   => 'خصم يصل إلى 40% على منتجات العناية بالبشرة والجمال المختارة.',
                'badge_text'    => '🔥 Hot Deals',
                'button_text'   => 'View Deals',
                'button_link'   => '/products?on_sale=1',
                'bg_color_from' => '#FECDD3',
                'bg_color_to'   => '#FDE68A',
                'text_color'    => '#1E1B4B',
                'image_1'       => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=600&q=80',
                'image_2'       => 'https://images.unsplash.com/photo-1571875257727-256c39da42af?auto=format&fit=crop&w=800&q=80',
                'image_3'       => 'https://images.unsplash.com/photo-1631730486789-6d6f6418370d?auto=format&fit=crop&w=600&q=80',
                'is_active'     => true,
                'sort_order'    => 1,
            ],
            [
                'title_en'      => 'Self Care Essentials 🐰',
                'title_ar'      => 'أساسيات العناية الذاتية 🐰',
                'subtitle_en'   => 'Because you deserve the best. Premium bodycare and haircare for every routine.',
                'subtitle_ar'   => 'لأنك تستحقين الأفضل. منتجات عناية بالجسم والشعر فاخرة لكل روتين.',
                'badge_text'    => '💕 New Collection',
                'button_text'   => 'Explore',
                'button_link'   => '/products',
                'bg_color_from' => '#DDD6FE',
                'bg_color_to'   => '#FBCFE8',
                'text_color'    => '#1E1B4B',
                'image_1'       => 'https://images.unsplash.com/photo-1583241800698-d1f73d4e7f3c?auto=format&fit=crop&w=600&q=80',
                'image_2'       => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=80',
                'image_3'       => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=600&q=80',
                'is_active'     => true,
                'sort_order'    => 2,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}
