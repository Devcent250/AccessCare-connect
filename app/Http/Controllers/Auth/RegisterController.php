<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:patient,doctor'],
        ]);

        // Create user without auto-login
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        // Create doctor profile if user is a doctor
        if ($user->role === 'doctor') {
            $user->doctorProfile()->create([
                'specialization' => 'Not specified',
                'qualification' => 'Not specified',
                'experience' => 'Not specified',
                'contact_number' => 'Not specified',
                'address' => 'Not specified',
            ]);
        }

        // Redirect to login with success message
        return redirect()->route('login')
            ->with('status', 'Registration successful! Please login with your credentials.');
    }
}
