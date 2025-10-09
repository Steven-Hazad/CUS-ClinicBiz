<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;

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
}