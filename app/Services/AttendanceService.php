<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Student;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\CourseSchedule;
use App\Models\StudyPlanDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Create a new meeting (meeting session)
     */
    public function createMeeting(int $courseScheduleId, int $meetingNumber, string $date, ?string $topic = null): Meeting
    {
        return Meeting::create([
            'course_schedule_id' => $courseScheduleId,
            'meeting_number' => $meetingNumber,
            'date' => $date,
            'topic' => $topic,
            'status' => Meeting::STATUS_COMPLETED,
        ]);
    }

    /**
     * Record attendance for a meeting
     */
    public function recordAttendance(int $meetingId, array $attendanceData): void
    {
        DB::transaction(function () use ($meetingId, $attendanceData) {
            foreach ($attendanceData as $studentId => $status) {
                Attendance::updateOrCreate(
                    [
                        'meeting_id' => $meetingId,
                        'student_id' => $studentId,
                    ],
                    [
                        'status' => $status,
                        'attendance_time' => $status === Attendance::STATUS_PRESENT ? now()->format('H:i:s') : null,
                    ]
                );
            }
        });
    }

    /**
     * Get attendance summary for a student in a class
     */
    public function getAttendanceSummary(int $studentId, int $classId): array
    {
        $meetingIds = Meeting::byClass($classId)->completed()->pluck('id');

        $attendances = Attendance::where('student_id', $studentId)
            ->whereIn('meeting_id', $meetingIds)
            ->get();

        $totalMeetings = $meetingIds->count();
        $present = $attendances->where('status', Attendance::STATUS_PRESENT)->count();
        $sick = $attendances->where('status', Attendance::STATUS_SICK)->count();
        $excused = $attendances->where('status', Attendance::STATUS_EXCUSED)->count();
        $absent = $attendances->where('status', Attendance::STATUS_ABSENT)->count();

        $percentage = $totalMeetings > 0
            ? round(($present / $totalMeetings) * 100, 1)
            : 0;

        return [
            'total_meetings' => $totalMeetings,
            'present' => $present,
            'sick' => $sick,
            'excused' => $excused,
            'absent' => $absent,
            'percentage' => $percentage,
        ];
    }

    /**
     * Get attendance summary for all students in a class
     */
    public function getAttendanceByClass(int $classId): Collection
    {
        $studentList = $this->getStudentsByClass($classId);

        return $studentList->map(function ($student) use ($classId) {
            $summary = $this->getAttendanceSummary($student->id, $classId);
            return [
                'student' => $student,
                'summary' => $summary,
            ];
        });
    }

    /**
     * Get all classes taught by a lecturer
     */
    public function getClassesByLecturer(int $lecturerId): Collection
    {
        return AcademicClass::where('lecturer_id', $lecturerId)
            ->with(['course', 'studyPlanDetails'])
            ->get();
    }

    /**
     * Get all meetings for a class
     */
    public function getMeetingsByClass(int $classId): Collection
    {
        return Meeting::byClass($classId)
            ->orderBy('meeting_number')
            ->get();
    }

    /**
     * Get next meeting number for a schedule
     */
    public function getNextMeetingNumber(int $scheduleId): int
    {
        $lastMeeting = Meeting::where('course_schedule_id', $scheduleId)
            ->orderBy('meeting_number', 'desc')
            ->first();

        return ($lastMeeting?->meeting_number ?? 0) + 1;
    }

    /**
     * Get all students enrolled in a class
     */
    public function getStudentsByClass(int $classId): Collection
    {
        return Student::whereHas('studyPlans', function ($q) use ($classId) {
            $q->where('status', 'approved')
                ->whereHas('details', fn($q2) => $q2->where('class_id', $classId));
        })->with('user')->get();
    }
}
