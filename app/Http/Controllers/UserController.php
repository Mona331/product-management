<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $data) {

            // Update user role
            $user->update([
                'role' => $data['role'],
            ]);

            // Update employee position if linked
            if ($user->employee) {
                $position = match ($data['role']) {
                    'admin' => 'Admin',
                    'hr' => 'HR',
                    'employee' => 'Sales Employee',
                };

                $user->employee->update([
                    'position' => $position,
                ]);
            }
        });

        return back()->with('success', 'User role and position updated successfully!');
    }
}
