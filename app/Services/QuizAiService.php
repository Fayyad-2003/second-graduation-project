<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Material;
use App\Models\Meeting;
use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class QuizAiService
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
     * Generate a quiz for a specific class based on its materials
     */
    public function generateQuiz(Student $student, AcademicClass $class): array
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
     * Generate a summary and quiz for a specific meeting based on a material
     */
    public function generateMeetingAnalysis(Meeting $meeting, Material $material): array
    {
        if (empty($this->apiKey)) {
            throw new \Exception('API key has not been configured.');
        }

        // 1. Extract content from the material
        $content = "Meeting Topic: " . ($meeting->topic ?: 'General') . "\n";
        $content .= "Material Title: " . $material->title . "\n";
        if ($material->description) {
            $content .= "Material Description: " . $material->description . "\n";
        }

        if ($material->file_path && str_contains($material->file_type, 'pdf')) {
            $pdfText = $this->extractPdfText($material->file_path);
            if ($pdfText) {
                $content .= "[Full Content]: " . $pdfText . "\n";
            }
        }

        // 2. Build prompt for summary and 10 questions
        $prompt = <<<PROMPT
You are an expert academic assistant. Analyze the following class material and generate:
1. A concise summary of the material (2-3 paragraphs).
2. Exactly 10 multiple-choice questions based on the content.

Material Content:
$content

Instructions for Questions:
- Each question must have 4 options (A, B, C, D).
- Provide the correct answer and a brief explanation for each.
- Ensure questions are academic and relevant.

Return ONLY a valid JSON object with the following structure:
{
  "summary": "The summary text here...",
  "quiz": {
    "title": "Quiz for {$meeting->topic}",
    "questions": [
      {
        "id": 1,
        "question": "The question text?",
        "options": {"A": "...", "B": "...", "C": "...", "D": "..."},
        "answer": "A",
        "explanation": "..."
      }
    ]
  }
}
PROMPT;

        // 3. Call LLM
        return $this->callLlm($prompt);
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
                            $content .= "    [Detailed Content]: " . $pdfText . "\n";
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
You are an expert examiner for the course "$courseName". Generate a quiz for student "$studentName" based on the course materials provided below.

Course Materials:
$content

Instructions:
1. Generate 5 multiple-choice questions.
2. Each question must have 4 options (A, B, C, D).
3. Provide the correct answer and a brief explanation for each question.
4. Ensure the questions are relevant to the provided materials.
5. Be clear and academic.

Return ONLY a valid JSON object with the following structure:
{
  "title": "Quiz for $courseName",
  "questions": [
    {
      "id": 1,
      "question": "The question text?",
      "options": {
        "A": "Option A",
        "B": "Option B",
        "C": "Option C",
        "D": "Option D"
      },
      "answer": "A",
      "explanation": "Why A is correct"
    }
  ]
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
