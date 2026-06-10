<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\AcademicClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    public function definition(): array
    {
        $numericGrade = fake()->numberBetween(50, 100);
        return [
            'student_id' => Student::factory(),
            'class_id' => AcademicClass::factory(),
            'numeric_grade' => $numericGrade,
            'letter_grade' => $this->convertToLetter($numericGrade),
        ];
    }

    private function convertToLetter(int $grade): string
    {
        return match (true) {
            $grade >= 85 => 'A',
            $grade >= 80 => 'A-',
            $grade >= 75 => 'B+',
            $grade >= 70 => 'B',
            $grade >= 65 => 'C+',
            $grade >= 60 => 'C',
            $grade >= 55 => 'D',
            default => 'E',
        };
    }
}
