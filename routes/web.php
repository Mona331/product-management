<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Public page
Route::get('/', function () {
    return view('welcome');
});

// Protected pages
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Customers and Sales: Admin + Employee
    Route::resource('customers', CustomerController::class);
    Route::resource('sales', SaleController::class);

    // Products and Employees: Admin only
    Route::middleware('role:admin')->group(function () {

        Route::resource('products', ProductController::class);
        Route::resource('employees', EmployeeController::class);
    });
});

require __DIR__ . '/auth.php';
