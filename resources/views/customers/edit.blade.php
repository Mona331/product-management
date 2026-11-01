@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

<h2 class="fw-bold mb-4">Edit Customer</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('customers.update', $customer->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name', $customer->name) }}">

                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $customer->phone) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email', $customer->email) }}">

                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Update Customer
            </button>

            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection