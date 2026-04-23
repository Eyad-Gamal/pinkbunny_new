<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::where('is_active', true)->whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'required|string|max:255',
            'icon'       => 'nullable|string|max:50',
            'parent_id'  => 'nullable|exists:categories,id',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($request->name_en);
        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('toast', 'Category created.');
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $parents  = Category::where('is_active', true)->whereNull('parent_id')->where('id', '!=', $id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name_en'    => 'required|string|max:255',
            'name_ar'    => 'required|string|max:255',
            'icon'       => 'nullable|string|max:50',
            'parent_id'  => 'nullable|exists:categories,id',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('toast', 'Category updated.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has products.');
        }
        $category->delete();
        return back()->with('toast', 'Category deleted.');
    }
}
