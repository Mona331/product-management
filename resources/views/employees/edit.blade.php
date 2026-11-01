@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')

<h2 class="fw-bold mb-4">Edit Employee</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('employees.update', $employee->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $employee->name) }}">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $employee->phone) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email', $employee->email) }}">

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Position</label>

                <select name="position" class="form-select">

                    @foreach(['Sales Employee', 'Manager', 'Admin'] as $position)
                    <option value="{{ $position }}"
                        {{ old('position', $employee->position) == $position ? 'selected' : '' }}>
                        {{ $position }}
                    </option>
                    @endforeach

                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Update Employee
            </button>

            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection
