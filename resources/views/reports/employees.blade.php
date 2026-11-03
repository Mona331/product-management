@extends('layouts.app')

@section('title', 'Employees Report')

@section('content')

<h2 class="mb-4">Employees Report</h2>

{{-- Search --}}
<form method="GET" action="{{ route('reports.employees') }}" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name, email or position..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>

            <a href="{{ route('reports.employees') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Summary --}}
<div class="row mb-4">

    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Employees</h6>
                <h3>{{ $employeesCount }}</h3>
            </div>
        </div>
    </div>

    @foreach($positionsCount as $position => $group)
    <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">{{ $position ?: 'Not Specified' }}</h6>
                <h3>{{ $group->count() }}</h3>
            </div>
        </div>
    </div>
    @endforeach

</div>

{{-- Employees Table --}}
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">Employees List</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Number of Sales</th>
                        <th>Total Sales Amount</th>
                        <th>Sales Details</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email ?? 'N/A' }}</td>
                        <td>{{ $employee->phone ?? 'N/A' }}</td>
                        <td>{{ $employee->position }}</td>

                        <td>{{ $employee->sales_count }}</td>

                        <td>
                            {{ number_format($employee->sales_sum_total_amount ?? 0, 2) }}
                        </td>

                        <td>
                            <a href="{{ route('reports.employees.sales', $employee->id) }}"
                                class="btn btn-sm btn-primary">
                                View Sales
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No employees found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <button onclick="window.print()" class="btn btn-dark">
            Print Report
        </button>

        <a href="{{ route('reports.employees.exportExcel', request()->query()) }}"
            class="btn btn-success">
            Export Excel
        </a>

    </div>
</div>

@endsection