<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\CourseSchedule;
use App\Models\Meeting;
use App\Models\LecturerAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturerAttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lecturer_id' => Lecturer::factory(),
            'course_schedule_id' => CourseSchedule::factory(),
            'meeting_id' => Meeting::factory(),
            'date' => fake()->date(),
            'entry_time' => '08:30:00',
            'exit_time' => '10:30:00',
            'status' => LecturerAttendance::STATUS_PRESENT,
            'description' => fake()->sentence(),
        ];
    }
}
