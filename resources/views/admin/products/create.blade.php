@extends('layouts.admin')
@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" style="max-width: 900px;">
        @csrf
        @include('admin.products._form')
        <div class="flex gap-1 mt-3">
            <button type="submit" class="btn btn-primary">Create Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection
