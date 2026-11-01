@extends('layouts.app')

@section('title', 'Customers')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Customers</h2>
        <p class="text-muted">Manage your store customers</p>
    </div>

    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        + Add Customer
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
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td class="fw-semibold">{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->address }}</td>

                        <td>
                            <a href="{{ route('customers.edit', $customer->id) }}"
                                class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('customers.destroy', $customer->id) }}"
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
                            No customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection