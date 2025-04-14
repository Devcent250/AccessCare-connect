<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->patient_id ||
            $user->id === $appointment->doctor_id ||
            $user->hasRole('admin');
    }

    public function update(User $user, Appointment $appointment)
    {
        // Only admin and doctor can update appointments
        return $user->id === $appointment->doctor_id ||
            $user->hasRole('admin');
    }

    public function cancel(User $user, Appointment $appointment)
    {
        // Both patient and doctor can cancel appointments
        return $user->id === $appointment->patient_id ||
            $user->id === $appointment->doctor_id ||
            $user->hasRole('admin');
    }

    public function reschedule(User $user, Appointment $appointment)
    {
        // Both patient and doctor can reschedule appointments
        if (
            $appointment->status === 'completed' ||
            $appointment->status === 'cancelled'
        ) {
            return false;
        }

        return $user->id === $appointment->patient_id ||
            $user->id === $appointment->doctor_id ||
            $user->hasRole('admin');
    }

    public function complete(User $user, Appointment $appointment)
    {
        // Only doctor can mark appointment as completed
        if ($appointment->status !== 'approved') {
            return false;
        }

        return $user->id === $appointment->doctor_id ||
            $user->hasRole('admin');
    }
}
