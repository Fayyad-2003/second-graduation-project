<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SemesterCalendar;
use Illuminate\Http\Request;

class SemesterCalendarController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return view('student.semester-calendar.index', [
                'calendars' => collect(),
                'activeYear' => null,
                'message' => __('No active academic year found.')
            ]);
        }

        $calendars = SemesterCalendar::where('academic_year_id', $activeYear->id)
            ->where('is_active', true)
            ->orderBy('week_number')
            ->orderBy('date')
            ->get();

        return view('student.semester-calendar.index', compact('calendars', 'activeYear'));
    }
}
