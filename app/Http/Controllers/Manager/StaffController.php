<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

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
}