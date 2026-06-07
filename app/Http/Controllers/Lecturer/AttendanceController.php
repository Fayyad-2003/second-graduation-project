<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\LecturerAttendance;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = Auth::user()->lecturer;
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Get schedule for today
        $today = now();
        $dayName = $today->locale('en')->dayName;
        $dayMap = ['Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6];
        $todayIndex = $dayMap[$dayName] ?? $today->dayOfWeek;

        $todaySchedule = CourseSchedule::whereHas('class', fn($q) => $q->where('lecturer_id', $lecturer->id))
            ->where('day', $todayIndex)
            ->with('class.course')
            ->get();

        // Today's attendance
        $todayAttendance = LecturerAttendance::where('lecturer_id', $lecturer->id)
            ->whereDate('date', $today)
            ->pluck('course_schedule_id')
            ->toArray();

        // Stats for this month
        $stats = LecturerAttendance::where('lecturer_id', $lecturer->id)
            ->byMonth($year, $month)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // History
        $history = LecturerAttendance::where('lecturer_id', $lecturer->id)
            ->byMonth($year, $month)
            ->with('courseSchedule.class.course')
            ->orderBy('date', 'desc')
            ->get();

        return view('lecturer.attendance.index', compact('lecturer', 'todaySchedule', 'todayAttendance', 'stats', 'history', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $lecturer = Auth::user()->lecturer;

        $validated = $request->validate([
            'course_schedule_id' => 'required|exists:course_schedules,id',
            'entry_time' => 'nullable|date_format:H:i',
            'exit_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:' . implode(',', array_keys(LecturerAttendance::getStatusList())),
            'description' => 'nullable|string',
        ]);

        LecturerAttendance::updateOrCreate(
            [
                'lecturer_id' => $lecturer->id,
                'course_schedule_id' => $validated['course_schedule_id'],
                'date' => now()->toDateString(),
            ],
            [
                'entry_time' => $validated['entry_time'] ?? now()->format('H:i'),
                'exit_time' => $validated['exit_time'] ?? null,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
            ]
        );

        return redirect()->back()->with('success', __('Attendance successfully recorded'));
    }

    public function checkout()
    {
        $lecturer = Auth::user()->lecturer;
        
        // Find today's active presence for this lecturer
        $presence = LecturerAttendance::where('lecturer_id', $lecturer->id)
            ->whereDate('date', now())
            ->whereNull('exit_time')
            ->first();

        if (!$presence) {
            return redirect()->back()->with('error', __('Active attendance record not found'));
        }

        $presence->update([
            'exit_time' => now()->format('H:i:s'),
        ]);

        return redirect()->back()->with('success', __('Checkout successful'));
    }
}
