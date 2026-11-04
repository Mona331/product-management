<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\SaleItem;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'employee'])
            ->latest('sales_time');

        // Filter by start date
        if ($request->filled('start_date')) {
            $query->whereDate('sales_time', '>=', $request->start_date);
        }

        // Filter by end date
        if ($request->filled('end_date')) {
            $query->whereDate('sales_time', '<=', $request->end_date);
        }

        $sales = $query->get();

        $totalSales = $sales->sum('total_amount');
        $salesCount = $sales->count();
        $topProductsToday = SaleItem::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->whereHas('sale', function ($query) {
                $query->whereDate('sales_time', today());
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        return view('reports.sales', compact(
            'sales',
            'totalSales',
            'salesCount',
            'topProductsToday'
        ));
    }

    public function exportSales(Request $request)
    {
        $query = Sale::with(['customer', 'employee'])
            ->latest('sales_time');

        if ($request->filled('start_date')) {
            $query->whereDate('sales_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sales_time', '<=', $request->end_date);
        }

        $sales = $query->get();

        $totalSales = $sales->sum('total_amount');
        $salesCount = $sales->count();

        $pdf = Pdf::loadView('reports.sales-pdf', compact(
            'sales',
            'totalSales',
            'salesCount'
        ));

        return $pdf->download('sales-report.pdf');
    }

    public function exportSalesExcel(Request $request)
    {
        $query = Sale::with(['customer', 'employee'])
            ->latest('sales_time');

        if ($request->filled('start_date')) {
            $query->whereDate('sales_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sales_time', '<=', $request->end_date);
        }

        $sales = $query->get();

        $filename = 'sales-report.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($sales) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Sale ID',
                'Customer',
                'Employee',
                'Date',
                'Total Amount',
            ]);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->id,
                    $sale->customer->name ?? 'N/A',
                    $sale->employee->name ?? 'N/A',
                    $sale->sales_time,
                    $sale->total_amount,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function products(Request $request)
    {
        $query = Product::query()->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        $productsCount = $products->count();
        $totalStock = $products->sum('stock_quantity');
        $inventoryValue = $products->sum(function ($product) {
            return $product->price * $product->stock_quantity;
        });

        return view('reports.products', compact(
            'products',
            'productsCount',
            'totalStock',
            'inventoryValue'
        ));
    }

    public function exportProductsExcel(Request $request)
    {
        $query = Product::query()->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products-report.csv"',
        ];

        return response()->stream(function () use ($products) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ID',
                'Product Name',
                'Description',
                'Price',
                'Stock Quantity',
                'Total Value',
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->description,
                    $product->price,
                    $product->stock_quantity,
                    $product->price * $product->stock_quantity,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function employees(Request $request)
    {
        $query = Employee::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->get();

        $employeesCount = $employees->count();

        $positionsCount = $employees->groupBy('position');

        return view('reports.employees', compact(
            'employees',
            'employeesCount',
            'positionsCount'
        ));
    }

    public function exportEmployeesExcel(Request $request)
    {
        $query = Employee::query()->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="employees-report.csv"',
        ];

        return response()->stream(function () use ($employees) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Position',
            ]);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->id,
                    $employee->name,
                    $employee->email,
                    $employee->phone,
                    $employee->position,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function customers(Request $request)
    {
        $query = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->get();

        $customersCount = $customers->count();
        $totalPurchases = $customers->sum('sales_sum_total_amount');

        return view('reports.customers', compact(
            'customers',
            'customersCount',
            'totalPurchases'
        ));
    }

    public function exportCustomersExcel(Request $request)
    {
        $query = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="customers-report.csv"',
        ];

        return response()->stream(function () use ($customers) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Number of Sales',
                'Total Purchases',
            ]);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->sales_count,
                    $customer->sales_sum_total_amount ?? 0,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function employeeSales(Request $request, Employee $employee)
    {
        $query = Sale::with(['customer', 'saleItems.product'])
            ->where('employee_id', $employee->id);

        if ($request->filled('start_date')) {
            $query->whereDate('sales_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sales_time', '<=', $request->end_date);
        }

        $sales = $query->latest('sales_time')->get();

        $totalSales = $sales->sum('total_amount');
        $salesCount = $sales->count();
        $productsSold = \App\Models\SaleItem::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(quantity * unit_price) as total_amount')
            ->whereHas('sale', function ($query) use ($employee, $request) {
                $query->where('employee_id', $employee->id);

                if ($request->filled('start_date')) {
                    $query->whereDate('sales_time', '>=', $request->start_date);
                }

                if ($request->filled('end_date')) {
                    $query->whereDate('sales_time', '<=', $request->end_date);
                }
            })
            ->with('product')
            ->groupBy('product_id')
            ->get();

        return view('reports.employee-sales', compact(
            'employee',
            'sales',
            'totalSales',
            'salesCount',
            'productsSold'
        ));
    }

    public function exportEmployeeSalesExcel(Request $request, Employee $employee)
    {
        $query = Sale::with(['customer', 'saleItems.product'])
            ->where('employee_id', $employee->id);

        if ($request->filled('start_date')) {
            $query->whereDate('sales_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('sales_time', '<=', $request->end_date);
        }

        $sales = $query->latest('sales_time')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="employee-sales-report.csv"',
        ];

        return response()->stream(function () use ($sales, $employee) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Employee',
                'Sale ID',
                'Customer',
                'Date',
                'Total Amount',
            ]);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $employee->name,
                    $sale->id,
                    $sale->customer->name ?? 'N/A',
                    $sale->sales_time,
                    $sale->total_amount,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function mySales(Request $request)
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(404, 'Employee record not found.');
    }

    $query = Sale::with(['customer', 'saleItems.product'])
        ->where('employee_id', $employee->id);

    if ($request->filled('start_date')) {
        $query->whereDate('sales_time', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('sales_time', '<=', $request->end_date);
    }

    $sales = $query->latest('sales_time')->get();

    $totalSales = $sales->count();
    $totalAmount = $sales->sum('total_amount');

    return view('reports.my-sales', compact(
        'employee',
        'sales',
        'totalSales',
        'totalAmount'
    ));
}
}
