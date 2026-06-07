<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Services\SubjectSearchAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectSearchAiController extends Controller
{
    protected SubjectSearchAiService $aiService;

    public function __construct(SubjectSearchAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $student = Auth::user()->student;
        
        // Get all classes the student is enrolled in (approved study plan)
        $classes = AcademicClass::whereHas('details', function ($query) use ($student) {
            $query->whereHas('studyPlan', function ($q) use ($student) {
                $q->where('student_id', $student->id)
                  ->where('status', 'approved');
            });
        })->with('course')->get();

        return view('student.subject-search.index', compact('classes'));
    }

    public function ask(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'question' => 'required|string|min:3',
        ]);

        $student = Auth::user()->student;
        $class = AcademicClass::findOrFail($request->class_id);

        // Verify enrollment
        $isEnrolled = $class->details()->whereHas('studyPlan', function ($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->where('status', 'approved');
        })->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => __('You are not enrolled in this class.'),
            ], 403);
        }

        $result = $this->aiService->askQuestion($student, $class, $request->question);

        return response()->json($result);
    }
}
