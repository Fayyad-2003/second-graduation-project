<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LecturerAttendance;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LecturerAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $lecturerId = $request->get('lecturer_id');

        $query = LecturerAttendance::with(['lecturer.user', 'lecturer.studyProgram', 'courseSchedule.class.course'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        }

        // Faculty scoping for admin_faculty
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $query->whereHas('lecturer.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        $attendanceList = $query->orderBy('date', 'desc')->paginate(50);

        // Stats - also scoped
        $statsQuery = LecturerAttendance::whereYear('date', $year)->whereMonth('date', $month);
        if ($lecturerId) $statsQuery->where('lecturer_id', $lecturerId);
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $statsQuery->whereHas('lecturer.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        
        $stats = $statsQuery->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Scope lecturers list for dropdown
        $lecturerQuery = Lecturer::with('user');
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $lecturerQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        $lecturerList = $lecturerQuery->get();

        // Rekap per lecturers - also scoped
        $summaryQuery = LecturerAttendance::whereYear('date', $year)
            ->whereMonth('date', $month);
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $summaryQuery->whereHas('lecturer.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        $lecturerSummary = $summaryQuery->selectRaw('lecturer_id, status, COUNT(*) as count')
            ->groupBy('lecturer_id', 'status')
            ->get()
            ->groupBy('lecturer_id');

        return view('admin.lecturer-attendance.index', compact('attendanceList', 'stats', 'lecturerList', 'lecturerSummary', 'month', 'year', 'lecturerId'));
    }


    public function show(Lecturer $lecturer, Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendanceList = LecturerAttendance::where('lecturer_id', $lecturer->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('courseSchedule.class.course')
            ->orderBy('date', 'desc')
            ->get();

        $stats = $attendanceList->groupBy('status')->map->count();

        return view('admin.lecturer-attendance.show', compact('lecturer', 'attendanceList', 'stats', 'month', 'year'));
    }
}
