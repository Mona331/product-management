@extends('layouts.app')

@section('title', 'Employee Sales Details')

@section('content')

<h2 class="mb-4">Employee Sales Details</h2>

<div class="mb-4">
    <h5>Employee: {{ $employee->name }}</h5>
    <p class="text-muted">Position: {{ $employee->position }}</p>
</div>

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
                            <a href="{{ route('sales.show', $sale->id) }}"
                                class="btn btn-sm btn-primary">
                                View Sale
                            </a>
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

        <a href="{{ route('reports.employees') }}"
            class="btn btn-secondary">
            Back to Employees Report
        </a>

        <button onclick="window.print()" class="btn btn-dark">
            Print Report
        </button>

    </div>
</div>

@endsection