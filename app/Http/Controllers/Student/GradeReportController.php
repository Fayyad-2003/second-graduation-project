<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Services\AcademicCalculationService;
use Illuminate\Support\Facades\Auth;

class GradeReportController extends Controller
{
    protected $calculationService;

    public function __construct(AcademicCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Display list of semesters with grade reports
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Get all semesters where student has study plan (oldest first for historical view)
        $semesterList = StudyPlan::where('student_id', $student->id)
            ->where('status', 'approved')
            ->with('academicYear')
            ->orderBy('academic_year_id', 'asc')
            ->get()
            ->map(function ($studyPlan) use ($student) {
                $gpaData = $this->calculationService->calculateGPA($student, $studyPlan->academic_year_id);
                $courseCount = Grade::where('student_id', $student->id)
                    ->whereHas('academicClass', fn($q) => $q->where('academic_year_id', $studyPlan->academic_year_id))
                    ->count();
                return [
                    'studyPlan' => $studyPlan,
                    'academicYear' => $studyPlan->academicYear,
                    'gpa' => $gpaData['gpa'],
                    'semester_gpa' => $gpaData['gpa'],
                    'total_credits' => $gpaData['total_credits'],
                    'course_count' => $courseCount,
                ];
            });

        // Get current CGPA
        $cgpaData = $this->calculationService->calculateCGPA($student);
        return view('student.grade-report.index', compact('student', 'semesterList', 'cgpaData'));
    }

    /**
     * Display grade report detail for a specific semester
     */
    public function show(AcademicYear $academicYear)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Check if student has approved study plan for this semester
        $studyPlan = StudyPlan::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('status', 'approved')
            ->first();

        if (!$studyPlan) {
            return redirect()->route('students.grade-report.index')
                ->with('error', __('Study plan for this semester has not been approved'));
        }

        // Get all grades for this semester
        $gradeList = Grade::where('student_id', $student->id)
            ->whereHas('academicClass', function ($q) use ($academicYear) {
                $q->where('academic_year_id', $academicYear->id);
            })
            ->with(['academicClass.course', 'academicClass.lecturer.user'])
            ->get()
            ->sortBy('academicClass.course.course_code');

        // Calculate GPA for this semester
        $gpaData = $this->calculationService->calculateGPA($student, $academicYear->id);

        // Get CGPA cumulative
        $cgpaData = $this->calculationService->calculateCGPA($student);

        // Grade distribution for this semester
        $gradeDistribution = $gradeList->groupBy('letter_grade')
            ->map(fn($group) => $group->count())
            ->sortKeys();

        $semesterGpaData = [
            'gpa' => $gpaData['gpa'],
            'semester_gpa' => $gpaData['gpa'],
            'total_credits' => $gpaData['total_credits'],
        ];

        return view('student.grade-report.show', compact(
            'student',
            'academicYear',
            'gradeList',
            'gpaData',
            'cgpaData',
            'gradeDistribution',
            'semesterGpaData'
        ));
    }
}
