<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function dashboard()
    {
        $patient = auth()->user()->patient;
        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('appointment_date', '>=', now())
            ->with(['doctor.user'])
            ->latest()
            ->take(10)
            ->get();

        $pastAppointments = Appointment::where('patient_id', $patient->id)
            ->where('appointment_date', '<', now())
            ->with(['doctor.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('patient.dashboard', compact('upcomingAppointments', 'pastAppointments', 'patient'));
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:now',
        ]);

        if ($appointment->patient_id !== auth()->user()->patient->id) {
            return back()->withErrors(['message' => 'Unauthorized action.']);
        }

        $appointment->update([
            'appointment_date' => $request->appointment_date,
        ]);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Appointment rescheduled successfully.');
    }

    public function confirm(Availability $availability)
    {
        return view('patient.confirm', compact('availability'));
    }

    public function book(Request $request, Availability $availability)
    {
        $request->validate([
            'appointment_date' => 'required|date|after:now|before:' . $availability->end_time,
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment = Appointment::create([
            'patient_id' => auth()->user()->patient->id,
            'doctor_id' => $availability->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $availability->update(['available' => false]);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Appointment booked successfully!');
    }
}
