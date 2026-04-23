@extends('layouts.admin')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" style="max-width: 900px;">
        @csrf @method('PUT')
        @include('admin.products._form', ['product' => $product])
        <div class="flex gap-1 mt-3">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection
