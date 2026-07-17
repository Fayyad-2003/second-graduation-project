<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Http;

class CareerRoadmapAiService
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

    public function getMainFields(Student $student): array
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'API key not configured.'];
        }

        try {
            $studyProgram = $student->studyProgram?->name ?? 'General';
            $faculty = $student->studyProgram?->faculty?->name ?? 'General';

            $prompt = "A university student is enrolled in the '{$studyProgram}' program under the '{$faculty}' faculty. List 5-7 relevant career fields they could pursue. Return ONLY a JSON array of short strings. Example: [\"Software Engineering\", \"Data Science\"]";

            $messages = [
                ['role' => 'system', 'content' => 'You are a career advisor. Always respond with ONLY a valid JSON array of strings, no markdown, no explanation.'],
                ['role' => 'user', 'content' => $prompt],
            ];

            $raw = $this->provider === 'qwen'
                ? $this->callQwenApiRaw($messages)
                : $this->callGeminiApiRaw($messages);

            $raw = preg_replace('/```json\s*|```\s*/i', '', trim($raw));
            $fields = json_decode($raw, true);

            if (!is_array($fields)) {
                return ['success' => false, 'message' => 'Invalid AI response.'];
            }

            return ['success' => true, 'fields' => array_values($fields)];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getOptions(array $data): array
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'API key not configured.'];
        }

        try {
            $level = $data['level'];
            $field = $data['field'];
            $subField = $data['subField'] ?? '';
            $specificField = $data['specificField'] ?? '';

            if ($level === 'subFields') {
                $prompt = "List 4-6 sub-fields within the career field: '{$field}'. Return ONLY a JSON array of short strings, no explanation. Example: [\"Web Development\", \"Mobile Development\"]";
            } elseif ($level === 'specificFields') {
                $prompt = "List 3-5 specific specializations within '{$subField}' (under '{$field}'). Return ONLY a JSON array of short strings. Example: [\"Frontend\", \"Backend\"]";
            } else {
                $prompt = "List 4-7 key technologies or tools for '{$specificField}' (under '{$field} > {$subField}'). Return ONLY a JSON array of short strings. Example: [\"React\", \"Vue\", \"Laravel\"]";
            }

            $messages = [
                ['role' => 'system', 'content' => 'You are a career advisor. Always respond with ONLY a valid JSON array of strings, no markdown, no explanation.'],
                ['role' => 'user', 'content' => $prompt],
            ];

            $raw = $this->provider === 'qwen'
                ? $this->callQwenApiRaw($messages)
                : $this->callGeminiApiRaw($messages);

            // Strip markdown code fences if present
            $raw = preg_replace('/```json\s*|```\s*/i', '', trim($raw));
            $options = json_decode($raw, true);

            if (!is_array($options)) {
                return ['success' => false, 'message' => 'Invalid AI response.'];
            }

            return ['success' => true, 'options' => array_values($options)];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function callGeminiApiRaw(array $messages): string
    {
        $response = Http::timeout(30)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [['role' => 'user', 'parts' => [['text' => $messages[0]['content'] . "\n\n" . $messages[1]['content']]]]]
            ]);

        if ($response->failed()) throw new \Exception('Gemini API failed: ' . $response->body());
        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }

    protected function callQwenApiRaw(array $messages): string
    {
        $response = Http::timeout(30)
            ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey, 'Content-Type' => 'application/json'])
            ->post('https://api.together.xyz/v1/chat/completions', ['model' => $this->model, 'messages' => $messages]);

        if ($response->failed()) throw new \Exception('Qwen API failed: ' . $response->body());
        return $response->json()['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Generate a career roadmap based on selected path
     */
    public function generateRoadmap(Student $student, array $pathData): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key has not been configured.',
            ];
        }

        try {
            $prompt = $this->buildPrompt($student, $pathData);
            $response = $this->callLlm($prompt);

            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    protected function buildPrompt(Student $student, array $pathData): array
    {
        $field = $pathData['field'] ?? '';
        $subField = $pathData['subField'] ?? '';
        $specificField = $pathData['specificField'] ?? '';
        $technology = $pathData['technology'] ?? '';

        $systemPrompt = "You are an AI Career Coach. Your task is to generate a highly detailed, step-by-step career roadmap for a university student.
        
        Output the response in Markdown format. You MUST use Arabic language (اللغة العربية).

        The roadmap should include:
        1. An encouraging introduction.
        2. Prerequisites / Fundamentals to master first.
        3. A multi-phase learning path (e.g., Phase 1: Beginner, Phase 2: Intermediate, Phase 3: Advanced).
        4. Specific projects to build to showcase skills.
        5. Recommended certifications or resources.
        6. Job roles and salary expectations (optional but helpful).

        Path: $field -> $subField -> $specificField -> $technology";

        $userPrompt = "I am a student named " . $student->user->name . ". I want to become a professional in the field of $specificField specifically focusing on $technology. Please generate a complete and professional roadmap in Arabic.";

        return [
            'system' => $systemPrompt,
            'user' => $userPrompt,
        ];
    }

    protected function callLlm(array $prompts): array
    {
        $messages = [
            ['role' => 'system', 'content' => $prompts['system']],
            ['role' => 'user', 'content' => $prompts['user']],
        ];

        if ($this->provider === 'qwen') {
            return $this->callQwenApi($messages);
        } else {
            return $this->callGeminiApi($messages);
        }
    }

    protected function callGeminiApi(array $messages): array
    {
        $response = Http::timeout(90)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $messages[0]['content'] . "\n\n" . $messages[1]['content']]
                        ]
                    ]
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception('Gemini API call failed: ' . $response->body());
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        
        return [
            'success' => true,
            'roadmap' => $text,
        ];
    }

    protected function callQwenApi(array $messages): array
    {
        $response = Http::timeout(90)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("https://api.together.xyz/v1/chat/completions", [
                'model' => $this->model,
                'messages' => $messages,
            ]);

        if ($response->failed()) {
            throw new \Exception('Qwen API call failed: ' . $response->body());
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? '';
        
        return [
            'success' => true,
            'roadmap' => $text,
        ];
    }
}
