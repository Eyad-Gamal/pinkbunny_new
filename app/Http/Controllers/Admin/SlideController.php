<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::ordered()->paginate(20);
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slides.form', ['slide' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title_en'      => 'required|string|max:255',
            'title_ar'      => 'nullable|string|max:255',
            'subtitle_en'   => 'nullable|string|max:500',
            'subtitle_ar'   => 'nullable|string|max:500',
            'badge_text'    => 'nullable|string|max:100',
            'button_text'   => 'required|string|max:100',
            'button_link'   => 'required|string|max:255',
            'bg_color_from' => 'required|string|max:20',
            'bg_color_to'   => 'required|string|max:20',
            'text_color'    => 'required|string|max:20',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Handle image uploads
        foreach (['image_1', 'image_2', 'image_3'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('slides', 'public');
            }
        }

        HeroSlide::create($data);

        return redirect()->route('admin.slides.index')->with('toast', 'Slide created successfully!');
    }

    public function edit(string $id)
    {
        $slide = HeroSlide::findOrFail($id);
        return view('admin.slides.form', compact('slide'));
    }

    public function update(Request $request, string $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $data = $request->validate([
            'title_en'      => 'required|string|max:255',
            'title_ar'      => 'nullable|string|max:255',
            'subtitle_en'   => 'nullable|string|max:500',
            'subtitle_ar'   => 'nullable|string|max:500',
            'badge_text'    => 'nullable|string|max:100',
            'button_text'   => 'required|string|max:100',
            'button_link'   => 'required|string|max:255',
            'bg_color_from' => 'required|string|max:20',
            'bg_color_to'   => 'required|string|max:20',
            'text_color'    => 'required|string|max:20',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Handle image uploads — replace existing files
        foreach (['image_1', 'image_2', 'image_3'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if it's a stored file (not a URL)
                if ($slide->$field && !str_starts_with($slide->$field, 'http')) {
                    Storage::disk('public')->delete($slide->$field);
                }
                $data[$field] = $request->file($field)->store('slides', 'public');
            }
        }

        $slide->update($data);

        return redirect()->route('admin.slides.index')->with('toast', 'Slide updated!');
    }

    public function destroy(string $id)
    {
        $slide = HeroSlide::findOrFail($id);

        // Clean up stored images
        foreach (['image_1', 'image_2', 'image_3'] as $field) {
            if ($slide->$field && !str_starts_with($slide->$field, 'http')) {
                Storage::disk('public')->delete($slide->$field);
            }
        }

        $slide->delete();

        return redirect()->route('admin.slides.index')->with('toast', 'Slide deleted!');
    }
}
