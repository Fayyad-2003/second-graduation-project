<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    public function definition(): array
    {
        $year = fake()->numberBetween(2020, 2025);
        $semester = fake()->randomElement(['Odd', 'Even']);
        
        return [
            'year' => "$year/" . ($year + 1),
            'semester' => $semester,
            'is_active' => false,
            'start_date' => $semester === 'Odd' ? "$year-09-01" : ($year + 1) . "-02-01",
            'completion_date' => $semester === 'Odd' ? ($year + 1) . "-01-31" : ($year + 1) . "-06-30",
        ];
    }
}
