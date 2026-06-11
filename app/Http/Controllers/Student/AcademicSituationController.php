<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Grade;
use App\Models\StudyPlan;
use App\Services\AcademicCalculationService;
use App\Services\AttendanceService;
use App\Services\AcademicRecommendationsAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicSituationController extends Controller
{
    protected AcademicCalculationService $calculationService;
    protected AttendanceService $attendanceService;
    protected AcademicRecommendationsAiService $recommendationsService;

    public function __construct(
        AcademicCalculationService $calculationService,
        AttendanceService $attendanceService,
        AcademicRecommendationsAiService $recommendationsService
    ) {
        $this->calculationService = $calculationService;
        $this->attendanceService = $attendanceService;
        $this->recommendationsService = $recommendationsService;
    }

    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Get current active academic year
        $activeAcademicYear = AcademicYear::active();

        // Get CGPA and overall data
        $cgpaData = $this->calculationService->calculateCGPA($student);
        $gpaHistory = $this->calculationService->getGPAHistory($student);
        $gradeDistribution = $this->calculationService->getGradeDistribution($student);
        $classificationProgress = $this->calculationService->getClassificationProgress($student);

        // Get all student's grades with details
        $allGrades = Grade::where('student_id', $student->id)
            ->with(['academicClass.course', 'academicClass.academicYear'])
            ->get();

        // Classify subjects into passed and failed
        $passedSubjects = $allGrades->filter(fn($grade) => in_array($grade->letter_grade, ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-']));
        $failedSubjects = $allGrades->filter(fn($grade) => in_array($grade->letter_grade, ['D+', 'D', 'E', 'F']));

        // Get current semester's enrolled classes
        $currentClasses = collect();
        $classAttendanceSummaries = collect();
        $classAssignmentSummaries = collect();

        if ($activeAcademicYear) {
            $currentStudyPlan = StudyPlan::where('student_id', $student->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('status', 'approved')
                ->first();

            if ($currentStudyPlan) {
                $currentClasses = AcademicClass::whereHas('details', function ($q) use ($currentStudyPlan) {
                    $q->where('study_plan_id', $currentStudyPlan->id);
                })->with(['course', 'lecturer.user'])->get();

                // Get attendance summaries for current classes
                $classAttendanceSummaries = $currentClasses->mapWithKeys(function ($class) use ($student) {
                    $summary = $this->attendanceService->getAttendanceSummary($student->id, $class->id);
                    return [$class->id => $summary];
                });

                // Get assignment summaries for current classes
                $classAssignmentSummaries = $currentClasses->mapWithKeys(function ($class) use ($student) {
                    $assignments = Assignment::where('class_id', $class->id)
                        ->where('is_active', true)
                        ->with(['submissions' => fn($q) => $q->where('student_id', $student->id)])
                        ->get();

                    $submitted = $assignments->filter(fn($a) => $a->submissions->isNotEmpty())->count();
                    $notSubmitted = $assignments->filter(fn($a) => $a->submissions->isEmpty())->count();

                    return [
                        $class->id => [
                            'total' => $assignments->count(),
                            'submitted' => $submitted,
                            'not_submitted' => $notSubmitted,
                        ]
                    ];
                });
            }
        }

        // Generate a situation report summary
        $reportSummary = $this->generateSituationReport(
            $cgpaData,
            $gpaHistory,
            $passedSubjects,
            $failedSubjects,
            $classAttendanceSummaries,
            $classAssignmentSummaries
        );

        return view('student.academic-situation.index', compact(
            'student',
            'activeAcademicYear',
            'cgpaData',
            'gpaHistory',
            'gradeDistribution',
            'classificationProgress',
            'allGrades',
            'passedSubjects',
            'failedSubjects',
            'currentClasses',
            'classAttendanceSummaries',
            'classAssignmentSummaries',
            'reportSummary'
        ));
    }

    protected function generateSituationReport(
        $cgpaData,
        $gpaHistory,
        $passedSubjects,
        $failedSubjects,
        $classAttendanceSummaries,
        $classAssignmentSummaries
    ) {
        $currentGpa = $cgpaData['gpa'] ?? 0;
        $totalCredits = $cgpaData['total_credits'] ?? 0;
        $passedCount = $passedSubjects->count();
        $failedCount = $failedSubjects->count();

        // Calculate overall attendance rate
        $totalPresent = 0;
        $totalMeetings = 0;
        foreach ($classAttendanceSummaries as $summary) {
            $totalPresent += $summary['present'] ?? 0;
            $totalMeetings += $summary['total_meetings'] ?? 0;
        }
        $attendanceRate = $totalMeetings > 0 ? round(($totalPresent / $totalMeetings) * 100, 2) : 0;

        // Calculate overall assignment submission rate
        $totalAssignments = 0;
        $totalSubmitted = 0;
        foreach ($classAssignmentSummaries as $summary) {
            $totalAssignments += $summary['total'] ?? 0;
            $totalSubmitted += $summary['submitted'] ?? 0;
        }
        $submissionRate = $totalAssignments > 0 ? round(($totalSubmitted / $totalAssignments) * 100, 2) : 0;

        // Determine situation status
        if ($currentGpa >= 3.5 && $attendanceRate >= 80 && $submissionRate >= 80 && $failedCount === 0) {
            $status = 'excellent';
            $statusText = __('Excellent');
        } elseif ($currentGpa >= 3.0 && $attendanceRate >= 70 && $submissionRate >= 70 && $failedCount <= 1) {
            $status = 'good';
            $statusText = __('Good');
        } elseif ($currentGpa >= 2.0 && $attendanceRate >= 60 && $submissionRate >= 60 && $failedCount <= 3) {
            $status = 'fair';
            $statusText = __('Fair');
        } else {
            $status = 'needs_attention';
            $statusText = __('Needs Attention');
        }

        return [
            'status' => $status,
            'status_text' => $statusText,
            'gpa' => $currentGpa,
            'total_credits' => $totalCredits,
            'passed_subjects' => $passedCount,
            'failed_subjects' => $failedCount,
            'attendance_rate' => $attendanceRate,
            'submission_rate' => $submissionRate,
        ];
    }

    public function generateRecommendations()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Get current active academic year
        $activeAcademicYear = AcademicYear::active();

        // Get CGPA and overall data
        $cgpaData = $this->calculationService->calculateCGPA($student);
        $gpaHistory = $this->calculationService->getGPAHistory($student);

        // Get all student's grades with details
        $allGrades = Grade::where('student_id', $student->id)
            ->with(['academicClass.course', 'academicClass.academicYear'])
            ->get();

        // Classify subjects into passed and failed
        $passedSubjects = $allGrades->filter(fn($grade) => in_array($grade->letter_grade, ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-']));
        $failedSubjects = $allGrades->filter(fn($grade) => in_array($grade->letter_grade, ['D+', 'D', 'E', 'F']));

        // Get current semester's enrolled classes
        $currentClasses = collect();
        $classAttendanceSummaries = collect();
        $classAssignmentSummaries = collect();

        if ($activeAcademicYear) {
            $currentStudyPlan = StudyPlan::where('student_id', $student->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('status', 'approved')
                ->first();

            if ($currentStudyPlan) {
                $currentClasses = AcademicClass::whereHas('details', function ($q) use ($currentStudyPlan) {
                    $q->where('study_plan_id', $currentStudyPlan->id);
                })->with(['course', 'lecturer.user'])->get();

                // Get attendance summaries for current classes
                $classAttendanceSummaries = $currentClasses->mapWithKeys(function ($class) use ($student) {
                    $summary = $this->attendanceService->getAttendanceSummary($student->id, $class->id);
                    return [$class->id => $summary];
                });

                // Get assignment summaries for current classes
                $classAssignmentSummaries = $currentClasses->mapWithKeys(function ($class) use ($student) {
                    $assignments = Assignment::where('class_id', $class->id)
                        ->where('is_active', true)
                        ->with(['submissions' => fn($q) => $q->where('student_id', $student->id)])
                        ->get();

                    $submitted = $assignments->filter(fn($a) => $a->submissions->isNotEmpty())->count();
                    $notSubmitted = $assignments->filter(fn($a) => $a->submissions->isEmpty())->count();

                    return [
                        $class->id => [
                            'total' => $assignments->count(),
                            'submitted' => $submitted,
                            'not_submitted' => $notSubmitted,
                        ]
                    ];
                });
            }
        }

        // Generate a situation report summary
        $reportSummary = $this->generateSituationReport(
            $cgpaData,
            $gpaHistory,
            $passedSubjects,
            $failedSubjects,
            $classAttendanceSummaries,
            $classAssignmentSummaries
        );

        // Generate AI recommendations
        $recommendations = $this->recommendationsService->generateRecommendations(
            $student,
            $cgpaData,
            $reportSummary,
            $passedSubjects,
            $failedSubjects,
            $currentClasses,
            $classAttendanceSummaries,
            $classAssignmentSummaries
        );

        return response()->json($recommendations);
    }
}
