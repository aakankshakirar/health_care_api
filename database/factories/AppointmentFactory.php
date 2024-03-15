<?php

namespace Database\Factories;

use App\Models\HealthcareProfessional;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'healthcare_professional_id' => function () {
                return HealthcareProfessional::factory()->create()->id;
            },
            'appointment_start_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'appointment_end_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'status' => $this->faker->randomElement(['booked', 'completed', 'cancelled']),
        ];
    }
}
