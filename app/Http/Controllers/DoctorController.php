<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
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

        $availabilities = Availability::where('doctor_id', $doctor->id)->get();

        return view('doctor.dashboard', compact('upcomingAppointments', 'pastAppointments', 'doctor', 'availabilities'));
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

    public function storeAvailability(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        $doctor = auth()->user()->doctor;
        $conflicts = Appointment::where('doctor_id', $doctor->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('appointment_date', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('appointment_date', '<=', $request->start_time)
                            ->where('appointment_date', '>=', $request->end_time);
                      });
            })->exists();

        if ($conflicts) {
            return back()->withErrors(['message' => 'Conflict with existing appointments.']);
        }

        Availability::create([
            'doctor_id' => $doctor->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('doctor.dashboard')
            ->with('success', 'Availability slot added.');
    }
}
