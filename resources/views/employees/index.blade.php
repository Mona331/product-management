@extends('layouts.app')

@section('title', 'Employees')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Employees</h2>
        <p class="text-muted">Manage your store employees</p>
    </div>

    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        + Add Employee
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td class="fw-semibold">{{ $employee->name }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->position }}</td>

                        <td>
                            <a href="{{ route('employees.edit', $employee->id) }}"
                                class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('employees.destroy', $employee->id) }}"
                                method="POST" class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No employees found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection