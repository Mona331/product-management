@extends('layouts.app')

@section('title', 'New Sale')

@section('content')

<h2 class="fw-bold mb-4">Create New Sale</h2>

<form action="{{ route('sales.store') }}" method="POST">
    @csrf

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Sale Information</h5>

            <div class="mb-3">
                <label class="form-label">Customer</label>

                <select name="customer_id" class="form-select" required>
                    <option value="">Select Customer</option>

                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                    @endforeach
                </select>

                @error('customer_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Employee</label>

                <select name="employee_id" class="form-select">
                    <option value="">Select Employee (Optional)</option>

                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}"
                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>


    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold">Sale Products</h5>

                <button type="button" class="btn btn-success btn-sm" id="addProduct">
                    + Add Product
                </button>
            </div>

            <div id="productsContainer">

                <div class="row g-3 mb-3 product-row">

                    <div class="col-md-6">
                        <label class="form-label">Product</label>

                        <select name="products[0][product_id]"
                            class="form-select product-select" required>

                            <option value="">Select Product</option>

                            @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                data-price="{{ $product->price }}">
                                {{ $product->name }} - {{ number_format($product->price, 2) }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>

                        <input type="number"
                            name="products[0][quantity]"
                            class="form-control quantity-input"
                            min="1"
                            value="1"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Action</label>

                        <button type="button"
                            class="btn btn-danger remove-product w-100"
                            disabled>
                            Remove
                        </button>
                    </div>

                </div>

            </div>

            @error('products')
            <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>
    </div>


    <button type="submit" class="btn btn-primary">
        Save Sale
    </button>

    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        Cancel
    </a>

</form>


<script>
    let productIndex = 1;

    document.getElementById('addProduct').addEventListener('click', function() {

        const container = document.getElementById('productsContainer');

        const row = document.createElement('div');

        row.className = 'row g-3 mb-3 product-row';

        row.innerHTML = `
        <div class="col-md-6">
            <label class="form-label">Product</label>

            <select name="products[${productIndex}][product_id]"
                    class="form-select product-select" required>

                <option value="">Select Product</option>

                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} - {{ number_format($product->price, 2) }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Quantity</label>

            <input type="number"
                   name="products[${productIndex}][quantity]"
                   class="form-control quantity-input"
                   min="1"
                   value="1"
                   required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Action</label>

            <button type="button"
                    class="btn btn-danger remove-product w-100">
                Remove
            </button>
        </div>
    `;

        container.appendChild(row);

        productIndex++;

        updateRemoveButtons();
    });


    document.getElementById('productsContainer').addEventListener('click', function(event) {

        if (event.target.classList.contains('remove-product')) {

            event.target.closest('.product-row').remove();

            updateRemoveButtons();
        }

    });


    function updateRemoveButtons() {

        const rows = document.querySelectorAll('.product-row');

        rows.forEach(function(row) {

            const button = row.querySelector('.remove-product');

            button.disabled = rows.length === 1;

        });

    }
</script>

@endsection