@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')

<h2 class="fw-bold mb-4">Add New Employee</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        ```
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name') }}" required>

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone') }}">

                @error('phone')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}" required>

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Position</label>
                <select name="position" class="form-select" required>
                    <option value="Sales Employee"
                        {{ old('position', 'Sales Employee') == 'Sales Employee' ? 'selected' : '' }}>
                        Sales Employee
                    </option>

                    <option value="Manager"
                        {{ old('position') == 'Manager' ? 'selected' : '' }}>
                        Manager
                    </option>
                </select>

                @error('position')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <hr>

            <h5 class="mb-3">Login Account Information</h5>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                    required>

                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Save Employee
            </button>

            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
    ```

</div>

@endsection