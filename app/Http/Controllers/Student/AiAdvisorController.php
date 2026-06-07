<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AiAdvisorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiAdvisorController extends Controller
{
    protected AiAdvisorService $aiService;

    public function __construct(AiAdvisorService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display AI Advisor chat page
     */
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        $student->load(['user', 'studyProgram']);

        return view('student.ai-advisor.index', compact('student'));
    }

    /**
     * Handle chat message
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $student = Auth::user()->student;

        if (!$student) {
            return response()->json(['success' => false, 'message' => __('Unauthorized')], 403);
        }

        $result = $this->aiService->chat(
            $student,
            $request->input('message'),
            $request->input('history', [])
        );

        return response()->json($result);
    }
}
