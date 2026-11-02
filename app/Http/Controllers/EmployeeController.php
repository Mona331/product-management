<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $query = Employee::query();

        // HR can only see regular employees
        if (auth()->user()->role === 'hr') {
            $query->where(function ($q) {
                $q->whereNull('user_id')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('role', 'employee');
                    });
            });
        }

        $employees = $query->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:100',
            'email' => 'required|email|max:100|unique:employees,email|unique:users,email',
            'position' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($data) {

            // Create login account
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'employee',
            ]);

            // Create employee record linked to the user
            Employee::create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'],
                'position' => $data['position'],
                'user_id' => $user->id,
            ]);
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee and login account created successfully!');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('employees', 'email')->ignore($employee->id),
                Rule::unique('users', 'email')->ignore($employee->user_id),
            ],
            'position' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($data, $employee) {

            // Update employee information
            $employee->update([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'],
                'position' => $data['position'],
            ]);

            // Update linked login account
            if ($employee->user) {
                $employee->user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
            }
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }
    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {

            $user = $employee->user;

            // Prevent HR from deleting Admin or HR accounts
            if (
                auth()->user()->role === 'hr' &&
                $user &&
                $user->role !== 'employee'
            ) {
                abort(403, 'You cannot delete this account.');
            }

            // Delete linked login account
            if ($user) {
                $user->delete();
            }

            // Delete employee record
            $employee->delete();
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee and login account deleted successfully!');
    }
}
