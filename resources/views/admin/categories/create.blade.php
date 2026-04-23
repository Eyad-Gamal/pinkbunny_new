@extends('layouts.admin')
@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
    <div class="card" style="max-width: 600px;">
        <div class="card-header"><span class="card-title">New Category</span></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group"><label class="form-label">Name (EN) *</label><input type="text" name="name_en" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Name (AR) *</label><input type="text" name="name_ar" class="form-input" dir="rtl" required></div>
                <div class="form-group"><label class="form-label">Icon (emoji)</label><input type="text" name="icon" class="form-input" placeholder="💄"></div>
                <div class="form-group">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-input">
                        <option value="">None (top-level)</option>
                        @foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->name_en }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" value="0" class="form-input"></div>
                <input type="hidden" name="is_active" value="1">
                <div class="flex gap-1">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
