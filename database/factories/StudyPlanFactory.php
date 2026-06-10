<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'status' => fake()->randomElement(['draft', 'submitted', 'approved']),
            'notes' => fake()->sentence(),
        ];
    }
}
