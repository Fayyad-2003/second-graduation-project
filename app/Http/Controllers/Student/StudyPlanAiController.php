<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\StudyPlan;
use App\Services\StudyPlanAiService;
use App\Services\AcademicCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyPlanAiController extends Controller
{
    protected StudyPlanAiService $aiService;
    protected AcademicCalculationService $calculationService;

    public function __construct(StudyPlanAiService $aiService, AcademicCalculationService $calculationService)
    {
        $this->aiService = $aiService;
        $this->calculationService = $calculationService;
    }

    /**
     * Display the AI Study Plan page
     */
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Get classes the student is currently enrolled in
        $classes = AcademicClass::whereHas('details', function ($q) use ($student) {
            $q->whereHas('studyPlan', function ($q2) use ($student) {
                $q2->where('student_id', $student->id)
                    ->where('status', 'approved'); // Only approved study plans
            });
        })->with('course')->get();

        return view('student.study-plan-ai.index', compact('student', 'classes'));
    }

    /**
     * Generate study plan for a selected class
     */
    public function generate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $student = Auth::user()->student;

        if (!$student) {
            return response()->json(['success' => false, 'message' => __('Unauthorized')], 403);
        }

        $class = AcademicClass::findOrFail($request->class_id);

        // Verify student is in this class
        $isEnrolled = AcademicClass::where('id', $class->id)
            ->whereHas('details', function ($q) use ($student) {
                $q->whereHas('studyPlan', function ($q2) use ($student) {
                    $q2->where('student_id', $student->id);
                });
            })->exists();

        if (!$isEnrolled) {
            return response()->json(['success' => false, 'message' => __('You are not enrolled in this class.')], 403);
        }

        $result = $this->aiService->generatePlan($student, $class);

        return response()->json($result);
    }

    /**
     * Generate overall study plan for the semester
     */
    public function generateOverall(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return response()->json(['success' => false, 'message' => __('Unauthorized')], 403);
        }

        // Get academic data
        $cgpaData = $this->calculationService->calculateCGPA($student);
        $classificationProgress = $this->calculationService->getClassificationProgress($student);

        // Get all grades
        $allGrades = Grade::where('student_id', $student->id)
            ->with(['academicClass.course', 'academicClass.academicYear'])
            ->get();

        $passedSubjects = $allGrades->filter(fn($grade) => in_array($grade->letter_grade, ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C']));
        $failedSubjects = $allGrades->filter(fn($grade) => in_array($grade->letter_grade, ['D+', 'D', 'E', 'F']));

        // Get current semester classes from active study plan
        $currentClasses = collect();
        $activeAcademicYear = AcademicYear::active();
        if ($activeAcademicYear) {
            $currentStudyPlan = StudyPlan::where('student_id', $student->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('status', 'approved')
                ->first();

            if ($currentStudyPlan) {
                $currentClasses = AcademicClass::whereHas('details', function ($q) use ($currentStudyPlan) {
                    $q->where('study_plan_id', $currentStudyPlan->id);
                })->with(['course', 'lecturer.user'])->get();
            }
        }

        $result = $this->aiService->generateOverallPlan(
            $student,
            $cgpaData,
            $classificationProgress,
            $passedSubjects,
            $failedSubjects,
            $currentClasses
        );

        return response()->json($result);
    }
}
