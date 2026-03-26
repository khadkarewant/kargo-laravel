<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['manager', 'employee'])
            ->latest()
            ->get();

        return view('manager.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('manager.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'employee',
        ]);

        return redirect()->route('manager.staff.index')
            ->with('success', 'Employee account created successfully.');
    }

    public function show(User $user)
    {
        if ($user->role === 'customer') {
            abort(404);
        }

        return view('manager.staff.show', compact('user'));
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            abort(403, 'You cannot deactivate your own account.');
        }

        if ($user->role === 'manager') {
            abort(403, 'Manager accounts cannot be deactivated this way.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('manager.staff.show', $user)
            ->with('success', "Employee {$status} successfully.");
    }
}