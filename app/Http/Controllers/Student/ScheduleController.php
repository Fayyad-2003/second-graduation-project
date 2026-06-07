<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display class schedule based on student's study plan
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            // Show empty schedule page instead of aborting
            return view('student.schedule.index', [
                'student' => null,
                'scheduleByDay' => collect(),
                'activeAcademicYear' => null,
                'message' => 'Student record not found. Please contact administration.'
            ]);
        }

        // Get active academic year
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeAcademicYear) {
            return view('student.schedule.index', [
                'student' => $student,
                'scheduleByDay' => collect(),
                'activeAcademicYear' => null,
                'message' => __('No active academic year')
            ]);
        }

        // Get classes from approved study plan for current semester
        $classList = AcademicClass::whereHas('details', function ($q) use ($student, $activeAcademicYear) {
            $q->whereHas('studyPlan', fn($q2) => $q2
                ->where('student_id', $student->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('status', 'approved')
            );
        })->with(['course', 'lecturer.user', 'schedule'])->get();

        // Group schedule by day
        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $scheduleByDay = collect();
        
        foreach ($dayOrder as $day) {
            $todaySchedule = collect();
            
            foreach ($classList as $class) {
                foreach ($class->schedule as $schedule) {
                    if ($schedule->day === $day) {
                        $todaySchedule->push([
                            'class' => $class,
                            'schedule' => $schedule,
                        ]);
                    }
                }
            }
            
            if ($todaySchedule->isNotEmpty()) {
                // Sort by start_time
                $todaySchedule = $todaySchedule->sortBy(fn($s) => $s['schedule']->start_time);
                $scheduleByDay[$day] = $todaySchedule;
            }
        }

        return view('student.schedule.index', compact('student', 'scheduleByDay', 'activeAcademicYear', 'dayOrder'));
    }
}

