<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class CourseRecommendationService
{
    /**
     * Get course recommendations for a student based on their strengths (grades)
     */
    public function getRecommendations(Student $student)
    {
        // 1. Calculate student's strengths by classification
        $strengths = Grade::where('student_id', $student->id)
            ->join('classes', 'grades.class_id', '=', 'classes.id')
            ->join('courses', 'classes.course_id', '=', 'courses.id')
            ->join('subject_classifications', 'courses.subject_classification_id', '=', 'subject_classifications.id')
            ->select(
                'subject_classifications.id as classification_id',
                'subject_classifications.name as classification_name',
                DB::raw('AVG(grades.numeric_grade) as average_grade')
            )
            ->groupBy('subject_classifications.id', 'subject_classifications.name')
            ->orderByDesc('average_grade')
            ->get();

        if ($strengths->isEmpty()) {
            return collect();
        }

        // 2. Get IDs of courses the student has already taken
        $takenCourseIds = Grade::where('student_id', $student->id)
            ->join('classes', 'grades.class_id', '=', 'classes.id')
            ->pluck('classes.course_id')
            ->toArray();

        // 3. Find elective courses in the strength classifications that haven't been taken
        $recommendations = collect();
        foreach ($strengths as $strength) {
            $courses = Course::where('subject_classification_id', $strength->classification_id)
                ->where('is_elective', true)
                ->whereNotIn('id', $takenCourseIds)
                ->where('study_program_id', $student->study_program_id)
                ->with('classification')
                ->get()
                ->map(function ($course) use ($strength) {
                    $course->match_score = $strength->average_grade;
                    $course->strength_category = $strength->classification_name;
                    return $course;
                });

            $recommendations = $recommendations->concat($courses);
        }

        // Sort all recommendations by match score (the student's average in that category)
        return $recommendations->sortByDesc('match_score')->values();
    }
}
