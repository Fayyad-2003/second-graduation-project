<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\CourseSchedule;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceInputController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * List of classes taught
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;
        
        if (!$lecturer) {
            abort(403, __('Unauthorized'));
        }

        $classList = $this->attendanceService->getClassesByLecturer($lecturer->id);
        
        // Group by semester
        $classesGrouped = $classList->groupBy(fn($class) => $class->course->semester ?? 0);
        
        // Get semester list for filter
        $semesterList = $classList->pluck('course.semester')->unique()->sort()->values();

        return view('lecturer.attendance-input.index', compact('classList', 'classesGrouped', 'semesterList'));
    }

    /**
     * Attendance detail per class
     */
    public function showClass(AcademicClass $class)
    {
        $lecturer = Auth::user()->lecturer;
        
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403, __('You do not have access to this class'));
        }

        $class->load(['course', 'schedule']);
        $meetingList = $this->attendanceService->getMeetingsByClass($class->id);
        $studentSummary = $this->attendanceService->getAttendanceByClass($class->id);

        return view('lecturer.attendance-input.class', compact('class', 'meetingList', 'studentSummary'));
    }

    /**
     * Form to create new meeting
     */
    public function createMeeting(AcademicClass $class)
    {
        $lecturer = Auth::user()->lecturer;
        
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403, __('You do not have access to this class'));
        }

        $class->load(['course', 'schedule']);
        $scheduleList = $class->schedule;
        $nextMeetingNumber = [];
        
        foreach ($scheduleList as $schedule) {
            $nextMeetingNumber[$schedule->id] = $this->attendanceService->getNextMeetingNumber($schedule->id);
        }

        return view('lecturer.attendance-input.meeting-create', compact('class', 'scheduleList', 'nextMeetingNumber'));
    }

    /**
     * Store new meeting
     */
    public function storeMeeting(Request $request, AcademicClass $class)
    {
        $lecturer = Auth::user()->lecturer;
        
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403, __('You do not have access to this class'));
        }

        $validated = $request->validate([
            'course_schedule_id' => 'required|exists:course_schedules,id',
            'meeting_number' => 'required|integer|min:1|max:16',
            'date' => 'required|date',
            'topic' => 'nullable|string|max:255',
        ]);

        // Check schedule belongs to this class
        $schedule = CourseSchedule::findOrFail($validated['course_schedule_id']);
        if ($schedule->class_id !== $class->id) {
            abort(403, __('Invalid schedule'));
        }

        // Check duplicate meeting_number
        $exists = Meeting::where('course_schedule_id', $validated['course_schedule_id'])
            ->where('meeting_number', $validated['meeting_number'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['meeting_number' => __('Meeting :number already exists', ['number' => $validated['meeting_number']])])
                ->withInput();
        }

        $meeting = $this->attendanceService->createMeeting(
            $validated['course_schedule_id'],
            $validated['meeting_number'],
            $validated['date'],
            $validated['topic']
        );

        return redirect()->route('lecturers.attendance-input.input', $meeting)
            ->with('success', __('Meeting successfully created. Please input attendance.'));
    }

    /**
     * Form to input attendance
     */
    public function inputAttendance(Meeting $meeting)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $meeting->courseSchedule->class;
        
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403, __('You do not have access to this meeting'));
        }

        $meeting->load(['courseSchedule.class.course', 'attendances']);
        $studentList = $this->attendanceService->getStudentsByClass($class->id);
        
        // Get existing attendance data
        $existingAttendance = $meeting->attendances->keyBy('student_id');

        return view('lecturer.attendance-input.input', compact('meeting', 'class', 'studentList', 'existingAttendance'));
    }

    /**
     * Store attendance
     */
    public function storeAttendance(Request $request, Meeting $meeting)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $meeting->courseSchedule->class;
        
        if ($class->lecturer_id !== $lecturer->id) {
            abort(403, __('You do not have access to this meeting'));
        }

        $validated = $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,sick,excused,absent',
        ]);

        $this->attendanceService->recordAttendance($meeting->id, $validated['attendance']);

        return redirect()->route('lecturers.attendance-input.class', $class)
            ->with('success', __('Attendance successfully saved'));
    }
}
