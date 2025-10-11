<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalAppointments = Appointment::count();
        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->latest()
            ->take(5)
            ->get();

        $dates = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $appointmentCounts = $dates->map(function ($date) {
            return Appointment::whereDate('appointment_date', $date)->count();
        });

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'totalAppointments',
            'recentAppointments',
            'dates',
            'appointmentCounts'
        ));
    }

    public function patients(Request $request)
    {
        $search = $request->query('search');
        $patients = Patient::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('admin.patients.index', compact('patients', 'search'));
    }

    public function editPatient(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($patient->user->id)],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => 'required|date|before:today',
            'contact' => 'required|string|max:20',
            'medical_history' => 'nullable|string|max:1000',
        ]);

        $patient->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $patient->update([
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'contact' => $validated['contact'],
            'medical_history' => $validated['medical_history'],
        ]);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully.');
    }
    public function analytics()
    {
        $totalAppointments = Appointment::count();
        $byStatus = Appointment::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        $byDate = Appointment::select(\DB::raw('DATE(appointment_date) as date'), \DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        return view('admin.analytics', compact('totalAppointments', 'byStatus', 'byDate'));
    }
}
