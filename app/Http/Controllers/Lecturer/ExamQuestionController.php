<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Services\QuizAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamQuestionController extends Controller
{
    protected QuizAiService $aiService;

    public function __construct(QuizAiService $aiService)
    {
        $this->aiService = $aiService;
    }

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

    public function aiGenerate(Request $request, $classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->with('course')->findOrFail($classId);

        $validated = $request->validate([
            'count'      => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard,very_hard',
            'type'       => 'required|in:multiple_choice,true_false,short_answer,essay',
        ]);

        try {
            $result = $this->aiService->generateExamQuestions(
                $class,
                $validated['count'],
                $validated['difficulty'],
                $validated['type']
            );

            if (empty($result['questions'])) {
                return back()->with('error', __('AI did not return any questions. Please try again.'));
            }

            $order = $lecturer->examQuestions()->where('course_id', $class->course->id)->max('order') ?? 0;

            foreach ($result['questions'] as $q) {
                $order++;
                $lecturer->examQuestions()->create([
                    'course_id'     => $class->course->id,
                    'question_type' => $q['question_type'] ?? $validated['type'],
                    'question_text' => $q['question_text'],
                    'options'       => $q['options'] ?? null,
                    'correct_answer'=> $q['correct_answer'] ?? null,
                    'points'        => $q['points'] ?? 10,
                    'order'         => $order,
                    'difficulty'    => $q['difficulty'] ?? $validated['difficulty'],
                ]);
            }

            $count = count($result['questions']);
            return back()->with('success', __(':count questions generated and added to the bank!', ['count' => $count]));
        } catch (\Exception $e) {
            return back()->with('error', __('AI generation failed: ') . $e->getMessage());
        }
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
