@extends('layouts.app')

@section('title', 'Products Report')

@section('content')

<h2 class="mb-4">Products Report</h2>

{{-- Search --}}
<form method="GET" action="{{ route('reports.products') }}" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by product name..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">
                Search
            </button>

            <a href="{{ route('reports.products') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Summary Cards --}}
<div class="row mb-4">

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Products</h6>
                <h3>{{ $productsCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Stock</h6>
                <h3>{{ $totalStock }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Inventory Value</h6>
                <h3>{{ number_format($inventoryValue, 2) }}</h3>
            </div>
        </div>
    </div>

</div>

{{-- Products Table --}}
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">Products List</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock Quantity</th>
                        <th>Total Value</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description ?? 'N/A' }}</td>
                        <td>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>
                            {{ number_format($product->price * $product->stock_quantity, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No products found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button onclick="window.print()" class="btn btn-dark">
            Print Report
        </button>
        <a href="{{ route('reports.products.exportExcel', request()->query()) }}"
            class="btn btn-success">
            Export Excel
        </a>

    </div>
</div>

@endsection