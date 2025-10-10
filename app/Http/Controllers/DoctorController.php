<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', '>=', now())
            ->with(['patient.user'])
            ->latest()
            ->take(10)
            ->get();

        $pastAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', '<', now())
            ->with(['patient.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('upcomingAppointments', 'pastAppointments', 'doctor'));
    }

    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            return back()->withErrors(['status' => 'Unauthorized action.']);
        }

        $appointment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('doctor.dashboard')
            ->with('success', 'Appointment status updated.');
    }
}
