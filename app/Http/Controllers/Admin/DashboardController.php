<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\AcademicClass;
use App\Models\Grade;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $facultyId = $user->faculty_id;

        // For admin_faculty, get their faculty info
        $faculty = null;
        if (!$isSuperAdmin && $facultyId) {
            $faculty = Faculty::find($facultyId);
        }

        // Build scoped queries
        $studyProgramQuery = StudyProgram::query();
        $studentQuery = Student::query();
        $lecturerQuery = Lecturer::query();
        $courseQuery = Course::query();
        $classQuery = AcademicClass::query();
        $gradeQuery = Grade::query();

        if (!$isSuperAdmin && $facultyId) {
            $studyProgramQuery->where('faculty_id', $facultyId);
            $studentQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
            $lecturerQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
            $courseQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
            $classQuery->whereHas('lecturer.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
            $gradeQuery->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Basic counts (scoped)
        $stats = [
            'faculty' => $isSuperAdmin ? Faculty::count() : 0,
            'study_program' => (clone $studyProgramQuery)->count(),
            'student' => (clone $studentQuery)->count(),
            'lecturer' => (clone $lecturerQuery)->count(),
            'course' => (clone $courseQuery)->count(),
            'academic_class' => (clone $classQuery)->count(),
            'grade' => (clone $gradeQuery)->count(),
        ];

        // Grade distribution (scoped)
        $gradeDistribution = (clone $gradeQuery)
            ->selectRaw('letter_grade, COUNT(*) as count')
            ->groupBy('letter_grade')
            ->pluck('count', 'letter_grade')
            ->toArray();

        // Active academic year
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Per-study program student count (scoped)
        $studyProgramStatsQuery = StudyProgram::withCount('students');
        if (!$isSuperAdmin && $facultyId) {
            $studyProgramStatsQuery->where('faculty_id', $facultyId);
        }
        $studyProgramStats = $studyProgramStatsQuery
            ->orderBy('students_count', 'desc')
            ->take(10)
            ->get();

        // Per-study program lecturer count (scoped)
        $lecturerPerStudyProgramQuery = StudyProgram::withCount('lecturers');
        if (!$isSuperAdmin && $facultyId) {
            $lecturerPerStudyProgramQuery->where('faculty_id', $facultyId);
        }
        $lecturersByStudyProgram = $lecturerPerStudyProgramQuery
            ->orderBy('lecturers_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'gradeDistribution',
            'activeYear',
            'studyProgramStats',
            'lecturersByStudyProgram',
            'isSuperAdmin',
            'faculty'
        ));
    }
}
