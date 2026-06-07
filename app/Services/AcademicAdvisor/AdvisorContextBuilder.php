<?php

namespace App\Services\AcademicAdvisor;

use App\Models\Student;
use App\Models\Course;
use App\Models\Grade;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use App\Models\CourseSchedule;
use App\Services\AcademicCalculationService;
use App\Services\AttendanceService;

class AdvisorContextBuilder
{
    protected AcademicCalculationService $calculationService;
    protected AttendanceService $attendanceService;

    public function __construct(
        AcademicCalculationService $calculationService,
        AttendanceService $attendanceService
    ) {
        $this->calculationService = $calculationService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Build comprehensive context for AI Advisor
     */
    public function build(Student $student): array
    {
        $student->load(['user', 'studyProgram.faculty', 'academicAdvisor.user']);

        $studyProgramKey = $this->getStudyProgramKey($student);
        $rules = $this->getStudyProgramRules($studyProgramKey);

        $academicSummary = $this->buildAcademicSummary($student);
        $courseStatuses = $this->buildCourseStatuses($student, $studyProgramKey);
        $curriculum = $this->getCurriculum($studyProgramKey);
        $schedule = $this->getActiveSchedule($student);
        $attendance = $this->getAttendanceData($student);

        return [
            'student' => [
                'name' => $student->user->name,
                'student_number' => $student->student_number,
                'study_program' => $student->studyProgram->name ?? '-',
                'faculty' => $student->studyProgram->faculty->name ?? '-',
                'batch' => $student->batch,
                'active_semester' => $this->calculateActiveSemester($student),
                'status' => $student->status,
                'academic_advisor' => $student->academicAdvisor->user->name ?? '-',
            ],
            'study_program_rules' => $rules,
            'academic_summary' => $academicSummary,
            'course_statuses' => $courseStatuses,
            'curriculum' => $curriculum,
            'schedule' => $schedule,
            'attendance' => $attendance,
            'metadata' => [
                'generated_at' => now()->toIso8601String(),
                'study_program_key' => $studyProgramKey,
            ],
        ];
    }

    /**
     * Get study program key for config lookup
     */
    protected function getStudyProgramKey(Student $student): string
    {
        $studyProgramName = strtolower($student->studyProgram->name ?? '');

        if (str_contains($studyProgramName, 'sistem informasi')) {
            return 'sistem_informasi_unri';
        }

        return 'default';
    }

    /**
     * Get rules for specific study program
     */
    protected function getStudyProgramRules(string $studyProgramKey): array
    {
        $rules = config("academic_rules.study_program.{$studyProgramKey}");

        if (!$rules) {
            $rules = config('academic_rules.default');
        }

        return [
            'graduation_total_credits' => $rules['graduation_total_credits'] ?? 144,
            'thesis_min_credits' => $rules['thesis_min_credits'] ?? 144,
            'internship_credits' => $rules['internship']['credits'] ?? 3,
            'internship_min_credits' => $rules['internship']['min_credits_required'] ?? 90,
        ];
    }

    /**
     * Build text summary of academic performance
     */
    protected function buildAcademicSummary(Student $student): array
    {
        $gpaData = $this->calculationService->calculateGPA($student);
        
        return [
            'gpa' => $gpaData['gpa'],
            'total_credits' => $gpaData['total_credits'],
            'credits_passed' => $gpaData['total_credits_passed'],
            'grade_distribution' => $this->calculationService->getGradeDistribution($student),
        ];
    }

    /**
     * Get statuses of courses (taken, passed, currently taking)
     */
    protected function buildCourseStatuses(Student $student, string $studyProgramKey): array
    {
        $grades = Grade::where('student_id', $student->id)->with('academicClass.course')->get();
        $activeStudyPlan = StudyPlan::where('student_id', $student->id)
            ->where('status', 'approved')
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->with('details.academicClass.course')
            ->first();

        $statuses = [];
        foreach ($grades as $grade) {
            $course = $grade->academicClass->course;
            $statuses[$course->course_code] = [
                'name' => $course->course_name,
                'grade' => $grade->letter_grade,
                'numeric' => $grade->numeric_grade,
                'status' => $this->isPassed($grade->letter_grade) ? 'passed' : 'failed',
            ];
        }

        if ($activeStudyPlan) {
            foreach ($activeStudyPlan->details as $detail) {
                $course = $detail->academicClass->course;
                if (!isset($statuses[$course->course_code])) {
                    $statuses[$course->course_code] = [
                        'name' => $course->course_name,
                        'status' => 'currently_taking',
                    ];
                }
            }
        }

        return $statuses;
    }

    /**
     * Get curriculum courses
     */
    protected function getCurriculum(string $studyProgramKey): array
    {
        // For now, get all courses in study program
        // In real app, this would be scoped to specific curriculum version
        return Course::orderBy('semester')->get()->map(function($course) {
            return [
                'code' => $course->course_code,
                'name' => $course->course_name,
                'credits' => $course->credits,
                'semester' => $course->semester,
            ];
        })->toArray();
    }

    /**
     * Get active schedule for student
     */
    protected function getActiveSchedule(Student $student): array
    {
        $activeStudyPlan = StudyPlan::where('student_id', $student->id)
            ->where('status', 'approved')
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->with('studyPlanDetails.academicClass.courseSchedules')
            ->first();

        if (!$activeStudyPlan) return [];

        $schedule = [];
        foreach ($activeStudyPlan->studyPlanDetails as $detail) {
            $class = $detail->academicClass;
            foreach ($class->courseSchedules as $sched) {
                $schedule[] = [
                    'course' => $class->course->course_name,
                    'day' => $sched->day,
                    'time' => $sched->start_time . ' - ' . $sched->end_time,
                    'room' => $sched->room,
                ];
            }
        }

        return $schedule;
    }

    /**
     * Get attendance summary
     */
    protected function getAttendanceData(Student $student): array
    {
        $activeStudyPlan = StudyPlan::where('student_id', $student->id)
            ->where('status', 'approved')
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->with('studyPlanDetails.academicClass')
            ->first();

        if (!$activeStudyPlan) return [];

        $data = [];
        foreach ($activeStudyPlan->studyPlanDetails as $detail) {
            $summary = $this->attendanceService->getAttendanceSummary($student->id, $detail->class_id);
            $data[$detail->academicClass->course->course_name] = $summary;
        }

        return $data;
    }

    /**
     * Helper to calculate active semester
     */
    protected function calculateActiveSemester(Student $student): int
    {
        // Simple logic: current year - batch year * 2 + (current semester is even ? 0 : 1)
        $currentYear = date('Y');
        $currentMonth = date('n');
        $batchYear = $student->batch;
        
        $years = $currentYear - $batchYear;
        $semester = $years * 2;
        
        if ($currentMonth >= 8 || $currentMonth <= 1) { // Odd semester (Aug - Jan)
            $semester += 1;
        } else { // Even semester (Feb - Jul)
            $semester += 2;
        }

        return max(1, $semester);
    }

    protected function isPassed(?string $grade): bool
    {
        if (!$grade) return false;
        return in_array(strtoupper($grade), ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C']);
    }
}
