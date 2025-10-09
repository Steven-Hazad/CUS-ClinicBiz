<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Doctor
        $doctorUser = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@medsync.com',
            'password' => bcrypt('password123'),
            'role' => 'doctor',
            'email_verified_at' => now(),
        ]);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization' => 'Cardiology',
            'schedule' => json_encode(['monday' => ['09:00-12:00', '14:00-17:00']]),
            'status' => 'active',
        ]);

        // Create Patient
        $patientUser = User::create([
            'name' => 'Jane Doe',
            'email' => 'patient@medsync.com',
            'password' => bcrypt('password123'),
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);

        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'gender' => 'female',
            'dob' => '1990-05-15',
            'contact' => '123-456-7890',
            'medical_history' => 'No known allergies.',
        ]);

        // Create Appointments
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::now()->subDay(),
            'status' => 'confirmed',
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => Carbon::now(),
            'status' => 'pending',
        ]);
    }
}