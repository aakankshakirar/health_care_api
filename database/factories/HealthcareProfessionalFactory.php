<?php

namespace Database\Factories;

use App\Models\HealthcareProfessional;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthcareProfessional>
 */
class HealthcareProfessionalFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HealthcareProfessional::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $specialties = ['General Practitioner', 'Pediatrician', 'Dermatologist', 'Psychiatrist', 'Orthopedic Surgeon'];
        
        return [
            'name' => $this->faker->name,
            'specialty' => $this->faker->randomElement($specialties),
        ];
    }
}
