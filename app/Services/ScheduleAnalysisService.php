<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\CourseSchedule;
use App\Models\Student;
use Illuminate\Support\Collection;

class ScheduleAnalysisService
{
    /**
     * Analyze all schedules in the active semester for conflicts
     */
    public function analyzeActiveSemester(): array
    {
        $activeYear = AcademicYear::active();
        if (!$activeYear) {
            return [
                'room_conflicts' => [],
                'lecturer_conflicts' => [],
                'student_conflicts' => [],
                'active_year' => null
            ];
        }

        $schedules = CourseSchedule::whereHas('class', function ($query) use ($activeYear) {
            $query->where('academic_year_id', $activeYear->id);
        })->with(['class.course', 'class.lecturer.user'])->get();

        return [
            'room_conflicts' => $this->detectRoomConflicts($schedules),
            'lecturer_conflicts' => $this->detectLecturerConflicts($schedules),
            'student_conflicts' => $this->detectStudentConflicts($activeYear),
            'active_year' => $activeYear
        ];
    }

    /**
     * Detect room conflicts: same room, same day, overlapping time
     */
    protected function detectRoomConflicts(Collection $schedules): array
    {
        $conflicts = [];
        $grouped = $schedules->groupBy(['room', 'day']);

        foreach ($grouped as $room => $days) {
            if (!$room) continue;
            foreach ($days as $day => $daySchedules) {
                $count = $daySchedules->count();
                for ($i = 0; $i < $count; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($daySchedules[$i]->conflictsWith($daySchedules[$j])) {
                            $conflicts[] = [
                                'room' => $room,
                                'day' => $day,
                                'schedules' => [$daySchedules[$i], $daySchedules[$j]]
                            ];
                        }
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Detect lecturer conflicts: same lecturer, same day, overlapping time
     */
    protected function detectLecturerConflicts(Collection $schedules): array
    {
        $conflicts = [];
        $grouped = $schedules->groupBy(function ($schedule) {
            return $schedule->class->lecturer_id;
        });

        foreach ($grouped as $lecturerId => $lecturerSchedules) {
            $dayGrouped = $lecturerSchedules->groupBy('day');
            foreach ($dayGrouped as $day => $daySchedules) {
                $count = $daySchedules->count();
                for ($i = 0; $i < $count; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($daySchedules[$i]->conflictsWith($daySchedules[$j])) {
                            $conflicts[] = [
                                'lecturer' => $daySchedules[$i]->class->lecturer,
                                'day' => $day,
                                'schedules' => [$daySchedules[$i], $daySchedules[$j]]
                            ];
                        }
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Detect student conflicts: student enrolled in overlapping classes
     */
    protected function detectStudentConflicts(AcademicYear $activeYear): array
    {
        $conflicts = [];

        // Only check students with approved study plans in this year
        $students = Student::whereHas('studyPlans', function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear->id)
                ->where('status', 'approved');
        })->with(['user', 'studyPlans' => function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear->id)
                ->where('status', 'approved')
                ->with('details.academicClass.courseSchedules.class.course');
        }])->get();

        foreach ($students as $student) {
            $studyPlan = $student->studyPlans->first();
            $allSchedules = collect();

            foreach ($studyPlan->details as $detail) {
                foreach ($detail->academicClass->courseSchedules as $schedule) {
                    $allSchedules->push($schedule);
                }
            }

            $dayGrouped = $allSchedules->groupBy('day');
            foreach ($dayGrouped as $day => $daySchedules) {
                $count = $daySchedules->count();
                for ($i = 0; $i < $count; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if ($daySchedules[$i]->conflictsWith($daySchedules[$j])) {
                            $conflicts[] = [
                                'student' => $student,
                                'day' => $day,
                                'schedules' => [$daySchedules[$i], $daySchedules[$j]]
                            ];
                        }
                    }
                }
            }
        }

        return $conflicts;
    }
}
