<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SemesterCalendar;
use Illuminate\Http\Request;

class SemesterCalendarController extends Controller
{
    public function index(Request $request)
    {
        $academicYearId = $request->get('academic_year_id');
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$academicYearId && $activeYear) {
            $academicYearId = $activeYear->id;
        }

        $academicYears = AcademicYear::orderBy('year', 'desc')->orderBy('semester', 'desc')->get();
        
        $calendars = SemesterCalendar::when($academicYearId, function($query) use ($academicYearId) {
                return $query->where('academic_year_id', $academicYearId);
            })
            ->orderBy('week_number')
            ->orderBy('date')
            ->get();

        return view('admin.semester-calendar.index', compact('calendars', 'academicYears', 'academicYearId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'week_number' => 'nullable|integer',
            'date' => 'nullable|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:academic,holiday,exam,other',
        ]);

        SemesterCalendar::create($validated);

        return back()->with('success', __('Calendar entry added successfully.'));
    }

    public function update(Request $request, SemesterCalendar $semesterCalendar)
    {
        $validated = $request->validate([
            'week_number' => 'nullable|integer',
            'date' => 'nullable|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:academic,holiday,exam,other',
        ]);

        $semesterCalendar->update($validated);

        return back()->with('success', __('Calendar entry updated successfully.'));
    }

    public function destroy(SemesterCalendar $semesterCalendar)
    {
        $semesterCalendar->delete();
        return back()->with('success', __('Calendar entry deleted successfully.'));
    }
}
