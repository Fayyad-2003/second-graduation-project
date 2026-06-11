<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Services\AcademicCalculationService;

class SubjectTreeController extends Controller
{
    public function index(AcademicCalculationService $calculationService)
    {
        $student = Auth::user()->student;
        if (!$student) abort(403, __('Unauthorized'));

        // Get all courses from student's study program, with prerequisites
        $courses = Course::with('prerequisites')
            ->where('study_program_id', $student->study_program_id)
            ->orderBy('semester')
            ->get();

        // Get all finished subjects (with passing grades)
        $finishedCourseIds = $student->grades()
            ->whereIn('letter_grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'])
            ->with('academicClass.course')
            ->get()
            ->pluck('academicClass.course.id')
            ->unique()
            ->toArray();

        // Group courses by semester
        $coursesBySemester = $courses->groupBy('semester');

        // Prepare course connections for JavaScript
        $courseConnections = [];
        foreach ($courses as $course) {
            foreach ($course->prerequisites as $prereq) {
                $courseConnections[] = [
                    'courseId' => $course->id,
                    'prereqId' => $prereq->id,
                    'prereqFinished' => in_array($prereq->id, $finishedCourseIds)
                ];
            }
        }

        // Calculate progress summary
        $cgpaData = $calculationService->calculateCGPA($student);
        $totalCurriculumCredits = $courses->sum('credits');
        $creditsCompleted = $cgpaData['total_credits_passed'];
        $creditsRemaining = max(0, $totalCurriculumCredits - $creditsCompleted);
        $percentageCompleted = $totalCurriculumCredits > 0 ? min(round(($creditsCompleted / $totalCurriculumCredits) * 100), 100) : 0;

        return view('student.subject-tree.index', compact(
            'coursesBySemester',
            'finishedCourseIds',
            'courseConnections',
            'cgpaData',
            'totalCurriculumCredits',
            'creditsCompleted',
            'creditsRemaining',
            'percentageCompleted'
        ));
    }
}
