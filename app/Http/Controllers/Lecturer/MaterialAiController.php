<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Services\QuizAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialAiController extends Controller
{
    protected $aiService;

    public function __construct(QuizAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request, Material $material)
    {
        $lecturer = Auth::user()->lecturer;

        // Verify lecturer owns the class this material belongs to
        if (!$lecturer || $material->meeting->courseSchedule->class->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        try {
            $meeting = $material->meeting;
            $result = $this->aiService->generateMeetingAnalysis($meeting, $material);

            if (isset($result['summary']) && isset($result['quiz'])) {
                $meeting->update([
                    'ai_summary' => $result['summary'],
                    'ai_quiz' => $result['quiz'],
                ]);

                return back()->with('success', __('AI Quiz and Summary generated successfully!'));
            }

            return back()->with('error', __('Failed to parse AI response. Please try again.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Error generating AI content: ') . $e->getMessage());
        }
    }
}
