<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Billing;
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

    public function doctors(Request $request)
    {
        $search = $request->query('search');
        $doctors = Doctor::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors', 'search'));
    }

    public function createDoctor()
    {
        return view('admin.doctors.create');
    }

    public function storeDoctor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|max:100',
            'schedule' => 'required|json',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'contact' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'doctor',
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialization' => $validated['specialization'],
            'schedule' => $validated['schedule'], // Removed json_decode
            'status' => $validated['status'],
            'contact' => $validated['contact'],
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function editDoctor(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function updateDoctor(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($doctor->user->id)],
            'specialization' => 'required|string|max:100',
            'schedule' => 'required|json',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'contact' => 'required|string|max:20',
        ]);

        $doctor->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $doctor->update([
            'specialization' => $validated['specialization'],
            'schedule' => $validated['schedule'], // Removed json_decode
            'status' => $validated['status'],
            'contact' => $validated['contact'],
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function billings()
    {
        $billings = Billing::with('appointment.patient.user', 'appointment.doctor.user')->paginate(10);
        return view('admin.billings.index', compact('billings'));
    }
}
