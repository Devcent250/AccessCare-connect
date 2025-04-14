<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isDoctor()) {
            // Fetch appointments for the doctor
            $appointments = $user->appointments; // Assuming a relationship exists
            return view('dashboard', compact('appointments'));
        } else {
            // Fetch available doctors for the patient
            $doctors = User::where('role', 'doctor')->get();
            return view('dashboard', compact('doctors'));
        }
    }
}
