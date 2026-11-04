<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Display all users
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    // Update user role
    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => [
                'required',
                Rule::in(['admin', 'hr', 'employee']),
            ],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        DB::transaction(function () use ($user, $data) {

            // Update user role
            $user->update([
                'role' => $data['role'],
            ]);

            $position = match ($data['role']) {
                'admin' => 'Admin',
                'hr' => 'HR',
                'employee' => 'Sales Employee',
            };

            $employee = $user->employee;

            // Create employee record for HR if it does not exist
            if (!$employee && $data['role'] === 'hr') {

                $employee = Employee::where('email', $user->email)
                    ->whereNull('user_id')
                    ->first();

                if ($employee) {
                    $employee->update([
                        'user_id' => $user->id,
                        'position' => $position,
                    ]);
                } else {
                    Employee::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'position' => $position,
                        'user_id' => $user->id,
                    ]);
                }
            } elseif ($employee) {

                $employee->update([
                    'position' => $position,
                ]);
            }
        });

        return back()->with('success', 'User role and position updated successfully!');
    }
}
