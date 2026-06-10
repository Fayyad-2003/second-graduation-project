<?php

namespace Database\Factories;

use App\Models\CourseSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_schedule_id' => CourseSchedule::factory(),
            'meeting_number' => fake()->numberBetween(1, 16),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'topic' => fake()->sentence(),
            'status' => 'completed',
        ];
    }
}
