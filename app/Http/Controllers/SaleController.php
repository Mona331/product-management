<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // Display all sales
    public function index()
    {
        $sales = Sale::with(['customer', 'employee'])
            ->latest('id')
            ->get();

        return view('sales.index', compact('sales'));
    }

    // Show create form
    public function create()
    {
        $customers = Customer::all();
        $employees = Employee::all();
        $products = Product::all();

        return view('sales.create', compact(
            'customers',
            'employees',
            'products'
        ));
    }

    // Store a new sale
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'employee_id' => 'nullable|exists:employees,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:product,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {

            $total = 0;
            $items = [];

            foreach ($data['products'] as $item) {

                $product = Product::findOrFail($item['product_id']);

                $quantity = $item['quantity'];
                $price = $product->price;

                $total += $quantity * $price;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                ];
            }

            $sale = Sale::create([
                'customer_id' => $data['customer_id'],
                'employee_id' => $data['employee_id'] ?? null,
                'total_amount' => $total,
            ]);

            foreach ($items as $item) {
                $sale->saleItems()->create($item);
            }
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale created successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'employee', 'saleItems.product']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        return redirect()->route('sales.index');
    }

    public function update(Request $request, Sale $sale)
    {
        return redirect()->route('sales.index');
    }

    public function destroy(Sale $sale)
    {
        return redirect()->route('sales.index')
            ->with('error', 'Sales records cannot be deleted.');
    }
}
