<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'student_id' => Student::factory(),
            'status' => fake()->randomElement(['present', 'present', 'present', 'present', 'sick', 'excused', 'absent']),
            'attendance_time' => fake()->time('H:i'),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
