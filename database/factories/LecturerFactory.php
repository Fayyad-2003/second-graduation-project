<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'lecturer']),
            'lecturer_number' => fake()->unique()->numerify('##########'),
            'study_program_id' => StudyProgram::factory(),
        ];
    }
}
