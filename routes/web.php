<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

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
