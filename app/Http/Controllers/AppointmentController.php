<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;

class AppointmentController extends Controller
{
    // Book an appointment 
    public function bookAppointment(Request $request)
    {
        // Retrieve authenticated user
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        //Validate requested data
        $validator = Validator::make($request->all(), [
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => 'required|date|after_or_equal:now',
            'appointment_end_time' => 'required|date|after:appointment_start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'message' => $validator->errors()], 422);
        }

        // Check for availability
        $isAvailable = !Appointment::where('healthcare_professional_id', $request->healthcare_professional_id)
            ->where(function ($query) use ($request) {
                $query->where('appointment_start_time', '<', $request->appointment_end_time)
                    ->where('appointment_end_time', '>', $request->appointment_start_time);
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if (!$isAvailable) {
            return response()->json([
                'status' => false,
                'message' => 'Appointment slot is not available.'
            ], 400);
        }

        // Create the appointment
        $appointment = Appointment::create([
            'user_id' => $user->id, // Associate appointment with authenticated user
            'healthcare_professional_id' => $request->healthcare_professional_id,
            'appointment_start_time' => $request->appointment_start_time,
            'appointment_end_time' => $request->appointment_end_time,
        ]);

        return response()->json(['status' => true, 'message' => 'Appointment booked successfully.', 'appointment' => $appointment], 201);
    }

    // View all appointments for a user.
    public function getUserAppointments()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $appointments = $user->appointments()->with('healthcareProfessional')->get();

        return response()->json(['data' => $appointments], 200);
    }

    public function cancelAppointment($appointmentId)
    {
        // Check if the appointment ID is valid
        if (!$appointmentId || !Appointment::find($appointmentId)) {
            return response()->json(['error' => 'Invalid appointment ID.'], Response::HTTP_BAD_REQUEST);
        }

        // Retrieve the appointment
        $appointment = Appointment::findOrFail($appointmentId);

        // Check if the authenticated user is authorized to cancel the appointment
        if (!$this->isUserAuthorizedToCancelAppointment($appointment)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Check if the appointment can be cancelled
        if (!$this->canCancelAppointment($appointment)) {
            return response()->json(['error' => 'Cannot cancel appointment within 24 hours of the appointment time.'], Response::HTTP_BAD_REQUEST);
        }

        // Update appointment status to cancelled
        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json(['message' => 'Appointment cancelled successfully.'], Response::HTTP_OK);
    }

    private function canCancelAppointment(Appointment $appointment)
    {
        // Calculate the difference in hours between the appointment time and current time
        $currentTime = Carbon::now();
        $appointmentTime = Carbon::parse($appointment->appointment_start_time);
        $hoursDifference = $appointmentTime->diffInHours($currentTime);

        // Check if the appointment can be cancelled (24 hours in advance)
        return $hoursDifference >= 24;
    }

    private function isUserAuthorizedToCancelAppointment(Appointment $appointment)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the authenticated user is the user who booked the appointment
        return $user && $user->id === $appointment->user_id;
    }

    
}
