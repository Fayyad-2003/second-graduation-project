<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\AcademicCalculationService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class GpaWarningController extends Controller
{
    protected AcademicCalculationService $calculationService;
    protected NotificationService $notificationService;

    public function __construct(
        AcademicCalculationService $calculationService,
        NotificationService $notificationService
    ) {
        $this->calculationService = $calculationService;
        $this->notificationService = $notificationService;
    }

    /**
     * Display at-risk students dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $facultyId = $user->faculty_id;

        // Build query, scoped to faculty for admin_faculty
        $query = Student::with(['user', 'studyProgram.faculty', 'academicAdvisor.user'])
            ->where('status', 'active');

        if (!$isSuperAdmin && $facultyId) {
            $query->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Filter by study program
        if ($studyProgramId = $request->get('study_program_id')) {
            $query->where('study_program_id', $studyProgramId);
        }

        // Filter by batch
        if ($batch = $request->get('batch')) {
            $query->where('batch', $batch);
        }

        $cautionThreshold = config('system.gpa_warning.caution', 2.50);

        // Get all active students with their CGPA, filter at-risk
        $students = $query->get();

        $atRiskStudents = $students->map(function ($student) {
            $cgpa = $this->calculationService->calculateCGPA($student);
            $level = $this->calculationService->getGpaWarningLevel($cgpa['gpa']);

            if (!$level) {
                return null;
            }

            return [
                'student' => $student,
                'gpa' => $cgpa['gpa'],
                'total_credits' => $cgpa['total_credits'],
                'level' => $level,
            ];
        })->filter()->sortBy('gpa')->values();

        // Counts
        $dangerCount = $atRiskStudents->where('level', 'danger')->count();
        $cautionCount = $atRiskStudents->where('level', 'caution')->count();
        $totalAtRisk = $atRiskStudents->count();

        // Filter by level if requested
        if ($levelFilter = $request->get('level')) {
            $atRiskStudents = $atRiskStudents->where('level', $levelFilter)->values();
        }

        // Filter options
        $studyProgramList = \App\Models\StudyProgram::orderBy('name')->get();
        $batchList = Student::distinct()->pluck('batch')->sort()->reverse();

        return view('admin.gpa-warning.index', compact(
            'atRiskStudents',
            'dangerCount',
            'cautionCount',
            'totalAtRisk',
            'studyProgramList',
            'batchList',
            'cautionThreshold'
        ));
    }

    /**
     * Send GPA warning notifications
     */
    public function notify(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $students = Student::whereIn('id', $validated['student_ids'])->get();

        $items = $students->map(function ($student) {
            $cgpa = $this->calculationService->calculateCGPA($student);
            $level = $this->calculationService->getGpaWarningLevel($cgpa['gpa']);

            if (!$level) {
                return null;
            }

            return [
                'student' => $student,
                'gpa' => $cgpa['gpa'],
                'level' => $level,
            ];
        })->filter();

        $count = $this->notificationService->sendBulkGpaWarnings($items);

        return back()->with('success', __(':count GPA warning notifications sent successfully.', ['count' => $count]));
    }
}
