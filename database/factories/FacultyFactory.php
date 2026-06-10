<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FacultyFactory extends Factory
{
    public function definition(): array
    {
        $faculties = ['كلية الهندسة', 'كلية تكنولوجيا المعلومات', 'كلية العلوم', 'كلية الاقتصاد'];
        return [
            'name' => fake()->randomElement($faculties),
        ];
    }
}
