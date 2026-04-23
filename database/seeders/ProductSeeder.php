<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $makeup   = Category::where('slug', 'makeup')->firstOrFail();
        $skincare = Category::where('slug', 'skincare')->firstOrFail();
        $haircare = Category::where('slug', 'haircare')->firstOrFail();
        $bodycare = Category::where('slug', 'bodycare')->firstOrFail();

        $mac      = Brand::where('slug', 'mac-cosmetics')->firstOrFail();
        $ordinary = Brand::where('slug', 'the-ordinary')->firstOrFail();
        $cerave   = Brand::where('slug', 'cerave')->firstOrFail();
        $olaplex  = Brand::where('slug', 'olaplex')->firstOrFail();

        $products = [
            [
                'name_en'             => 'Bunny Blush Palette',
                'name_ar'             => 'باليت بلاشر باني',
                'slug'                => 'bunny-blush-palette',
                'description_en'      => 'A soft-focus blush palette with peach, rose, and berry tones.',
                'description_ar'      => 'باليت بلاشر ناعم بدرجات خوخي ووردي وتوتي لإطلالة حيوية.',
                'ingredients_en'      => 'Mica, Silica, Jojoba Oil, Vitamin E',
                'how_to_use_en'       => 'Sweep lightly across cheeks and layer for more color.',
                'how_to_use_ar'       => 'مرريه بخفة على الخدود ويمكنك زيادة الطبقات حسب الرغبة.',
                'price'               => 299.00,
                'sale_price'          => 199.00,
                'stock_quantity'      => 40,
                'low_stock_threshold' => 5,
                'category_id'         => $makeup->id,
                'brand_id'            => $mac->id,
                'is_featured'         => true,
                'is_flash_sale'       => true,
                'flash_sale_ends_at'  => now()->addDays(3),
                'average_rating'      => 4.8,
                'total_reviews'       => 132,
                'total_sold'          => 88,
                'images'              => [
                    'https://images.unsplash.com/photo-1631730486789-6d6f6418370d?auto=format&fit=crop&w=900&q=80',
                    'https://images.unsplash.com/photo-1583241800698-d1f73d4e7f3c?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            [
                'name_en'             => 'Glow Serum Pro',
                'name_ar'             => 'سيروم جلو برو',
                'slug'                => 'glow-serum-pro',
                'description_en'      => 'Advanced vitamin C serum for radiant skin and brighter tone.',
                'description_ar'      => 'سيروم فيتامين سي متقدم لإشراقة أوضح وتوحيد لون البشرة.',
                'ingredients_en'      => 'Vitamin C, Hyaluronic Acid, Niacinamide, Aloe Vera',
                'how_to_use_en'       => 'Use 2-3 drops after cleansing before moisturizer.',
                'how_to_use_ar'       => 'استخدمي 2-3 قطرات بعد التنظيف وقبل المرطب.',
                'price'               => 449.00,
                'sale_price'          => 349.00,
                'stock_quantity'      => 27,
                'low_stock_threshold' => 5,
                'category_id'         => $skincare->id,
                'brand_id'            => $ordinary->id,
                'is_featured'         => true,
                'is_flash_sale'       => true,
                'flash_sale_ends_at'  => now()->addDays(2),
                'average_rating'      => 4.7,
                'total_reviews'       => 221,
                'total_sold'          => 156,
                'images'              => [
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            [
                'name_en'             => 'Hydrating Cloud Cream',
                'name_ar'             => 'كريم كلاود المرطب',
                'slug'                => 'hydrating-cloud-cream',
                'description_en'      => 'A rich cream that locks in moisture without heaviness.',
                'description_ar'      => 'كريم غني يمنح ترطيبًا عميقًا بدون إحساس دهني ثقيل.',
                'ingredients_en'      => 'Ceramides, Glycerin, Squalane',
                'how_to_use_en'       => 'Apply morning and night as the final skincare step.',
                'how_to_use_ar'       => 'يوضع صباحًا ومساءً كآخر خطوة في روتين البشرة.',
                'price'               => 380.00,
                'sale_price'          => null,
                'stock_quantity'      => 18,
                'low_stock_threshold' => 5,
                'category_id'         => $skincare->id,
                'brand_id'            => $cerave->id,
                'is_featured'         => true,
                'average_rating'      => 4.5,
                'total_reviews'       => 76,
                'total_sold'          => 42,
                'images'              => [
                    'https://images.unsplash.com/photo-1617897903246-719242758050?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            [
                'name_en'             => 'Bond Repair Shampoo',
                'name_ar'             => 'شامبو بوند ريبير',
                'slug'                => 'bond-repair-shampoo',
                'description_en'      => 'Repairing shampoo for stressed and damaged hair.',
                'description_ar'      => 'شامبو مخصص لإصلاح الشعر المتضرر واستعادة نعومته.',
                'ingredients_en'      => 'Sunflower Seed Oil, Coconut Oil, Keratin',
                'how_to_use_en'       => 'Massage into wet hair, rinse, and follow with conditioner.',
                'how_to_use_ar'       => 'دلكي على الشعر المبلل ثم اشطفيه واتّبعيه بالبلسم.',
                'price'               => 520.00,
                'sale_price'          => 449.00,
                'stock_quantity'      => 15,
                'low_stock_threshold' => 5,
                'category_id'         => $haircare->id,
                'brand_id'            => $olaplex->id,
                'is_featured'         => true,
                'is_flash_sale'       => true,
                'flash_sale_ends_at'  => now()->addHours(36),
                'average_rating'      => 4.6,
                'total_reviews'       => 89,
                'total_sold'          => 67,
                'images'              => [
                    'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            [
                'name_en'             => 'Velvet Body Lotion',
                'name_ar'             => 'لوشن فيلفيت للجسم',
                'slug'                => 'velvet-body-lotion',
                'description_en'      => 'Smooth daily body lotion with a soft powdery finish.',
                'description_ar'      => 'لوشن يومي ناعم يمنح ترطيبًا ولمسة مخملية للجسم.',
                'ingredients_en'      => 'Shea Butter, Hyaluronic Acid, Vitamin B5',
                'how_to_use_en'       => 'Massage generously onto clean skin.',
                'how_to_use_ar'       => 'يدلك على بشرة نظيفة بكمية مناسبة.',
                'price'               => 265.00,
                'sale_price'          => null,
                'stock_quantity'      => 32,
                'low_stock_threshold' => 5,
                'category_id'         => $bodycare->id,
                'brand_id'            => $cerave->id,
                'average_rating'      => 4.4,
                'total_reviews'       => 51,
                'total_sold'          => 35,
                'images'              => [
                    'https://images.unsplash.com/photo-1580870069867-74c57ee1bb07?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            [
                'name_en'             => 'Rose Lip Tint',
                'name_ar'             => 'تينت روز للشفايف',
                'slug'                => 'rose-lip-tint',
                'description_en'      => 'A buildable lip tint for glossy rosy color.',
                'description_ar'      => 'تينت قابل للبناء يمنح لونًا ورديًا لامعًا للشفاه.',
                'ingredients_en'      => 'Castor Oil, Shea Butter, Vitamin E',
                'how_to_use_en'       => 'Tap on lips and blend with fingertips.',
                'how_to_use_ar'       => 'ضعيه على الشفاه وادمجيه بأطراف الأصابع.',
                'price'               => 210.00,
                'sale_price'          => 180.00,
                'stock_quantity'      => 0,
                'low_stock_threshold' => 5,
                'category_id'         => $makeup->id,
                'brand_id'            => $mac->id,
                'average_rating'      => 4.2,
                'total_reviews'       => 34,
                'total_sold'          => 28,
                'images'              => [
                    'https://images.unsplash.com/photo-1631214500115-598fc2cb8f39?auto=format&fit=crop&w=900&q=80',
                ],
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
