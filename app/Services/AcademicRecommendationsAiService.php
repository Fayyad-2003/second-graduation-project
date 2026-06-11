<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Http;

class AcademicRecommendationsAiService
{
    protected string $apiKey;
    protected string $model;
    protected string $provider;

    public function __construct()
    {
        $this->provider = config('services.ai_provider', 'gemini');

        if ($this->provider === 'qwen') {
            $this->apiKey = config('services.qwen.api_key', '');
            $this->model = config('services.qwen.model', 'Qwen/Qwen3-4B-Instruct-2507');
        } else {
            $this->apiKey = config('services.gemini.api_key', '');
            $this->model = 'gemini-2.5-flash-lite';
        }
    }

    /**
     * Generate academic recommendations for a student based on their data
     */
    public function generateRecommendations(
        Student $student,
        array $cgpaData,
        array $reportSummary,
        $passedSubjects,
        $failedSubjects,
        $currentClasses,
        $classAttendanceSummaries,
        $classAssignmentSummaries
    ): array {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => __('API key has not been configured.'),
            ];
        }

        try {
            // Build prompt
            $prompt = $this->buildPrompt(
                $student,
                $cgpaData,
                $reportSummary,
                $passedSubjects,
                $failedSubjects,
                $currentClasses,
                $classAttendanceSummaries,
                $classAssignmentSummaries
            );

            // Call LLM
            $response = $this->callLlm($prompt);

            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('An error occurred: :message', ['message' => $e->getMessage()]),
            ];
        }
    }

    protected function buildPrompt(
        Student $student,
        array $cgpaData,
        array $reportSummary,
        $passedSubjects,
        $failedSubjects,
        $currentClasses,
        $classAttendanceSummaries,
        $classAssignmentSummaries
    ): string {
        $studentName = $student->user->name;
        $studyProgram = $student->studyProgram->name ?? '';

        // Format passed subjects
        $passedSubjectsText = $passedSubjects->map(function ($grade) {
            return "- " . $grade->academicClass->course->course_name . " (" . $grade->letter_grade . ")";
        })->implode("\n");

        // Format failed subjects
        $failedSubjectsText = $failedSubjects->map(function ($grade) {
            return "- " . $grade->academicClass->course->course_name . " (" . $grade->letter_grade . ")";
        })->implode("\n");

        // Format current classes
        $currentClassesText = $currentClasses->map(function ($class) use ($classAttendanceSummaries, $classAssignmentSummaries) {
            $attendance = $classAttendanceSummaries->get($class->id);
            $assignments = $classAssignmentSummaries->get($class->id);
            $attendanceRate = $attendance['total_meetings'] > 0 ? round(($attendance['present'] / $attendance['total_meetings']) * 100, 2) : 0;
            $submissionRate = $assignments['total'] > 0 ? round(($assignments['submitted'] / $assignments['total']) * 100, 2) : 0;

            return "- " . $class->course->course_name . "\n  Attendance: " . $attendanceRate . "%\n  Assignments Submitted: " . $assignments['submitted'] . "/" . $assignments['total'];
        })->implode("\n");

        return <<<PROMPT
You are an expert academic advisor. Help student "$studentName" who is studying "$studyProgram".

Here's their academic data:
- Overall GPA: {$reportSummary['gpa']}
- Total Credits: {$reportSummary['total_credits']}
- Academic Status: {$reportSummary['status_text']}
- Overall Attendance Rate: {$reportSummary['attendance_rate']}%
- Overall Assignment Submission Rate: {$reportSummary['submission_rate']}%
- Passed Subjects: {$passedSubjects->count()}
- Failed Subjects: {$failedSubjects->count()}

Passed Subjects:
$passedSubjectsText

Failed Subjects (if any):
$failedSubjectsText

Current Semester Classes:
$currentClassesText

Instructions:
1. Provide 3-5 specific, actionable recommendations tailored to this student's situation
2. If there are failed subjects, give specific advice on how to improve them
3. If there are current classes with low attendance or assignment submission, address those specifically
4. Give study tips relevant to their field of study
5. Be encouraging and supportive

Return a JSON object with this structure:
{
  "summary": "Brief encouraging summary",
  "recommendations": [
    {
      "title": "Recommendation title",
      "description": "Detailed actionable advice"
    }
  ],
  "study_tips": ["Tip 1", "Tip 2", "Tip 3"]
}
PROMPT;
    }

    protected function callLlm(string $prompt): array
    {
        if ($this->provider === 'qwen') {
            return $this->callQwen($prompt);
        }
        return $this->callGemini($prompt);
    }

    protected function callGemini(string $prompt): array
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt . "\n\nReturn JSON ONLY."]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception(__('Gemini API call failed: :message', ['message' => $response->body()]));
        }

        $data = $response->json();
        $jsonText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        $result = json_decode($jsonText, true) ?? [];
        $result['success'] = true;

        return $result;
    }

    protected function callQwen(string $prompt): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->apiKey}"
        ])->post("https://api.together.xyz/v1/chat/completions", [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'response_format' => ['type' => 'json_object']
        ]);

        if ($response->failed()) {
            throw new \Exception(__('Qwen API call failed: :message', ['message' => $response->body()]));
        }

        $data = $response->json();
        $jsonText = $data['choices'][0]['message']['content'] ?? '{}';

        $result = json_decode($jsonText, true) ?? [];
        $result['success'] = true;

        return $result;
    }
}
