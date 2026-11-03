@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Sale Details</h2>
        <p class="text-muted">Sale #{{ $sale->id }}</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('sales.exportExcel', $sale->id) }}"
            class="btn btn-success">
            Export Excel
        </a>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            Back to Sales
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Sale Information</h5>

        <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>

        <p>
            <strong>Employee:</strong>
            {{ $sale->employee->name ?? 'Not assigned' }}
        </p>

        <p><strong>Sale Time:</strong> {{ $sale->sales_time }}</p>

        <p>
            <strong>Total Amount:</strong>
            {{ number_format($sale->total_amount, 2) }}
        </p>

    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Products</h5>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($sale->saleItems as $item)

                    <tr>
                        <td>{{ $item->product->name }}</td>

                        <td>{{ $item->quantity }}</td>

                        <td>{{ number_format($item->unit_price, 2) }}</td>

                        <td>
                            {{ number_format($item->quantity * $item->unit_price, 2) }}
                        </td>
                    </tr>

                    @endforeach

                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total</th>

                        <th>
                            {{ number_format($sale->total_amount, 2) }}
                        </th>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>
</div>

@endsection