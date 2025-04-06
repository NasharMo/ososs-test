@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Price List</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.price-lists.update', $priceList) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="country_id" class="form-label">Country</label>
                    <select class="form-select" id="country_id" name="country_id">
                        <option value="" selected>Select a country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" 
                                {{ $priceList->country_id == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="currency_id" class="form-label">Currency</label>
                    <select class="form-select" id="currency_id" name="currency_id">
                        <option value="" selected>Select a currency</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" 
                                {{ $priceList->currency_id == $currency->id ? 'selected' : '' }}>
                                {{ $currency->code }} - {{ $currency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" 
                           name="start_date" value="{{ old('start_date', $priceList->start_date) }}">
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" 
                           name="end_date" value="{{ old('end_date', $priceList->end_date) }}">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <input type="number" class="form-control" id="priority" 
                       name="priority" value="{{ old('priority', $priceList->priority) }}" required>
                <small class="text-muted">Higher numbers have higher priority</small>
            </div>
            
            <h5 class="mt-4">Products</h5>
            <div id="products-container">
                @foreach($products as $product)
                    @php
                        $priceListItem = $priceList->items->firstWhere('product_id', $product->id);
                        $price = $priceListItem ? $priceListItem->price : $product->base_price;
                    @endphp
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
                                   value="{{ old("products.{$loop->index}.price", $price) }}" required>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Price List
            </button>
            <a href="{{ route('admin.price-lists.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush