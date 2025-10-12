<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\NewAvailabilityNotification;

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

        $availabilities = $doctor->availabilities()->get();

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

        $availability = Availability::create([
            'doctor_id' => $doctor->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        $patients = \App\Models\Patient::whereHas('appointments', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->get();

        foreach ($patients as $patient) {
            $patient->user->notify((new NewAvailabilityNotification($availability))->onQueue('notifications'));
        }

        return redirect()->route('doctor.dashboard')
            ->with('success', 'Availability slot added and patients notified.');
    }

    public function viewMedicalHistory(\App\Models\Patient $patient)
    {
        if (!auth()->user()->doctor->appointments()->where('patient_id', $patient->id)->exists()) {
            return back()->withErrors(['message' => 'Unauthorized access to patient history.']);
        }

        return view('doctor.medical-history', compact('patient'));
    }

    public function storeMedicalRecord(Request $request, \App\Models\Patient $patient)
    {
        if (!auth()->user()->doctor->appointments()->where('patient_id', $patient->id)->exists()) {
            return back()->withErrors(['message' => 'Unauthorized action.']);
        }

        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'record_date' => 'required|date',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('medical_records', 'public');
        }

        MedicalRecord::create([
            'patient_id' => $patient->id,
            'doctor_id' => auth()->user()->doctor->id,
            'notes' => $validated['notes'],
            'file_path' => $filePath,
            'record_date' => $validated['record_date'],
        ]);

        return redirect()->route('doctor.medical-history', $patient)
            ->with('success', 'Medical record added.');
    }
}
