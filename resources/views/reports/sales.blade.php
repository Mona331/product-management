@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Sales Report</h2>

    <div class="d-flex gap-2">

        <a href="{{ route('reports.sales.export', request()->query()) }}"
            class="btn btn-danger">
            Export PDF
        </a>
        <a href="{{ route('reports.sales.exportExcel', request()->query()) }}"
            class="btn btn-success">
            Export Excel
        </a>
        <button onclick="window.print()" class="btn btn-outline-primary">
            Print Report
        </button>

    </div>
</div>

{{-- Date Filters --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('reports.sales') }}" method="GET">
            <div class="row align-items-end">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary">
                        Generate Report
                    </button>

                    <a href="{{ route('reports.sales') }}"
                        class="btn btn-secondary">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Top Selling Products Today --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">
            Top Selling Products Today
        </h5>

        @if($topProductsToday->isNotEmpty())
        <div style="height: 300px;">
            <canvas id="topProductsChart"></canvas>
        </div>
        @else
        <p class="text-muted text-center py-4">
            No products sold today.
        </p>
        @endif

    </div>
</div>
{{-- Summary Cards --}}
<div class="row mb-4">

    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Sales</h6>
                <h3 class="fw-bold text-primary">
                    {{ number_format($totalSales, 2) }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Number of Sales</h6>
                <h3 class="fw-bold">
                    {{ $salesCount }}
                </h3>
            </div>
        </div>
    </div>

</div>

{{-- Sales Table --}}
<div class="card shadow-sm border-0">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Sales Details</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Sale ID</th>
                        <th>Customer</th>
                        <th>Employee</th>
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

                        <td>
                            {{ $sale->employee->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $sale->sales_time }}
                        </td>

                        <td class="fw-bold">
                            {{ number_format($sale->total_amount, 2) }}
                        </td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}"
                                class="btn btn-sm btn-primary">
                                Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-4">
                            No sales found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">
                            Total
                        </th>
                        <th>
                            {{ number_format($totalSales, 2) }}
                        </th>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>
@if($topProductsToday->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const productLabels = @json($topProductsToday -> pluck('product.name'));

    const productQuantities = @json($topProductsToday -> pluck('total_quantity'));

    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: productLabels,
            datasets: [{
                label: 'Quantity Sold',
                data: productQuantities,
                backgroundColor: '#1E3A8A',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endif
@endsection