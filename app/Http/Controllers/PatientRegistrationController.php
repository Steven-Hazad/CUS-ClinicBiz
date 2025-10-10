<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PatientRegistrationController extends Controller
{
    public function create()
    {
        return view('receptionist.patient-register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => 'required|date|before:today',
            'contact' => 'required|string|max:20',
            'medical_history' => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('default123'), // Default password, can be changed later
            'role' => 'patient',
            'email_verified_at' => now(), // Auto-verify for simplicity
        ]);

        Patient::create([
            'user_id' => $user->id,
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'contact' => $validated['contact'],
            'medical_history' => $validated['medical_history'],
        ]);

        return redirect()->route('receptionist.patient.register')
            ->with('success', 'Patient registered successfully.');
    }
}
