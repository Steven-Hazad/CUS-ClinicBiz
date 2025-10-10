<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientRegistrationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReceptionistAppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/patients', [AdminController::class, 'patients'])->name('admin.patients.index');
        Route::get('/patients/{patient}/edit', [AdminController::class, 'editPatient'])->name('admin.patients.edit');
        Route::put('/patients/{patient}', [AdminController::class, 'updatePatient'])->name('admin.patients.update');
    });

    Route::prefix('receptionist')->middleware('role:admin,receptionist')->group(function () {
        Route::get('/dashboard', function () {
            return view('receptionist.dashboard');
        })->name('receptionist.dashboard');
        Route::get('/patient/register', [PatientRegistrationController::class, 'create'])->name('receptionist.patient.register');
        Route::post('/patient/register', [PatientRegistrationController::class, 'store'])->name('receptionist.patient.register.store');
        Route::get('/appointments', [ReceptionistAppointmentController::class, 'index'])->name('receptionist.appointments.index');
        Route::patch('/appointments/{appointment}', [ReceptionistAppointmentController::class, 'updateStatus'])->name('receptionist.appointments.update');
    });


 Route::get('/patient/appointment/book', [AppointmentController::class, 'create'])
        ->middleware('role:patient')
        ->name('patient.appointment.book');

    Route::post('/patient/appointment/book', [AppointmentController::class, 'store'])
        ->middleware('role:patient')
        ->name('patient.appointment.book.store');
         Route::get('/doctor/dashboard', [DoctorController::class, 'index'])
        ->middleware('role:doctor')
        ->name('doctor.dashboard');

    Route::patch('/doctor/appointment/{appointment}', [DoctorController::class, 'updateAppointmentStatus'])
        ->middleware('role:doctor')
        ->name('doctor.appointment.update');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';