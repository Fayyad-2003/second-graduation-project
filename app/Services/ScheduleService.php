<?php

namespace App\Services;

use App\Models\CourseSchedule;
use App\Models\AcademicClass;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * Check for schedule conflicts for a lecturer
     */
    public function checkLecturerConflict(Lecturer $lecturer, string $day, string $startTime, string $endTime, ?int $excludeId = null): ?CourseSchedule
    {
        $classIds = $lecturer->classes()->pluck('id');

        return CourseSchedule::whereIn('class_id', $classIds)
            ->where('day', $day)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->get()
            ->first(function ($schedule) use ($startTime, $endTime) {
                return $this->timeOverlaps($schedule->start_time, $schedule->end_time, $startTime, $endTime);
            });
    }

    /**
     * Check for room conflicts
     */
    public function checkRoomConflict(string $rooms, string $day, string $startTime, string $endTime, ?int $excludeId = null): ?CourseSchedule
    {
        return CourseSchedule::where('rooms', $rooms)
            ->where('day', $day)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->get()
            ->first(function ($schedule) use ($startTime, $endTime) {
                return $this->timeOverlaps($schedule->start_time, $schedule->end_time, $startTime, $endTime);
            });
    }

    /**
     * Get weekly schedule for a student
     */
    public function getStudentSchedule(Student $student): Collection
    {
        $studyPlan = $student->studyPlans()
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$studyPlan) {
            return collect();
        }

        $classIds = $studyPlan->details->pluck('class_id');

        return CourseSchedule::whereIn('class_id', $classIds)
            ->with(['academicClass.course', 'academicClass.lecturer.user'])
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get weekly schedule for a lecturer
     */
    public function getLecturerSchedule(Lecturer $lecturer): Collection
    {
        $classIds = $lecturer->classes()->pluck('id');

        return CourseSchedule::whereIn('class_id', $classIds)
            ->with('academicClass.course')
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Check if two time ranges overlap
     */
    private function timeOverlaps($start1, $end1, $start2, $end2): bool
    {
        return !($end1 <= $start2 || $start1 >= $end2);
    }
}
