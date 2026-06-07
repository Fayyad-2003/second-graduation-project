<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisSupervision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403);
        }

        $thesis = Thesis::where('student_id', $student->id)
            ->with(['supervisor1.user', 'supervisor2.user', 'supervisions'])
            ->first();

        $supervisionList = $thesis ? $thesis->supervisions : collect();

        return view('student.thesis.index', compact('student', 'thesis', 'supervisionList'));
    }

    public function create()
    {
        $student = Auth::user()->student;

        // Check if already has thesis
        $existing = Thesis::where('student_id', $student->id)->first();
        if ($existing) {
            return redirect()->route('students.thesis.index')
                ->with('error', __('You already have a thesis submission'));
        }

        return view('student.thesis.create', compact('student'));
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'abstract' => 'nullable|string',
            'research_field' => 'nullable|string|max:100',
        ]);

        Thesis::create([
            'student_id' => $student->id,
            'title' => $validated['title'],
            'abstract' => $validated['abstract'] ?? null,
            'research_field' => $validated['research_field'] ?? null,
            'status' => Thesis::STATUS_SUBMISSION,
            'submission_date' => now(),
        ]);

        return redirect()->route('students.thesis.index')
            ->with('success', __('Thesis title submission sent successfully'));
    }

    public function storeSupervision(Request $request)
    {
        $student = Auth::user()->student;
        $thesis = Thesis::where('student_id', $student->id)->firstOrFail();

        $validated = $request->validate([
            'supervision_date' => 'required|date',
            'student_notes' => 'required|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('document_file')) {
            $filePath = $request->file('document_file')->store('theses/supervisions', 'public');
        }

        // Default to supervisor 1
        $lecturerId = $thesis->supervisor1_id;

        ThesisSupervision::create([
            'thesis_id' => $thesis->id,
            'lecturer_id' => $lecturerId,
            'supervision_date' => $validated['supervision_date'],
            'student_notes' => $validated['student_notes'],
            'document_file' => $filePath,
            'status' => ThesisSupervision::STATUS_WAITING,
        ]);

        return redirect()->route('students.thesis.index')
            ->with('success', __('Supervision notes sent successfully'));
    }
}
