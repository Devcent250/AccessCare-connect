<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'meeting_type' => 'required|in:in-person,virtual',
            'meeting_link' => 'nullable|url|required_if:meeting_type,virtual',
            'description' => 'required|string|max:500',
        ]);

        $appointment = new Appointment($validated);
        $appointment->patient_id = auth()->id();
        $appointment->status = 'pending';
        $appointment->duration_minutes = 30; // Default duration for patient appointments
        $appointment->save();

        return redirect()->route('dashboard')
            ->with('success', 'Appointment request submitted successfully.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'appointment_date' => 'sometimes|required|date|after:now',
            'duration_minutes' => 'sometimes|required|integer|min:15|max:180',
            'meeting_type' => 'sometimes|required|in:in-person,virtual',
            'meeting_link' => 'nullable|url|required_if:meeting_type,virtual',
            'description' => 'sometimes|required|string|max:500',
            'status' => 'sometimes|required|in:pending,approved,rejected,cancelled,completed',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
            'cancellation_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $appointment->update($validated);

        return redirect()->back()
            ->with('success', 'Appointment updated successfully.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return redirect()->back()
            ->with('success', 'Appointment cancelled successfully.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->authorize('reschedule', $appointment);

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:now',
            'duration_minutes' => 'sometimes|required|integer|min:15|max:180',
        ]);

        // Create new appointment
        $newAppointment = $appointment->replicate();
        $newAppointment->appointment_date = $validated['appointment_date'];
        if (isset($validated['duration_minutes'])) {
            $newAppointment->duration_minutes = $validated['duration_minutes'];
        }
        $newAppointment->status = 'pending';
        $newAppointment->is_rescheduled = true;
        $newAppointment->rescheduled_from_id = $appointment->id;
        $newAppointment->save();

        // Update old appointment
        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Rescheduled to new appointment'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Appointment rescheduled successfully.');
    }

    public function complete(Appointment $appointment)
    {
        $this->authorize('complete', $appointment);

        $appointment->update([
            'status' => 'completed'
        ]);

        return redirect()->back()
            ->with('success', 'Appointment marked as completed.');
    }

    public function approve(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->update(['status' => 'approved']);
        return redirect()->route('dashboard')
            ->with('success', 'Appointment approved successfully!');
    }

    public function reject(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->update(['status' => 'rejected']);
        return redirect()->route('dashboard')
            ->with('success', 'Appointment rejected successfully!');
    }

    public function suggestDate(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'suggested_date' => 'required|date|after:now',
            'suggestion_note' => 'required|string|max:500',
        ]);

        try {
            $appointment->fill([
                'suggested_date' => $validated['suggested_date'],
                'suggestion_note' => $validated['suggestion_note'],
                'status' => 'date_suggested'
            ])->save();

            return redirect()->route('dashboard')
                ->with('success', 'Alternative date suggested to patient.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Could not suggest alternative date. Please try again.');
        }
    }

    public function acceptSuggestedDate(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if (!$appointment->suggested_date) {
            return redirect()->back()->with('error', 'No suggested date found.');
        }

        $appointment->update([
            'status' => 'approved',
            'appointment_date' => $appointment->suggested_date,
            'suggested_date' => null,
            'suggestion_note' => null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Suggested appointment date accepted.');
    }

    public function rejectSuggestedDate(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->update([
            'status' => 'pending',
            'suggested_date' => null,
            'suggestion_note' => null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Suggested date declined. Appointment remains pending.');
    }
}
