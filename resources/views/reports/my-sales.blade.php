@extends('layouts.app')

@section('title', 'My Sales Report')

@section('content')

<div class="container">

    <h2 class="mb-4">My Sales Report</h2>

    <p class="text-muted">
        Employee: {{ $employee->name }}
    </p>

    {{-- Date Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employee.mySales') }}">
                <div class="row">

                    <div class="col-md-4">
                        <label>Start Date</label>
                        <input type="date" name="start_date"
                            value="{{ request('start_date') }}"
                            class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>End Date</label>
                        <input type="date" name="end_date"
                            value="{{ request('end_date') }}"
                            class="form-control">
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button class="btn btn-primary">Filter</button>
                        <a href="{{ route('employee.mySales') }}"
                            class="btn btn-secondary">Reset</a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div class="row mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Sales</h6>
                    <h3>{{ $totalSales }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Sales Amount</h6>
                    <h3>{{ number_format($totalAmount, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Sales History --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="mb-3">My Sales History</h5>

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
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td>{{ $sale->sales_time }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td>
                                <a href="{{ route('sales.show', $sale->id) }}"
                                    class="btn btn-sm btn-primary">
                                    Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No sales found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection