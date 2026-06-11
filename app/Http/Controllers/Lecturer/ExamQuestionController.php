<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamQuestionController extends Controller
{
    public function index($classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);
        $course = $class->course;
        $questions = $lecturer->examQuestions()
            ->where('course_id', $course->id)
            ->orderBy('order')
            ->get();

        return view('lecturer.exam-questions.index', compact('class', 'course', 'questions'));
    }

    public function create($classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);
        $course = $class->course;

        return view('lecturer.exam-questions.create', compact('class', 'course'));
    }

    public function store(Request $request, $classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);
        $course = $class->course;

        $validated = $request->validate([
            'question_type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'points' => 'required|numeric|min:0|max:999.99',
            'order' => 'required|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard,very_hard',
        ]);

        $lecturer->examQuestions()->create([
            'course_id' => $course->id,
            'question_type' => $validated['question_type'],
            'question_text' => $validated['question_text'],
            'options' => $validated['options'] ?? null,
            'correct_answer' => $validated['correct_answer'] ?? null,
            'points' => $validated['points'],
            'order' => $validated['order'],
            'difficulty' => $validated['difficulty'],
        ]);

        return redirect()->route('lecturers.exam-questions.index', $classId)
            ->with('success', __('Question added successfully!'));
    }

    public function edit($classId, $questionId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);
        $course = $class->course;
        $question = $lecturer->examQuestions()
            ->where('course_id', $course->id)
            ->findOrFail($questionId);

        return view('lecturer.exam-questions.edit', compact('class', 'course', 'question'));
    }

    public function update(Request $request, $classId, $questionId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);
        $course = $class->course;
        $question = $lecturer->examQuestions()
            ->where('course_id', $course->id)
            ->findOrFail($questionId);

        $validated = $request->validate([
            'question_type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'points' => 'required|numeric|min:0|max:999.99',
            'order' => 'required|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard,very_hard',
        ]);

        $question->update($validated);

        return redirect()->route('lecturers.exam-questions.index', $classId)
            ->with('success', __('Question updated successfully!'));
    }

    public function destroy($classId, $questionId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);
        $course = $class->course;
        $question = $lecturer->examQuestions()
            ->where('course_id', $course->id)
            ->findOrFail($questionId);

        $question->delete();

        return redirect()->route('lecturers.exam-questions.index', $classId)
            ->with('success', __('Question deleted successfully!'));
    }
}
