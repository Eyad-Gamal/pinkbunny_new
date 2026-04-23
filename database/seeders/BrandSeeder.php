<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'MAC Cosmetics',
                'slug' => 'mac-cosmetics',
                'logo' => 'https://dummyimage.com/180x80/ffffff/ff8fab&text=MAC',
                'banner' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=1600&q=80',
                'description_en' => 'Professional makeup staples with high-impact colors and iconic finishes.',
                'description_ar' => 'براند مكياج احترافي بألوان قوية ولمسات نهائية مميزة.',
                'is_active' => true,
            ],
            [
                'name' => 'The Ordinary',
                'slug' => 'the-ordinary',
                'logo' => 'https://dummyimage.com/180x80/ffffff/ff8fab&text=ORDINARY',
                'banner' => 'https://images.unsplash.com/photo-1556228578-dd6b8e2d42a3?auto=format&fit=crop&w=1600&q=80',
                'description_en' => 'Ingredient-led skincare loved for effective routines and transparent formulas.',
                'description_ar' => 'عناية بالبشرة تعتمد على المكونات الفعالة وتركيبات واضحة وموثوقة.',
                'is_active' => true,
            ],
            [
                'name' => 'CeraVe',
                'slug' => 'cerave',
                'logo' => 'https://dummyimage.com/180x80/ffffff/7cb8ff&text=CeraVe',
                'banner' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=1600&q=80',
                'description_en' => 'Barrier-friendly essentials for dry, sensitive, and everyday skin needs.',
                'description_ar' => 'أساسيات لطيفة تدعم حاجز البشرة وتناسب الاستخدام اليومي.',
                'is_active' => true,
            ],
            [
                'name' => 'Olaplex',
                'slug' => 'olaplex',
                'logo' => 'https://dummyimage.com/180x80/ffffff/ff8fab&text=OLAPLEX',
                'banner' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=1600&q=80',
                'description_en' => 'Repair-focused haircare that helps damaged hair feel stronger and softer.',
                'description_ar' => 'عناية بالشعر تركز على الإصلاح والقوة والنعومة.',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(['slug' => $brand['slug']], $brand);
        }
    }
}
