<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'lecturer_id' => Lecturer::factory(),
            'class_name' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'capacity' => fake()->numberBetween(30, 50),
            'academic_year_id' => AcademicYear::factory(),
        ];
    }
}
