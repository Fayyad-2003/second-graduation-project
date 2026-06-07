<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Services\GradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingController extends Controller
{
    protected $gradingService;

    public function __construct(GradingService $gradingService)
    {
        $this->gradingService = $gradingService;
    }

    public function index(Request $request)
    {
        $lecturer = Auth::user()->lecturer;
        $query = $lecturer->classes()->with(['course', 'studyPlanDetails']);

        // Filter by semester
        if ($request->filled('semester')) {
            $query->whereHas('course', fn($q) => $q->where('semester', $request->semester));
        }

        // Search by course name or course code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                    ->orWhere('course_code', 'like', "%{$search}%");
            });
        }

        $teachingClasses = $query->get();

        // Group by semester
        $classGrouped = $teachingClasses->groupBy(fn($class) => $class->course->semester);

        // Get available semesters from already loaded data (avoid N+1)
        $semesterList = $lecturer->classes()
            ->with('course')
            ->get()
            ->pluck('course.semester')
            ->unique()
            ->sort()
            ->values();

        if ($request->ajax()) {
            return view('lecturer.grading._cards', compact('teachingClasses', 'classGrouped'))->render();
        }

        return view('lecturer.grading.index', compact('teachingClasses', 'classGrouped', 'semesterList'));
    }

    public function show($classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with(['course', 'studyPlanDetails.studyPlan.student.user', 'grades'])->findOrFail($classId);

        return view('lecturer.grading.show', compact('class'));
    }

    public function store(Request $request, $classId)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $this->gradingService->bulkInputGrades($classId, $request->grades);
            return redirect()->back()->with('success', __('Grades successfully saved'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
