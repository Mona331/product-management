@extends('layouts.app')

@section('title', 'Customers Report')

@section('content')

<h2 class="mb-4">Customers Report</h2>

{{-- Search --}}
<form method="GET" action="{{ route('reports.customers') }}" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name, email or phone..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>

            <a href="{{ route('reports.customers') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Summary --}}
<div class="row mb-4">

    <div class="col-md-6">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Customers</h6>
                <h3>{{ $customersCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Purchases</h6>
                <h3>{{ number_format($totalPurchases, 2) }}</h3>
            </div>
        </div>
    </div>

</div>

{{-- Customers Table --}}
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">Customers List</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Number of Sales</th>
                        <th>Total Purchases</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email ?? 'N/A' }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->sales_count }}</td>
                        <td>
                            {{ number_format($customer->sales_sum_total_amount ?? 0, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button onclick="window.print()" class="btn btn-dark">
            Print Report
        </button>
        <a href="{{ route('reports.customers.exportExcel', request()->query()) }}"
            class="btn btn-success">
            Export Excel
        </a>

    </div>
</div>

@endsection