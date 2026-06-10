<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index($classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()
            ->with(['course', 'exams' => fn($q) => $q->latest()])
            ->findOrFail($classId);

        return view('lecturer.exam.index', compact('class'));
    }

    public function store(Request $request, $classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'max_score' => 'nullable|numeric|min:0|max:999.99',
        ]);

        Exam::create([
            'class_id' => $class->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'exam_date' => $validated['exam_date'],
            'duration' => $validated['duration'],
            'location' => $validated['location'] ?? null,
            'max_score' => $validated['max_score'] ?? 100.00,
        ]);

        return back()->with('success', __('Exam successfully created.'));
    }

    public function destroy($classId, Exam $exam)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        if ($exam->class_id != $classId) {
            abort(403);
        }

        $exam->delete();

        return back()->with('success', __('Exam successfully deleted.'));
    }
}
