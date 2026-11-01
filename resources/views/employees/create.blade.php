@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')

<h2 class="fw-bold mb-4">Add New Employee</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('employees.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name') }}">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}">

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Position</label>
                <select name="position" class="form-select">

                    <option value="Sales Employee"
                        {{ old('position', 'Sales Employee') == 'Sales Employee' ? 'selected' : '' }}>
                        Sales Employee
                    </option>

                    <option value="Manager"
                        {{ old('position') == 'Manager' ? 'selected' : '' }}>
                        Manager
                    </option>

                    <option value="Admin"
                        {{ old('position') == 'Admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Save Employee
            </button>

            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection