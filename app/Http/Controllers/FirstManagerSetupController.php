<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FirstManagerSetupController extends Controller
{
    public function create()
    {
        return view('setup.manager');
    }

    public function store(Request $request)
    {
        if (User::where('role', 'manager')->exists()) {
            abort(403, 'Initial manager setup is no longer available.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $manager = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'manager',
        ]);

        auth()->login($manager);

        return redirect()->route('manager.dashboard')
            ->with('success', 'Manager account created successfully.');
    }
}