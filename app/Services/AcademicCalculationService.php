<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Grade;
use App\Models\GpaCreditRule;
use App\Models\StudyPlan;
use App\Models\AcademicYear;
use Illuminate\Support\Collection;

class AcademicCalculationService
{
    /**
     * Calculate GPA (Grade Point Average) for a specific semester
     */
    public function calculateGPA(Student $student, ?int $academicYearId = null): array
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $academicYearId = $academicYearId ?? $activeYear?->id;

        if (!$academicYearId) {
            return [
                'gpa' => 0,
                'total_credits' => 0,
                'total_weight' => 0,
                'total_credits_passed' => 0
            ];
        }

        $grades = Grade::where('student_id', $student->id)
            ->whereHas('academicClass', function ($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->with('academicClass.course')
            ->get();

        return $this->calculateIndexFromGrades($grades);
    }

    /**
     * Calculate CGPA (Cumulative Grade Point Average) - all semesters
     */
    public function calculateCGPA(Student $student): array
    {
        $grades = Grade::where('student_id', $student->id)
            ->with('academicClass.course')
            ->get();

        return $this->calculateIndexFromGrades($grades);
    }

    /**
     * Get grade distribution for a student
     */
    public function getGradeDistribution(Student $student): array
    {
        $grades = Grade::where('student_id', $student->id)->get();

        $distribution = [
            'A' => 0,
            'A-' => 0,
            'B+' => 0,
            'B' => 0,
            'B-' => 0,
            'C+' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0
        ];

        foreach ($grades as $grade) {
            $letter = strtoupper($grade->letter_grade ?? '');
            if (isset($distribution[$letter])) {
                $distribution[$letter]++;
            }
        }

        return $distribution;
    }

    /**
     * Get semester-wise GPA history
     */
    public function getGPAHistory(Student $student): Collection
    {
        $studyPlanHistory = StudyPlan::where('student_id', $student->id)
            ->where('status', 'approved')
            ->with('academicYear')
            ->orderBy('created_at')
            ->get();

        return $studyPlanHistory->map(function ($studyPlan) use ($student) {
            $gpaData = $this->calculateGPA($student, $studyPlan->academic_year_id);
            return [
                'academic_year' => $studyPlan->academicYear->year . ' ' . $studyPlan->academicYear->semester,
                'academic_year_id' => $studyPlan->academic_year_id,
                'gpa' => $gpaData['gpa'],
                'total_credits' => $gpaData['total_credits'],
            ];
        });
    }

    /**
     * Determine max credits allowed based on GPA.
     * Reads from gpa_credit_rules database table first; falls back to
     * config/system.php if the table is empty.
     */
    public function getMaxCredits(float $gpa): int
    {
        $dbRules = GpaCreditRule::orderByDesc('min_gpa')->get();

        if ($dbRules->isNotEmpty()) {
            foreach ($dbRules as $rule) {
                if ($gpa >= $rule->min_gpa && $gpa <= $rule->max_gpa) {
                    return $rule->max_credits;
                }
            }
            // GPA is below the lowest rule; return the lowest rule's credits
            return $dbRules->last()->max_credits;
        }

        // Fallback to config/system.php rules
        $rules = config('system.max_credits.gpa_rules', [
            ['min' => 3.51, 'max' => 4.00, 'credits' => 24],
            ['min' => 3.01, 'max' => 3.50, 'credits' => 22],
            ['min' => 2.51, 'max' => 3.00, 'credits' => 20],
            ['min' => 2.00, 'max' => 2.50, 'credits' => 18],
            ['min' => 0.00, 'max' => 1.99, 'credits' => 14],
        ]);

        foreach ($rules as $rule) {
            if ($gpa >= $rule['min'] && $gpa <= $rule['max']) {
                return $rule['credits'];
            }
        }

        return config('system.max_credits.default', 24);
    }

    /**
     * Internal helper to calculate GPA from a collection of grades
     */
    protected function calculateIndexFromGrades(Collection $grades): array
    {
        $totalCredits = 0;
        $totalWeight = 0;
        $totalCreditsPassed = 0;

        foreach ($grades as $grade) {
            $credits = $grade->academicClass->course->credits ?? 0;
            $weight = $grade->numeric_grade ?? 0;

            $totalCredits += $credits;
            $totalWeight += ($credits * $weight);

            if ($this->isPassed($grade->letter_grade)) {
                $totalCreditsPassed += $credits;
            }
        }

        $gpa = $totalCredits > 0 ? round($totalWeight / $totalCredits, 2) : 0;

        return [
            'gpa' => $gpa,
            'total_credits' => $totalCredits,
            'total_weight' => $totalWeight,
            'total_credits_passed' => $totalCreditsPassed,
        ];
    }

    /**
     * Get full transcript data for a student
     */
    public function getTranscript(Student $student): array
    {
        $grades = Grade::where('student_id', $student->id)
            ->with(['academicClass.course', 'academicClass.academicYear'])
            ->get();

        $semesters = $grades->groupBy(fn($g) => $g->academicClass->academicYear->year . ' ' . $g->academicClass->academicYear->semester)
            ->map(function ($semesterGrades, $semesterName) {
                $calc = $this->calculateIndexFromGrades($semesterGrades);
                return [
                    'semester' => $semesterName,
                    'courses' => $semesterGrades->map(fn($g) => [
                        'course_code' => $g->academicClass->course->course_code,
                        'name' => $g->academicClass->course->course_name,
                        'credits' => $g->academicClass->course->credits,
                        'numeric_grade' => $g->numeric_grade,
                        'letter_grade' => $g->letter_grade,
                    ]),
                    'gpa' => $calc['gpa'],
                    'total_credits' => $calc['total_credits'],
                ];
            });

        $cgpa = $this->calculateCGPA($student);

        return [
            'semesters' => $semesters,
            'gpa' => $cgpa['gpa'],
            'total_credits' => $cgpa['total_credits'],
            'total_credits_passed' => $cgpa['total_credits_passed'],
        ];
    }

    /**
     * Get graduation requirement progress by classification
     */
    public function getClassificationProgress(Student $student): Collection
    {
        $facultyId = $student->studyProgram->faculty_id;
        $requirements = \App\Models\FacultyRequirement::where('faculty_id', $facultyId)
            ->with('classification')
            ->get();

        $grades = Grade::where('student_id', $student->id)
            ->whereIn('letter_grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'])
            ->with('academicClass.course')
            ->get();

        $activeStudyPlan = StudyPlan::where('student_id', $student->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->with('details.academicClass.course')
            ->first();

        return $requirements->map(function ($req) use ($grades, $activeStudyPlan) {
            $classificationId = $req->subject_classification_id;

            $total = $grades->filter(fn($g) => $g->academicClass->course->subject_classification_id == $classificationId)
                ->sum(fn($g) => $g->academicClass->course->credits);

            $enrolled = 0;
            if ($activeStudyPlan) {
                $enrolled = $activeStudyPlan->details->filter(fn($d) => $d->academicClass->course->subject_classification_id == $classificationId)
                    ->sum(fn($d) => $d->academicClass->course->credits);
            }

            return [
                'name' => $req->classification->name,
                'total' => $total,
                'required' => $req->required_credits,
                'enrolled' => $enrolled,
                'percentage' => $req->required_credits > 0 ? min(round(($total / $req->required_credits) * 100), 100) : 100,
            ];
        });
    }

    /**
     * Determine the GPA warning level for a given GPA value.
     *
     * @return string|null  'danger' | 'caution' | null
     */
    public function getGpaWarningLevel(float $gpa): ?string
    {
        $danger  = config('system.gpa_warning.danger', 2.00);
        $caution = config('system.gpa_warning.caution', 2.50);

        if ($gpa > 0 && $gpa < $danger) {
            return 'danger';
        }

        if ($gpa > 0 && $gpa < $caution) {
            return 'caution';
        }

        return null;
    }

    protected function isPassed(?string $grade): bool
    {
        if (!$grade) return false;
        return in_array(strtoupper($grade), ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C']);
    }
}
