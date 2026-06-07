<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Services\StudyPlanAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyPlanAiController extends Controller
{
    protected StudyPlanAiService $aiService;

    public function __construct(StudyPlanAiService $aiService)
    {
        $this->aiService = $aiService;
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
}
