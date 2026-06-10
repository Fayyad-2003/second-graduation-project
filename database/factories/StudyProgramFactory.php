<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyProgramFactory extends Factory
{
    public function definition(): array
    {
        $programs = ['هندسة الحاسوب', 'هندسة البرمجيات', 'نظم المعلومات', 'الذكاء الاصطناعي'];
        return [
            'name' => fake()->randomElement($programs),
            'faculty_id' => Faculty::factory(),
        ];
    }
}
