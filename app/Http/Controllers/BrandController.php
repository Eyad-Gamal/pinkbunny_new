<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('brands.index', compact('brands'));
    }

    public function show(Request $request, Brand $brand): View
    {
        abort_unless($brand->is_active, 404);

        $productsQuery = $brand->products()->active()->with(['brand', 'category'])
            ->when($request->filled('categories'), fn ($query) => $query->whereIn('category_id', (array) $request->input('categories', [])))
            ->when($request->boolean('on_sale'), fn ($query) => $query->whereNotNull('sale_price'))
            ->when($request->boolean('in_stock'), fn ($query) => $query->where('stock_quantity', '>', 0));

        $products = match ($request->string('sort')->toString()) {
            'price_asc' => $productsQuery->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $productsQuery->orderByRaw('COALESCE(sale_price, price) desc'),
            'rating' => $productsQuery->orderByDesc('average_rating'),
            'newest' => $productsQuery->latest(),
            default => $productsQuery->orderByDesc('total_reviews'),
        };

        return view('brands.show', [
            'brand' => $brand->loadCount('products'),
            'products' => $products->paginate(15)->withQueryString(),
            'categories' => Category::orderBy('name_en')->get(),
        ]);
    }
}
