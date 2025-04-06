@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Price List</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.price-lists.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="country_id" class="form-label">Country</label>
                    <select class="form-select" id="country_id" name="country_id">
                        <option value="" selected>Select a country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="currency_id" class="form-label">Currency</label>
                    <select class="form-select" id="currency_id" name="currency_id">
                        <option value="" selected>Select a currency</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <input type="number" class="form-control" id="priority" name="priority" required>
                <small class="text-muted">Higher numbers have higher priority when multiple price lists overlap</small>
            </div>
            
            <h5 class="mt-4">Products</h5>
            <div id="products-container">
                @foreach($products as $product)
                <div class="row mb-3 product-row">
                    <div class="col-md-6">
                        <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                        <label class="form-label">{{ $product->name }}</label>
                        <small class="text-muted d-block">Base Price: {{ number_format($product->base_price, 2) }}</small>
                    </div>
                    <div class="col-md-6">
                        <label for="price_{{ $product->id }}" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="price_{{ $product->id }}" 
                               name="products[{{ $loop->index }}][price]" 
                               value="{{ $product->base_price }}" required>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button type="submit" class="btn btn-primary">Save Price List</button>
            <a href="{{ route('admin.price-lists.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection