<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matricule' => fake()->unique()->bothify('EMP###'),
            'phone' => fake()->randomElement(['06', '07']) . fake()->numerify('########'),
            'grade' => fake()->jobTitle(),
            'position' => fake()->jobTitle(),
            'recruitment_date' => fake()->date(),
        ];
    }
}
