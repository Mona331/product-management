<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Reports: Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/reports/sales/export', [ReportController::class, 'exportSales'])
            ->name('reports.sales.export');
        Route::get('/reports/sales/export-excel', [ReportController::class, 'exportSalesExcel'])
            ->name('reports.sales.exportExcel');
        Route::get('/reports/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');
        Route::get('/reports/products', [ReportController::class, 'products'])
            ->name('reports.products');
        Route::get('/reports/products/export-excel', [ReportController::class, 'exportProductsExcel'])
            ->name('reports.products.exportExcel');
        Route::get('/reports/employees', [ReportController::class, 'employees'])
            ->name('reports.employees');
        Route::get('/reports/employees/export-excel', [ReportController::class, 'exportEmployeesExcel'])
            ->name('reports.employees.exportExcel');
        Route::get('/reports/customers', [ReportController::class, 'customers'])
            ->name('reports.customers');
        Route::get('/reports/customers/export-excel', [ReportController::class, 'exportCustomersExcel'])
            ->name('reports.customers.exportExcel');
        Route::get('/reports/employees/{employee}/sales', [ReportController::class, 'employeeSales'])
            ->name('reports.employees.sales');
        Route::get('/reports/employees/{employee}/sales/export-excel', [ReportController::class, 'exportEmployeeSalesExcel'])
            ->name('reports.employees.sales.exportExcel');

        Route::get('/sales/{sale}/export-excel', [SaleController::class, 'exportExcel'])
            ->name('sales.exportExcel');
    });
    // Dashboard: all authenticated users
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // User Management: Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
            ->name('users.updateRole');
    });
    // Profile: all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers: Admin and Employee
    Route::middleware('role:admin,employee')->group(function () {
        Route::resource('customers', CustomerController::class);
    });

    // Sales: Admin and Employee
    Route::middleware('role:admin,employee')->group(function () {
        Route::resource('sales', SaleController::class);
    });

    // Products: Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Employees: Admin and HR
    Route::middleware('role:admin,hr')->group(function () {
        Route::resource('employees', EmployeeController::class);
    });
});

require __DIR__ . '/auth.php';
