<?php

namespace App\Services;

use App\Models\AcademicClass;
use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class SubjectSearchAiService
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
     * Answer a question based on subject materials
     */
    public function askQuestion(Student $student, AcademicClass $class, string $question): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key has not been configured.',
            ];
        }

        try {
            // 1. Collect materials context
            $context = $this->collectMaterialsContext($class);

            // 2. Build prompt
            $prompt = $this->buildPrompt($student, $class, $context, $question);

            // 3. Call LLM
            $response = $this->callLlm($prompt);

            return [
                'success' => true,
                'answer'   => $response['answer'] ?? null,
                'sources'  => $response['sources'] ?? [],
                'related_questions' => $response['related_questions'] ?? [],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    protected function collectMaterialsContext(AcademicClass $class): string
    {
        $class->load(['courseSchedules.meetings.materials', 'course']);
        
        $content = "Course: " . $class->course->course_name . "\n\n";
        $content .= "Materials and Lessons:\n";

        foreach ($class->courseSchedules as $schedule) {
            foreach ($schedule->meetings as $meeting) {
                $content .= "Meeting " . $meeting->meeting_number . ": " . ($meeting->topic ?: __('No description')) . "\n";
                foreach ($meeting->materials as $material) {
                    $content .= "- " . $material->title . ": " . ($material->description ?: __('No detail')) . "\n";
                    
                    // Extract content from PDF if available
                    if ($material->file_path && str_contains($material->file_type, 'pdf')) {
                        $pdfText = $this->extractPdfText($material->file_path);
                        if ($pdfText) {
                            $content .= "  [PDF Content Summary]: " . $pdfText . "\n";
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
            // Check common storage locations
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

            // Sanitize to valid UTF-8 to prevent json_encode failures
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

            return mb_substr($text, 0, 1500) . "...";
        } catch (\Exception $e) {
            return "";
        }
    }

    protected function buildPrompt(Student $student, AcademicClass $class, string $context, string $question): string
    {
        $studentName = $student->user->name;
        $courseName = $class->course->course_name;

        return <<<PROMPT
You are a teaching assistant for the course "$courseName". Student "$studentName" has a question about the course materials.

Context (Course Materials):
$context

Question:
$question

Instructions:
1. Use the provided context to answer the question accurately.
2. If the answer is not in the context, use your general knowledge but mention that it's additional information.
3. Be helpful, professional, and clear.
4. Keep the response concise.

Return a JSON object with this structure:
{
  "answer": "Your detailed answer in Markdown format",
  "sources": ["Meeting 1", "PDF: Lesson 1 summary", etc],
  "related_questions": ["Question 1", "Question 2"]
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
