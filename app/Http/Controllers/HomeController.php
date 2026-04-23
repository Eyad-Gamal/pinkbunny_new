<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Category, HeroSlide, Product};
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        if (!Schema::hasTable('products') || !Schema::hasTable('categories') || !Schema::hasTable('brands')) {
            return view('home.index', [
                'slides'            => collect(),
                'featuredProducts'  => collect(),
                'flashSaleProducts' => collect(),
                'categories'        => collect(),
                'brands'            => collect(),
            ]);
        }

        return view('home.index', [
            'slides'            => Schema::hasTable('hero_slides') ? HeroSlide::active()->ordered()->get() : collect(),
            'featuredProducts'  => Product::with(['brand', 'category'])->featured()->take(8)->get(),
            'flashSaleProducts' => Product::with(['brand', 'category'])->flashSale()->take(6)->get(),
            'categories'        => Category::where('is_active', true)->withCount('products')->orderBy('sort_order')->take(8)->get(),
            'brands'            => Brand::where('is_active', true)->orderBy('sort_order')->take(8)->get(),
        ]);
    }
}
