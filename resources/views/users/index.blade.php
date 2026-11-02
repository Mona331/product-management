@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">User Management</h2>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Change Role</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>
                            <span class="badge
                                    {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'hr' ? 'bg-warning text-dark' : 'bg-primary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.updateRole', $user) }}"
                                method="POST"
                                class="d-flex gap-2">

                                @csrf
                                @method('PATCH')

                                <select name="role" class="form-select form-select-sm">
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="hr" {{ $user->role === 'hr' ? 'selected' : '' }}>HR</option>
                                    <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Employee</option>
                                </select>

                                <button type="submit" class="btn btn-sm btn-primary">
                                    Update
                                </button>
                            </form>
                            @else
                            <span class="text-muted">Current Account</span>
                            @endif
                        </td>

                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection