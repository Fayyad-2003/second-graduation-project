<?php

namespace App\Services;

use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Exception;

class GradingService
{
    public function inputGrade($studentId, $classId, $numericGrade)
    {
        // Find conversion
        $conversion = $this->getLetterGrade($numericGrade);

        return Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_id' => $classId,
            ],
            [
                'numeric_grade' => $numericGrade,
                'letter_grade' => $conversion['letter'],
            ]
        );
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
            foreach ($gradeData as $studentId => $numericGrade) {
                if (is_null($numericGrade)) continue;

                $this->inputGrade($studentId, $classId, $numericGrade);
                $updated++;
            }
            return $updated;
        });
    }
}
