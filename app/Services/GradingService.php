<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\AcademicClass;
use Illuminate\Support\Facades\DB;
use Exception;

class GradingService
{
    public function inputGrade($studentId, $classId, $components)
    {
        $class = AcademicClass::with('course')->findOrFail($classId);
        $hasPractical = $class->course->has_practical;

        // Calculate numeric grade
        $numericGrade = $this->calculateNumericGrade($components, $class->course);

        // Find conversion
        $conversion = $this->getLetterGrade($numericGrade);

        $data = [
            'numeric_grade' => $numericGrade,
            'letter_grade' => $conversion['letter'],
        ];

        // Add components to data
        $data['attendance'] = $components['attendance'] ?? null;
        $data['midterm'] = $components['midterm'] ?? null;
        $data['final_exam'] = $components['final_exam'] ?? null;
        $data['practical_attendance'] = $hasPractical ? ($components['practical_attendance'] ?? null) : null;
        $data['practical_exam'] = $hasPractical ? ($components['practical_exam'] ?? null) : null;

        return Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_id' => $classId,
            ],
            $data
        );
    }

    public function calculateNumericGrade($components, $course)
    {
        $hasPractical = $course->has_practical;

        // Get rules from course
        $rules = $hasPractical
            ? [
                'attendance' => $course->attendance_weight ?? 10,
                'midterm' => $course->midterm_weight ?? 20,
                'final_exam' => $course->final_exam_weight ?? 50,
                'practical_attendance' => $course->practical_attendance_weight ?? 5,
                'practical_exam' => $course->practical_exam_weight ?? 20,
            ]
            : [
                'attendance' => $course->attendance_weight ?? 10,
                'midterm' => $course->midterm_weight ?? 30,
                'final_exam' => $course->final_exam_weight ?? 60,
            ];

        $total = 0;

        foreach ($rules as $component => $weight) {
            $value = $components[$component] ?? 0;
            $total += ($value * $weight) / 100;
        }

        return round($total, 2);
    }

    private function getLetterGrade($numericGrade)
    {
        $rules = config('system.grade_conversion');

        foreach ($rules as $rule) {
            if ($numericGrade >= $rule['min'] && $numericGrade <= $rule['max']) {
                return $rule;
            }
        }

        // Fallback default E
        return ['letter' => 'E', 'weight' => 0];
    }

    public function bulkInputGrades($classId, array $gradeData)
    {
        return DB::transaction(function () use ($classId, $gradeData) {
            $updated = 0;
            foreach ($gradeData as $studentId => $components) {
                // Skip if all components are null
                $hasData = collect($components)->filter(fn($val) => !is_null($val))->isNotEmpty();
                if (!$hasData) continue;

                $this->inputGrade($studentId, $classId, $components);
                $updated++;
            }
            return $updated;
        });
    }
}
