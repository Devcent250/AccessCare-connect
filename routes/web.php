<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isDoctor()) {
            // For doctors, get their appointments
            $appointments = $user->doctorAppointments()
                ->with('patient')
                ->get();
            return view('dashboard', ['appointments' => $appointments]);
        } else {
            // For patients, get available doctors and their appointments
            $doctors = User::where('role', 'doctor')
                ->with('doctorProfile')
                ->get();
            $appointments = $user->patientAppointments()
                ->with('doctor')
                ->get();
            return view('dashboard', compact('doctors', 'appointments'));
        }
    })->name('dashboard');

    // Appointment Routes
    Route::controller(AppointmentController::class)->group(function () {
        Route::post('/appointments', 'store')->name('appointments.store');
        Route::put('/appointments/{appointment}', 'update')->name('appointments.update');
        Route::post('/appointments/{appointment}/cancel', 'cancel')->name('appointments.cancel');
        Route::post('/appointments/{appointment}/reschedule', 'reschedule')->name('appointments.reschedule');
        Route::post('/appointments/{appointment}/complete', 'complete')->name('appointments.complete');
        Route::post('/appointments/{appointment}/approve', 'approve')->name('appointments.approve');
        Route::post('/appointments/{appointment}/reject', 'reject')->name('appointments.reject');
        Route::post('/appointments/{appointment}/suggest-date', 'suggestDate')->name('appointments.suggest-date');
        Route::post('/appointments/{appointment}/accept-suggested-date', 'acceptSuggestedDate')->name('appointments.accept-suggested-date');
        Route::post('/appointments/{appointment}/reject-suggested-date', 'rejectSuggestedDate')->name('appointments.reject-suggested-date');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
