<?php

namespace Database\Factories;

use App\Models\AcademicClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseScheduleFactory extends Factory
{
    public function definition(): array
    {
        $startTime = fake()->randomElement(['08:00', '10:00', '13:00', '15:00']);
        $endTime = date('H:i', strtotime($startTime) + 5400); // 1.5 hours later
        
        return [
            'class_id' => AcademicClass::factory(),
            'day' => fake()->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room' => 'Room ' . fake()->numerify('###'),
        ];
    }
}
