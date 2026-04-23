<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category, Brand};
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()
            ->with(['brand', 'category'])
            ->filter($request->only([
                'search', 'categories', 'brands',
                'min_price', 'max_price', 'min_rating',
                'on_sale', 'in_stock',
            ]));

        $products = match($request->sort) {
            'price_asc'  => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'rating'     => $products->orderByDesc('average_rating'),
            'newest'     => $products->latest(),
            default      => $products->orderByDesc('total_sold'),
        };

        return view('products.index', [
            'products'   => $products->paginate(16)->withQueryString(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'brands'     => Brand::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['brand', 'category', 'reviews' => fn($q) => $q->where('is_approved', true)->with('user')])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->where(fn($q) => $q->where('brand_id', $product->brand_id)->orWhere('category_id', $product->category_id))
            ->inStock()
            ->limit(6)
            ->get();

        $userReview = auth()->check()
            ? $product->reviews()->where('user_id', auth()->id())->first()
            : null;

        // Star breakdown: count of approved reviews per rating (1–5)
        $ratingCounts = $product->reviews->where('is_approved', true)->groupBy('rating')->map->count();
        $ratings = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0])->merge($ratingCounts);

        return view('products.show', compact('product', 'related', 'userReview', 'ratings'));
    }
}
