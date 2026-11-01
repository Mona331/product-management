@extends('layouts.app')

@section('title', 'Sales')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Sales</h2>
        <p class="text-muted">Manage your store sales</p>
    </div>

    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        + New Sale
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Sale ID</th>
                        <th>Customer</th>
                        <th>Employee</th>
                        <th>Total Amount</th>
                        <th>Sale Time</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>

                        <td>
                            {{ $sale->customer->name ?? 'Unknown' }}
                        </td>

                        <td>
                            {{ $sale->employee->name ?? 'Not assigned' }}
                        </td>

                        <td class="fw-semibold">
                            {{ number_format($sale->total_amount, 2) }}
                        </td>

                        <td>
                            {{ $sale->sales_time }}
                        </td>

                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}"
                                class="btn btn-sm btn-info text-white">
                                Details
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No sales found.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection