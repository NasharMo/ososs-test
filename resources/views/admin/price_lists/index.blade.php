<!-- resources/views/admin/price_lists/index.blade.php -->
@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Price Lists</h5>
        <a href="{{ route('admin.price-lists.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Price List
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Country</th>
                        <th>Currency</th>
                        <th>Date Range</th>
                        <th>Priority</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($priceLists as $priceList)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>
                            @if($priceList->country)
                            <span class="fi fi-{{ strtolower($priceList->country->code) }}"></span>
                            {{ $priceList->country->name . " - " . $priceList->country->code }}
                            @else
                            —
                            @endif
                        </td>

                        <td>
                            {{ $priceList->currency->code ?? '—' }}
                        </td>

                        <td>
                            {{ $priceList->start_date ?? '—' }} -
                            {{ $priceList->end_date ?? '—' }}
                        </td>

                        <td>{{ $priceList->priority }}</td>
                        <td>{{ $priceList->items->count() }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.price-lists.edit', $priceList) }}"
                                    class="btn btn-sm btn-warning" title="Edit">
                                    Edit
                                </a>
                                <form action="{{ route('admin.price-lists.destroy', $priceList) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        title="Delete" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No price lists found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection

@push('styles')
<!-- Flag icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .fi {
        margin-right: 5px;
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
    }

    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endpush