<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_en' => 'Makeup',   'name_ar' => 'مكياج',             'slug' => 'makeup',   'icon' => '💄', 'sort_order' => 1],
            ['name_en' => 'Skincare', 'name_ar' => 'العناية بالبشرة',  'slug' => 'skincare', 'icon' => '🫧', 'sort_order' => 2],
            ['name_en' => 'Haircare', 'name_ar' => 'العناية بالشعر',   'slug' => 'haircare', 'icon' => '💇', 'sort_order' => 3],
            ['name_en' => 'Bodycare', 'name_ar' => 'العناية بالجسم',   'slug' => 'bodycare', 'icon' => '🧴', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
