@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Product</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="base_price" class="form-label">Base Price</label>
                <input type="number" step="0.01" class="form-control" id="base_price" 
                       name="base_price" value="{{ old('base_price', $product->base_price) }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" 
                          rows="3">{{ old('description', $product->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </form>
    </div>
</div>
@endsection