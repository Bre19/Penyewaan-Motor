<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:30'],
            'current_address' => ['required', 'string', 'max:1000'],
            'passport_number' => ['required', 'string', 'max:100'],
            'origin_country' => ['required', 'string', 'max:100'],
            'has_license' => ['required', 'boolean'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'current_address' => $validated['current_address'],
            'passport_number' => $validated['passport_number'],
            'origin_country' => $validated['origin_country'],
            'has_license' => $validated['has_license'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->forceFill([
            'role' => 'customer',
            'status' => 'active',
        ])->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}