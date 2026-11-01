@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<h2 class="fw-bold mb-4">Edit Product</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('products.update', $product->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $product->name) }}">
                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $product->price) }}">
                @error('price')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" class="form-control"
                    value="{{ old('stock_quantity', $product->stock_quantity) }}">
                @error('stock_quantity')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>

        </form>

    </div>
</div>

@endsection