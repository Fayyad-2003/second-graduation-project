<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Attendance summary for all courses
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Get all classes from approved study plan with academic year info
        $classList = AcademicClass::whereHas('details', function ($q) use ($student) {
            $q->whereHas(
                'studyPlan',
                fn($q2) => $q2
                    ->where('student_id', $student->id)
                    ->where('status', 'approved')
            );
        })->with(['course', 'lecturer.user', 'schedule', 'details.studyPlan.academicYear'])->get();

        // Get summary for each class and group by semester
        $summaryList = $classList->map(function ($class) use ($student) {
            $studyPlan = $class->details->first()?->studyPlan;
            $academicYear = $studyPlan?->academicYear;
            return [
                'class' => $class,
                'summary' => $this->attendanceService->getAttendanceSummary($student->id, $class->id),
                'semester' => $academicYear ? $academicYear->year . ' ' . ucfirst(__($academicYear->semester)) : __('Unknown'),
                'semester_order' => $academicYear ? $academicYear->id : 0,
            ];
        });

        // Group by semester - sort by semester_order desc first so latest semester is first
        $summaryBySemester = $summaryList->sortByDesc('semester_order')->groupBy('semester');

        return view('student.attendance.index', compact('student', 'summaryList', 'summaryBySemester'));
    }

    /**
     * Attendance detail per class
     */
    public function show(AcademicClass $class)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        // Verify student is enrolled in this class
        $isEnrolled = $class->details()
            ->whereHas(
                'studyPlan',
                fn($q) => $q
                    ->where('student_id', $student->id)
                    ->where('status', 'approved')
            )->exists();

        if (!$isEnrolled) {
            abort(403, __('You are not enrolled in this class.'));
        }

        $class->load(['course', 'lecturer.user']);

        // Get meetings list with attendance
        $meetingList = $this->attendanceService->getMeetingsByClass($class->id);

        // Get attendance for this student
        $attendanceData = Attendance::where('student_id', $student->id)
            ->whereIn('meeting_id', $meetingList->pluck('id'))
            ->get()
            ->keyBy('meeting_id');

        $summary = $this->attendanceService->getAttendanceSummary($student->id, $class->id);

        return view('student.attendance.show', compact('class', 'student', 'meetingList', 'attendanceData', 'summary'));
    }
}
