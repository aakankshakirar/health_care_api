<?php
// tests/Unit/AppointmentControllerTest.php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Appointment;
use App\Models\User;
use App\Models\HealthcareProfessional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // Unit testing: Book an appointment
    public function testBookAppointment()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a healthcare professional
        $professional = HealthcareProfessional::factory()->create();

        // Mock authenticated user
        $this->actingAs($user);

        // Send a request to book an appointment
        $response = $this->postJson('/api/book-appointment', [
            'healthcare_professional_id' => $professional->id,
            'appointment_start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'appointment_end_time' => now()->addDay()->addHour()->format('Y-m-d H:i:s'),
        ]);

        // Assert response status
        $response->assertStatus(201);

        // Assert appointment created
        $this->assertDatabaseHas('appointments', [
            'healthcare_professional_id' => $professional->id,
            'user_id' => $user->id,
        ]);
    }

    // Unit testing: Get user appointments
    public function testGetUserAppointments()
    {
        // Create a user
        $user = User::factory()->create();

        // Mock authenticated user
        $this->actingAs($user);

        // Create appointments for the user
        $appointments = Appointment::factory()->count(3)->create(['user_id' => $user->id]);

        // Send a request to get user appointments
        $response = $this->getJson('/api/user/appointments');

        // Assert response status
        $response->assertStatus(200);
    }

    // Unit testing: Cancel the appointment
    public function testCancelAppointment()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an appointment for the user
        $appointment = Appointment::factory()->create(['user_id' => $user->id]);

        // Mock authenticated user
        $this->actingAs($user);

        // Send a request to cancel the appointment
        $response = $this->deleteJson('/api/cancel-appointment/' . $appointment->id);

        // Assert response status
        $response->assertStatus(200);

        // Assert that the appointment status is updated to 'cancelled' in the database
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
        ]);
    }

}
