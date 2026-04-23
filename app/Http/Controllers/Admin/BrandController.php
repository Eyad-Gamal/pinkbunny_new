<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->withTrashed()->orderBy('sort_order')->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:brands,name',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'banner'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active'      => 'boolean',
            'sort_order'     => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($request->name);
        if ($request->hasFile('logo'))   $validated['logo']   = $request->file('logo')->store('brands/logos', 'public');
        if ($request->hasFile('banner')) $validated['banner'] = $request->file('banner')->store('brands/banners', 'public');

        Brand::create($validated);
        return redirect()->route('admin.brands.index')->with('toast', 'Brand created.');
    }

    public function update(Request $request, string $id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name'           => "required|string|max:255|unique:brands,name,{$id}",
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'banner'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active'      => 'boolean',
            'sort_order'     => 'integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            if ($brand->logo) Storage::disk('public')->delete($brand->logo);
            $validated['logo'] = $request->file('logo')->store('brands/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($brand->banner) Storage::disk('public')->delete($brand->banner);
            $validated['banner'] = $request->file('banner')->store('brands/banners', 'public');
        }

        $brand->update($validated);
        return redirect()->route('admin.brands.index')->with('toast', 'Brand updated.');
    }

    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Cannot delete a brand that has products.');
        }
        $brand->delete();
        return back()->with('toast', 'Brand deleted.');
    }

    public function toggleActive(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update(['is_active' => !$brand->is_active]);
        return response()->json(['is_active' => $brand->is_active]);
    }
}
