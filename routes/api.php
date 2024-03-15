<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthcareProfessionalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registration 
Route::post('register', [AuthController::class, 'register']);

// Login
Route::post('login', [AuthController::class, 'login']);

// List: Health care professionals
Route::get('healthcare-professionals', [HealthcareProfessionalController::class, 'index']);

// Book Appointment
Route::post('/book-appointment', [AppointmentController::class, 'bookAppointment']);

// View all appointments for a user
Route::get('/user/appointments', [AppointmentController::class, 'getUserAppointments']);

// Cancel an appointment 
Route::delete('/cancel-appointment/{appointment_id}', [AppointmentController::class, 'cancelAppointment']);
