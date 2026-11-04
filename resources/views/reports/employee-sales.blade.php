@extends('layouts.app')

@section('title', 'Employee Sales Details')

@section('content')

<h2 class="mb-4">Employee Sales Details</h2>

<div class="mb-4">
    <h5>Employee: {{ $employee->name }}</h5>
    <p class="text-muted">Position: {{ $employee->position }}</p>
</div>
{{-- Date Filter --}}
<form method="GET"
    action="{{ route('reports.employees.sales', $employee->id) }}"
    class="card card-body mb-4">

    <h5 class="mb-3">Filter Sales by Date</h5>

    <div class="row align-items-end">

        <div class="col-md-4">
            <label class="form-label">Start Date</label>
            <input type="date"
                name="start_date"
                class="form-control"
                value="{{ request('start_date') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">End Date</label>
            <input type="date"
                name="end_date"
                class="form-control"
                value="{{ request('end_date') }}">
        </div>

        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                Filter
            </button>

            <a href="{{ route('reports.employees.sales', $employee->id) }}"
                class="btn btn-secondary">
                Reset
            </a>
        </div>

    </div>
</form>

{{-- Summary --}}
<div class="row mb-4">

    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Total Sales</h6>
                <h3>{{ $salesCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-muted">Total Sales Amount</h6>
                <h3>{{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
    </div>

</div>

{{-- Sales Table --}}
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">Sales History</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>Sale ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Details</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>

                        <td>
                            {{ $sale->customer->name ?? 'N/A' }}
                        </td>

                        <td>{{ $sale->sales_time }}</td>

                        <td>
                            {{ number_format($sale->total_amount, 2) }}
                        </td>

                        <td>
                            @if(auth()->user()->role === 'hr')
                            <a href="{{ route('hr.sales.show', $sale->id) }}"
                                class="btn btn-sm btn-primary">
                                View Sale
                            </a>
                            @else
                            <a href="{{ route('sales.show', $sale->id) }}"
                                class="btn btn-sm btn-primary">
                                View Sale
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No sales found for this employee.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        <h4 class="mt-5 mb-3">Products Sold by Employee</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Total Quantity Sold</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($productsSold as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->total_quantity }}</td>
                        <td>{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            No products sold.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('reports.employees') }}"
            class="btn btn-secondary">
            Back to Employees Report
        </a>
        <a href="{{ route('reports.employees.sales.exportExcel', array_merge(
    ['employee' => $employee->id],
    request()->only(['start_date', 'end_date'])
)) }}"
            class="btn btn-success">
            Export Excel
        </a>
        <button onclick="window.print()" class="btn btn-dark">
            Print Report
        </button>

    </div>
</div>

@endsection