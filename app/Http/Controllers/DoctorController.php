<?php

namespace App\Http\Controllers;

   use App\Models\Appointment;
   use App\Mail\AppointmentStatusUpdated;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Mail;
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

           // Log email notifications
           Mail::to($appointment->patient->user->email)->queue(new AppointmentStatusUpdated($appointment, 'patient'));
           Mail::to($appointment->doctor->user->email)->queue(new AppointmentStatusUpdated($appointment, 'doctor'));

           return redirect()->route('doctor.dashboard')
               ->with('success', 'Appointment status updated.');
       }
   }
   