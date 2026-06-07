<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\StudyPlan;
use App\Models\Grade;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;
        
        if (!$lecturer) {
            abort(403, __('Unauthorized'));
        }

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        // Get classes taught (filter by active academic year if available)
        $classQuery = AcademicClass::where('lecturer_id', $lecturer->id)
            ->with(['course', 'studyPlanDetails.studyPlan.student', 'courseSchedules']);
        
        if ($activeAcademicYear) {
            $classQuery->where(function($q) use ($activeAcademicYear) {
                $q->where('academic_year_id', $activeAcademicYear->id)
                  ->orWhereNull('academic_year_id'); // Include legacy classes without academic year
            });
        }
        
        $classList = $classQuery->get();

        // Stats
        $totalClasses = $classList->count();
        $totalStudents = $classList->sum(fn($class) => $class->studyPlanDetails->count());
        
        // Get total grades already inputted
        $gradesInputted = Grade::whereIn('class_id', $classList->pluck('id'))
            ->whereNotNull('numeric_grade')
            ->count();
        $totalGrades = $classList->sum(fn($class) => $class->studyPlanDetails->count());
        $gradePercentage = $totalGrades > 0 ? round(($gradesInputted / $totalGrades) * 100) : 0;

        // Attendance stats
        $meetingList = Meeting::whereHas('courseSchedule', fn($q) => $q->whereIn('class_id', $classList->pluck('id')))->get();
        $totalMeetings = $meetingList->count();
        
        $attendanceStats = Attendance::whereIn('meeting_id', $meetingList->pluck('id'))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Advised students
        $advisedStudents = $lecturer->advisedStudents()->with('user')->get();
        $pendingStudyPlans = $advisedStudents->isNotEmpty() 
            ? StudyPlan::whereIn('student_id', $advisedStudents->pluck('id'))
                ->where('status', 'pending')
                ->count()
            : 0;

        // Recent activities (recent grades input)
        $recentGrades = Grade::whereIn('class_id', $classList->pluck('id'))
            ->whereNotNull('numeric_grade')
            ->with(['academicClass.course', 'student.user'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Classes with schedule today
        $today = now()->format('l');
        
        $todayClasses = $classList->filter(function ($class) use ($today) {
            return $class->courseSchedules->contains('day', $today);
        });

        return view('lecturer.dashboard.index', [
            'lecturer' => $lecturer,
            'activeYear' => $activeAcademicYear,
            'classList' => $classList,
            'totalClass' => $totalClasses,
            'totalStudents' => $totalStudents,
            'gradesInputted' => $gradesInputted,
            'totalGrade' => $totalGrades,
            'gradePercentage' => $gradePercentage,
            'totalMeetings' => $totalMeetings,
            'attendanceStats' => $attendanceStats,
            'advisedStudents' => $advisedStudents,
            'pendingStudyPlans' => $pendingStudyPlans,
            'recentGrades' => $recentGrades,
            'todayClasses' => $todayClasses,
            'today' => $today
        ]);
    }
}
