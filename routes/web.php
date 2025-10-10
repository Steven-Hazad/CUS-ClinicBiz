<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientRegistrationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');
});
 Route::get('/receptionist/patient/register', [PatientRegistrationController::class, 'create'])
        ->middleware('role:admin,receptionist')
        ->name('receptionist.patient.register');

    Route::post('/receptionist/patient/register', [PatientRegistrationController::class, 'store'])
        ->middleware('role:admin,receptionist')
        ->name('receptionist.patient.register.store');
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
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
