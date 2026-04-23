<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Brand, Category};
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::withTrashed()
            ->with(['brand', 'category'])
            ->when($request->search, fn($q, $s) =>
                $q->where('name_en', 'like', "%{$s}%")->orWhere('name_ar', 'like', "%{$s}%")
            )
            ->when($request->brand_id,    fn($q) => $q->where('brand_id', $request->brand_id))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->status === 'active',       fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive',     fn($q) => $q->where('is_active', false))
            ->when($request->status === 'low_stock',    fn($q) => $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0))
            ->when($request->status === 'out_of_stock', fn($q) => $q->where('stock_quantity', 0))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $brands     = Brand::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function create()
    {
        $brands     = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name_en')->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en'             => 'required|string|max:255',
            'name_ar'             => 'required|string|max:255',
            'description_en'      => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'sale_price'          => 'nullable|numeric|min:0',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'brand_id'            => 'required|exists:brands,id',
            'category_id'         => 'required|exists:categories,id',
            'images'              => 'required|array|min:1|max:5',
            'images.*'            => 'image|mimes:jpeg,png,jpg,webp|max:3072',
            'ingredients_en'      => 'nullable|string',
            'ingredients_ar'      => 'nullable|string',
            'how_to_use_en'       => 'nullable|string',
            'how_to_use_ar'       => 'nullable|string',
            'is_featured'         => 'boolean',
            'is_flash_sale'       => 'boolean',
            'flash_sale_ends_at'  => 'nullable|date|after:now',
            'is_active'           => 'boolean',
        ]);

        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imagePaths[] = $image->store('products', 'public');
        }

        $validated['slug']   = Str::slug($request->name_en) . '-' . Str::random(6);
        $validated['images'] = $imagePaths;

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('toast', 'Product created successfully.');
    }

    public function edit(string $id)
    {
        $product    = Product::withTrashed()->findOrFail($id);
        $brands     = Brand::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('name_en')->get();
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name_en'             => 'required|string|max:255',
            'name_ar'             => 'required|string|max:255',
            'description_en'      => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'sale_price'          => 'nullable|numeric|min:0',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'brand_id'            => 'required|exists:brands,id',
            'category_id'         => 'required|exists:categories,id',
            'new_images'          => 'nullable|array|max:5',
            'new_images.*'        => 'image|mimes:jpeg,png,jpg,webp|max:3072',
            'remove_images'       => 'nullable|array',
            'ingredients_en'      => 'nullable|string',
            'ingredients_ar'      => 'nullable|string',
            'how_to_use_en'       => 'nullable|string',
            'how_to_use_ar'       => 'nullable|string',
            'is_featured'         => 'boolean',
            'is_flash_sale'       => 'boolean',
            'flash_sale_ends_at'  => 'nullable|date',
            'is_active'           => 'boolean',
        ]);

        $existingImages = $product->images ?? [];

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $img) {
                Storage::disk('public')->delete($img);
                $existingImages = array_filter($existingImages, fn($i) => $i !== $img);
            }
        }

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $existingImages[] = $image->store('products', 'public');
            }
        }

        $validated['images'] = array_values($existingImages);
        unset($validated['new_images'], $validated['remove_images']);
        $product->update($validated);

        if ($product->is_low_stock) {
            $admins = \App\Models\User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($product));
            }
        }

        return redirect()->route('admin.products.index')->with('toast', 'Product updated.');
    }

    public function destroy(string $id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('toast', 'Product deleted.');
    }

    public function restore(string $id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return back()->with('toast', 'Product restored.');
    }

    public function toggleActive(string $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['is_active' => $product->is_active]);
    }
}
