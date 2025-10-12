<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Billing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function create()
    {
        $doctors = Doctor::with('user')->where('status', 'active')->get();
        return view('patient.appointment-book', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $doctor = Doctor::findOrFail($validated['doctor_id']);
        $schedule = $doctor->schedule; // Already an array due to casts
        $day = strtolower(Carbon::parse($validated['appointment_date'])->format('l'));
        $time = $validated['appointment_time'];

        // Check if time is within doctor's schedule
        $validTime = false;
        if (isset($schedule[$day])) {
            foreach ($schedule[$day] as $slot) {
                [$start, $end] = explode('-', $slot);
                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime = Carbon::createFromFormat('H:i', $end);
                $appointmentTime = Carbon::createFromFormat('H:i', $time);

                if ($appointmentTime->between($startTime, $endTime, true)) {
                    $validTime = true;
                    break;
                }
            }
        }

        if (! $validTime) {
            return back()->withErrors(['appointment_time' => 'Selected time is outside doctor\'s schedule.']);
        }

        $appointment = Appointment::create([
            'patient_id' => auth()->user()->patient->id,
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => Carbon::parse($validated['appointment_date'] . ' ' . $time),
            'status' => 'pending',
        ]);

        // Auto-generate billing
        Billing::create([
            'appointment_id' => $appointment->id,
            'amount' => 50.00, // Default consultation fee
            'discount' => 0.00,
            'tax' => 5.00,
            'payment_method' => 'cash',
            'status' => 'pending',
            'invoice_no' => $appointment->generateInvoiceNo(),
        ]);

        return redirect()->route('patient.appointment.book')
            ->with('success', 'Appointment booked successfully.');
    }
}
