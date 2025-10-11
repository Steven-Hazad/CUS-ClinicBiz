<?php

namespace App\Http\Controllers;

   use App\Models\Appointment;
   use App\Mail\AppointmentStatusUpdated;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Mail;

   class ReceptionistAppointmentController extends Controller
   {
       public function index(Request $request)
       {
           $search = $request->query('search');
           $appointments = Appointment::with(['patient.user', 'doctor.user'])
               ->when($search, function ($query, $search) {
                   return $query->whereHas('patient.user', function ($q) use ($search) {
                       $q->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                   })->orWhereHas('doctor.user', function ($q) use ($search) {
                       $q->where('name', 'like', "%{$search}%");
                   });
               })
               ->latest()
               ->paginate(10);

           return view('receptionist.appointments.index', compact('appointments', 'search'));
       }

       public function updateStatus(Request $request, Appointment $appointment)
       {
           $request->validate([
               'status' => 'required|in:confirmed,cancelled',
           ]);

           $appointment->update([
               'status' => $request->status,
           ]);

           // Log email notifications
           Mail::to($appointment->patient->user->email)->queue(new AppointmentStatusUpdated($appointment, 'patient'));
           Mail::to($appointment->doctor->user->email)->queue(new AppointmentStatusUpdated($appointment, 'doctor'));

           return redirect()->route('receptionist.appointments.index')
               ->with('success', 'Appointment status updated.');
       }
   }
   