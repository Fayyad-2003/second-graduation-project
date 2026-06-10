<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AcademicCalculationService;
use App\Models\StudyPlan;
use App\Models\AcademicYear;
use App\Models\SemesterCalendar;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected AcademicCalculationService $calculationService;

    public function __construct(AcademicCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            // Instead of aborting, show a message and empty data
            return view('student.dashboard.index', [
                'user' => $user,
                'student' => null,
                'gpaData' => null,
                'gpaHistory' => collect(),
                'currentSemesterGpa' => null,
                'maxCredits' => 0,
                'currentStudyPlan' => null,
                'gradeDistribution' => [],
                'greeting' => __('Welcome'),
                'activeAcademicYear' => null,
                'upcomingEvents' => collect()
            ]);
        }

        $student->load(['studyProgram.faculty', 'academicAdvisor.user']);

        // Calculate GPA
        $gpaData = $this->calculationService->calculateCGPA($student);

        // Get semester GPA history for chart
        $gpaHistory = $this->calculationService->getGPAHistory($student);

        // Current semester GPA = last semester with actual grades (GPA > 0)
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $semestersWithGrades = $gpaHistory->filter(fn($s) => $s['gpa'] > 0);
        $lastSemesterWithGrades = $semestersWithGrades->last();
        $currentSemesterGpa = $lastSemesterWithGrades ? ['gpa' => $lastSemesterWithGrades['gpa'], 'total_credits' => $lastSemesterWithGrades['total_credits']] : null;

        // Max credits for next semester based on last semester GPA
        $lastSemesterGpa = $lastSemesterWithGrades ? ($lastSemesterWithGrades['gpa'] ?? 0) : 0;
        $maxCredits = $this->calculationService->getMaxCredits($lastSemesterGpa);

        // Get current study plan status
        $currentStudyPlan = StudyPlan::where('student_id', $student->id)
            ->where('academic_year_id', $activeAcademicYear?->id)
            ->first();

        // Grade distribution
        $gradeDistribution = $this->calculationService->getGradeDistribution($student);

        // Greeting based on time
        $hour = now()->hour;
        if ($hour < 12) {
            $greeting = __('Good Morning');
        } elseif ($hour < 15) {
            $greeting = __('Good Afternoon');
        } elseif ($hour < 18) {
            $greeting = __('Good Evening');
        } else {
            $greeting = __('Good Night');
        }

        // GPA Warning Level
        $gpaWarningLevel = $this->calculationService->getGpaWarningLevel($gpaData['gpa']);

        // Upcoming Calendar Events
        $upcomingEvents = SemesterCalendar::where('date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->orderBy('date', 'asc')
            ->take(10)
            ->get();

        return view('student.dashboard.index', [
            'user' => $user,
            'student' => $student,
            'gpaData' => $gpaData,
            'gpaHistory' => $gpaHistory,
            'currentSemesterGpa' => $currentSemesterGpa,
            'maxCredits' => $maxCredits,
            'currentStudyPlan' => $currentStudyPlan,
            'gradeDistribution' => $gradeDistribution,
            'greeting' => $greeting,
            'activeAcademicYear' => $activeAcademicYear,
            'gpaWarningLevel' => $gpaWarningLevel,
            'upcomingEvents' => $upcomingEvents
        ]);
    }
}
