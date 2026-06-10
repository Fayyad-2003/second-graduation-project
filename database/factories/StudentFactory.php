<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\StudyProgram;
use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $batch = fake()->numberBetween(2020, 2024);
        return [
            'user_id' => User::factory()->state(['role' => 'student']),
            'student_number' => $batch . fake()->unique()->numerify('######'),
            'study_program_id' => StudyProgram::factory(),
            'academic_advisor_id' => Lecturer::factory(),
            'batch' => $batch,
            'status' => 'active',
        ];
    }
}
