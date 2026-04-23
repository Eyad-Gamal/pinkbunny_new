@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
    <div class="card" style="max-width: 600px;">
        <div class="card-header"><span class="card-title">{{ $category->name_en }}</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
                @csrf @method('PUT')
                <div class="form-group"><label class="form-label">Name (EN) *</label><input type="text" name="name_en" value="{{ $category->name_en }}" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Name (AR) *</label><input type="text" name="name_ar" value="{{ $category->name_ar }}" class="form-input" dir="rtl" required></div>
                <div class="form-group"><label class="form-label">Icon</label><input type="text" name="icon" value="{{ $category->icon }}" class="form-input"></div>
                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-input">
                        <option value="">None (top-level)</option>
                        @foreach($parents as $p)<option value="{{ $p->id }}" {{ $category->parent_id == $p->id ? 'selected' : '' }}>{{ $p->name_en }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" value="{{ $category->sort_order }}" class="form-input"></div>
                <label class="form-check" style="margin-bottom: 16px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}> Active
                </label>
                <div class="flex gap-1">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
