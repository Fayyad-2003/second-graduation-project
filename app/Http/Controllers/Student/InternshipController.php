<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\InternshipLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            // Show empty internship page instead of aborting
            return view('student.internship.index', [
                'student' => null,
                'internship' => null,
                'logbookList' => collect(),
            ]);
        }

        $internship = Internship::where('student_id', $student->id)
            ->with(['supervisor.user', 'logbook'])
            ->first();

        $logbookList = $internship ? $internship->logbook()->get() : collect();

        return view('student.internship.index', compact('student', 'internship', 'logbookList'));
    }

    public function create()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('students.internship.index')->with('error', __('Student record not found.'));
        }

        $existing = Internship::where('student_id', $student->id)->first();

        if ($existing) {
            return redirect()->route('students.internship.index')->with('error', __('You already have an internship application'));
        }

        return view('student.internship.create', compact('student'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('students.internship.index')->with('error', __('Student record not found.'));
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'business_field' => 'nullable|string|max:100',
            'field_supervisor_name' => 'nullable|string|max:100',
            'field_supervisor_title' => 'nullable|string|max:100',
            'supervisor_phone' => 'nullable|string|max:20',
            'start_date' => 'required|date',
            'completion_date' => 'required|date|after:start_date',
        ]);

        Internship::create([
            'student_id' => $student->id,
            ...$validated,
            'status' => Internship::STATUS_SUBMISSION,
        ]);

        return redirect()->route('students.internship.index')->with('success', __('Internship application submitted successfully'));
    }

    public function storeLogbook(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('students.internship.index')->with('error', __('Student record not found.'));
        }

        $internship = Internship::where('student_id', $student->id)->firstOrFail();

        $validated = $request->validate([
            'date' => 'required|date',
            'entry_time' => 'nullable|date_format:H:i',
            'exit_time' => 'nullable|date_format:H:i',
            'activity' => 'required|string',
        ]);

        InternshipLogbook::create([
            'internship_id' => $internship->id,
            ...$validated,
            'status' => InternshipLogbook::STATUS_PENDING,
        ]);

        return redirect()->route('students.internship.index')->with('success', __('Logbook added successfully'));
    }
}
