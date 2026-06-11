<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\File;

class StudyPlanAiService
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
     * Generate an overall study plan for the semester based on student's academic data
     */
    public function generateOverallPlan(Student $student, array $cgpaData, \Illuminate\Support\Collection $classificationProgress, $passedSubjects, $failedSubjects, $currentClasses): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => __('API key has not been configured.'),
            ];
        }

        try {
            $prompt = $this->buildOverallPrompt($student, $cgpaData, $classificationProgress, $passedSubjects, $failedSubjects, $currentClasses);
            $response = $this->callLlm($prompt);
            $response['success'] = true;
            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('An error occurred: :message', ['message' => $e->getMessage()]),
            ];
        }
    }

    protected function buildOverallPrompt(Student $student, array $cgpaData, \Illuminate\Support\Collection $classificationProgress, $passedSubjects, $failedSubjects, $currentClasses): string
    {
        $studentName = $student->user->name;
        $studyProgram = $student->studyProgram->name ?? '';
        $currentGpa = $cgpaData['gpa'] ?? 0;
        $totalCredits = $cgpaData['total_credits'] ?? 0;
        $creditsPassed = $cgpaData['total_credits_passed'] ?? 0;

        $passedSubjectsText = $passedSubjects->map(function ($grade) {
            return "- " . $grade->academicClass->course->course_name . " (" . $grade->letter_grade . ", " . $grade->academicClass->course->credits . " credits)";
        })->implode("\n");

        $failedSubjectsText = $failedSubjects->map(function ($grade) {
            return "- " . $grade->academicClass->course->course_name . " (" . $grade->letter_grade . ", " . $grade->academicClass->course->credits . " credits)";
        })->implode("\n");

        $currentClassesText = $currentClasses->map(function ($class) {
            return "- " . $class->course->course_name . " (" . $class->course->credits . " credits, Lecturer: " . ($class->lecturer->user->name ?? 'N/A') . ")";
        })->implode("\n");

        $classificationProgressText = $classificationProgress->map(function ($progress) {
            return "- " . $progress['name'] . ": " . $progress['total'] . "/" . $progress['required'] . " credits (" . $progress['percentage'] . "%)";
        })->implode("\n");

        return <<<PROMPT
You are an expert academic advisor. Help student "$studentName" create a smart, personalized study plan for their current semester. Student is enrolled in $studyProgram.

Here is their academic profile:
- Current CGPA: $currentGpa
- Total Credits: $totalCredits
- Total Credits Passed: $creditsPassed

Passed Subjects:
$passedSubjectsText

Failed Subjects (if any):
$failedSubjectsText

Current Semester Classes:
$currentClassesText

Graduation Progress by Classification:
$classificationProgressText

Instructions:
1. Provide a study schedule breakdown for each week of the semester
2. Give specific study tips for each subject they are currently taking
3. If they have failed subjects, suggest strategies for retaking or improving in future semesters
4. Give advice on balancing study time across different subjects
5. Provide tips on how to maintain or improve their GPA
6. Be encouraging and practical

Return a JSON object with this structure:
{
  "summary": "Brief encouraging summary of the student's situation and plan",
  "subject_specific_tips": [
    {
      "subject_name": "Subject name",
      "tips": ["Tip 1", "Tip 2"]
    }
  ],
  "weekly_schedule": [
    {
      "week_number": 1,
      "focus": "What to focus on this week",
      "key_tasks": ["Task 1", "Task 2"]
    }
  ],
  "general_tips": ["General tip 1", "General tip 2"],
  "retake_advice": "Advice for retaking failed subjects, if any"
}
PROMPT;
    }

    /**
     * Generate a study plan for a specific class based on its materials
     */
    public function generatePlan(Student $student, AcademicClass $class): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key has not been configured.',
            ];
        }

        try {
            // 1. Collect materials content
            $content = $this->collectMaterialsContent($class);

            // 2. Build prompt
            $prompt = $this->buildPrompt($student, $class, $content);

            // 3. Call LLM
            $response = $this->callLlm($prompt);

            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Collect titles and descriptions of all materials in the class
     */
    protected function collectMaterialsContent(AcademicClass $class): string
    {
        $class->load(['courseSchedules.meetings.materials', 'course']);

        $content = "Subject: " . $class->course->course_name . "\n";
        $content .= "Class: " . $class->class_name . "\n\n";
        $content .= "Course Content / Materials:\n";

        foreach ($class->courseSchedules as $schedule) {
            foreach ($schedule->meetings as $meeting) {
                $content .= "- Meeting " . $meeting->meeting_number . ": " . ($meeting->topic ?: __('No description')) . "\n";
                foreach ($meeting->materials as $material) {
                    $content .= "  * Material: " . $material->title . "\n";
                    if ($material->description) {
                        $content .= "    Description: " . $material->description . "\n";
                    }

                    // Extract content from PDF if available
                    if ($material->file_path && str_contains($material->file_type, 'pdf')) {
                        $pdfText = $this->extractPdfText($material->file_path);
                        if ($pdfText) {
                            $content .= "    [Content]: " . $pdfText . "\n";
                        }
                    }
                }
            }
        }

        return $content;
    }

    protected function extractPdfText(string $filePath): string
    {
        try {
            $fullPath = storage_path('app/private/' . $filePath);
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/public/' . $filePath);
            }
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/' . $filePath);
            }

            if (!file_exists($fullPath)) return "";

            $parser = new Parser();
            $pdf = $parser->parseFile($fullPath);
            $text = $pdf->getText();

            // Return only first 1500 characters to save tokens
            return mb_substr($text, 0, 1500) . "...";
        } catch (\Exception $e) {
            return "";
        }
    }

    protected function buildPrompt(Student $student, AcademicClass $class, string $content): string
    {
        $studentName = $student->user->name;
        $courseName = $class->course->course_name;

        return <<<PROMPT
You are an expert academic tutor. Help student "$studentName" create a weekly study plan for the course "$courseName".

Based on the course materials provided below:
$content

Instructions:
1. Break down the materials into a 14-week study plan (standard semester).
2. For each week, specify:
   - What to study (based on the materials)
   - Estimated hours required
   - Learning objectives
   - Recommended practice or review
3. If materials only cover some weeks, distribute them logically and add "General Review" or "Exam Prep" for others.
4. Be encouraging and practical.

Return a JSON object with this structure:
{
  "summary": "Brief encouraging summary of the plan",
  "weeks": [
    {
      "week_number": 1,
      "topic": "Topic Name",
      "hours": 3,
      "objectives": ["Goal 1", "Goal 2"],
      "activities": "What to do"
    }
  ],
  "tips": ["Tip 1", "Tip 2"]
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
            throw new \Exception('Gemini API call failed: ' . $response->body());
        }

        $data = $response->json();
        $jsonText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        return json_decode($jsonText, true) ?? [];
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
            throw new \Exception('Qwen API call failed: ' . $response->body());
        }

        $data = $response->json();
        $jsonText = $data['choices'][0]['message']['content'] ?? '{}';

        return json_decode($jsonText, true) ?? [];
    }
}
