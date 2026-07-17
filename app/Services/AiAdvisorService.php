<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Models\Student;
use App\Models\AiConversationLog;
use App\Services\AcademicAdvisor\AdvisorContextBuilder;
use App\Services\AcademicAdvisor\AdvisorGuards;

class AiAdvisorService
{
    protected AdvisorContextBuilder $contextBuilder;
    protected AdvisorGuards $guards;
    protected string $apiKey;
    protected string $model;
    protected string $provider;

    protected const MAX_RETRIES = 1;
    protected const API_MAX_RETRIES = 3;

    public function __construct(
        AdvisorContextBuilder $contextBuilder,
        AdvisorGuards $guards
    ) {
        $this->contextBuilder = $contextBuilder;
        $this->guards = $guards;

        // Get AI provider from config (default: qwen)
        $this->provider = config('services.ai_provider', 'qwen');

        if ($this->provider === 'qwen') {
            $this->apiKey = config('services.qwen.api_key', '');
            $this->model = config('services.qwen.model', 'Qwen/Qwen3-4B-Instruct-2507');
        } else {
            $this->apiKey = config('services.gemini.api_key', '');
            $this->model = 'gemini-2.5-flash-lite';
        }
    }

    /**
     * Send a chat message to Gemini with grounded student context
     */
    public function chat(Student $student, string $message, array $history = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'AI API key has not been configured. Please contact administrator.',
            ];
        }

        try {
            // Step 1: Build context
            $startTime = microtime(true); // Start timer for logging
            $context = $this->contextBuilder->build($student);

            // Step 2: Run pre-guards
            $this->guards->assertRulesPresent($context);
            $this->guards->validateContext($context);

            // Step 3: Build system prompt with context
            $systemPrompt = $this->buildSystemPrompt($context);

            // Step 4: Call LLM
            $response = $this->callLlm($systemPrompt, $message, $history);

            if (!$response['success']) {
                return $response;
            }

            $output = $response['message'];

            // Step 5: Run post-guards
            $guardResult = $this->guards->runPostGuards($context, $output);

            if (!$guardResult['passed']) {
                // Try retry if allowed
                if ($guardResult['should_retry'] && $guardResult['retry_prompt']) {
                    $retryResponse = $this->retryWithGuardPrompt(
                        $systemPrompt,
                        $message,
                        $output,
                        $guardResult['retry_prompt'],
                        $history
                    );

                    if ($retryResponse['success']) {
                        // Check guards again on retry
                        $retryGuardResult = $this->guards->runPostGuards($context, $retryResponse['message']);
                        if ($retryGuardResult['passed']) {
                            return $retryResponse;
                        }
                    }
                }

                // Use replacement output if guard provides one
                if ($guardResult['replacement_output']) {
                    $debugInfo = '';
                    if (config('app.debug')) {
                        $debugInfo = "\n\n[Guard Triggered: " . implode(', ', $guardResult['failed_guards'] ?? []) . "]";
                    }
                    return [
                        'success' => true,
                        'message' => $guardResult['replacement_output'] . $debugInfo,
                        'sources' => [],
                    ];
                }
            }

            // Step 6: Log conversation
            $duration = microtime(true) - $startTime;
            $this->logConversation($student->id, $message, $output, $duration);

            return [
                'success' => true,
                'message' => $output,
                'sources' => $context['sources'] ?? [],
            ];
        } catch (\Exception $e) {
            \Log::error('AI Advisor Chat Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while processing your request: ' . $e->getMessage(),
            ];
        }
    }

    protected function buildSystemPrompt(array $context): string
    {
        $studentName = $context['student']['name'] ?? 'Student';
        $studyProgram = $context['student']['study_program'] ?? 'Unknown';
        $gpa = $context['academic_summary']['gpa'] ?? '0.0';
        $credits = $context['academic_summary']['total_credits'] ?? '0';

        $historyText = $this->formatCourseStatuses($context['course_statuses'] ?? []);
        $curriculumText = $this->formatCurriculum($context['curriculum'] ?? []);
        $rulesText = $this->formatRules($context['study_program_rules'] ?? []);

        return <<<PROMPT
You are a professional Academic Advisor for student $studentName in the $studyProgram study program.
Current GPA: $gpa
Total Credits: $credits

Your role is to help students with:
1. Academic planning and course selection.
2. Understanding academic regulations and requirements.
3. Providing advice on career paths based on their courses.
4. Answering questions about course materials and content.

Student Academic History:
{$historyText}

Available Courses in Study Program:
{$curriculumText}

Academic Rules:
{$rulesText}

Instructions:
1. Be helpful, professional, and accurate.
2. ALWAYS verify your advice against the provided Academic Rules.
3. If the student asks for advice on course selection, consider their past performance and prerequisites.
4. If you don't know the answer or it's not in the context, be honest and refer them to the official academic department.
5. Keep your responses in the language of the student (Arabic or English).

PROMPT;
    }

    protected function formatCourseStatuses(array $statuses): string
    {
        if (empty($statuses)) return 'No academic history available.';
        $lines = [];
        foreach ($statuses as $code => $info) {
            $grade = isset($info['grade']) ? " - Grade: {$info['grade']}" : '';
            $lines[] = "- [{$info['status']}] {$code}: {$info['name']}{$grade}";
        }
        return implode("\n", $lines);
    }

    protected function formatCurriculum(array $curriculum): string
    {
        if (empty($curriculum)) return 'No curriculum data available.';
        $lines = [];
        foreach ($curriculum as $course) {
            $lines[] = "- Sem {$course['semester']}: {$course['code']} {$course['name']} ({$course['credits']} credits)";
        }
        return implode("\n", $lines);
    }

    protected function formatRules(array $rules): string
    {
        if (empty($rules)) return 'No rules available.';
        return implode("\n", array_map(
            fn($k, $v) => "- {$k}: {$v}",
            array_keys($rules),
            $rules
        ));
    }

    protected function callLlm(string $systemPrompt, string $userMessage, array $history = []): array
    {
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($history as $chat) {
            $role = $chat['role'] ?? 'user';
            $content = $chat['content'] ?? '';
            if ($role === 'user' || $role === 'assistant') {
                $messages[] = ['role' => $role, 'content' => $content];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        if ($this->provider === 'qwen') {
            return $this->callQwen($messages);
        }
        return $this->callGemini($messages);
    }

    protected function callGemini(array $messages): array
    {
        $system = array_shift($messages)['content'];
        $user = array_pop($messages)['content'];

        $contents = [];
        foreach ($messages as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]]
            ];
        }

        $fullUserPrompt = "SYSTEM INSTRUCTION:\n" . $system . "\n\nUSER MESSAGE:\n" . $user;
        $contents[] = ['role' => 'user', 'parts' => [['text' => $fullUserPrompt]]];

        $lastError = null;
        for ($attempt = 1; $attempt <= self::API_MAX_RETRIES; $attempt++) {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => $contents
                ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text', '');
                return ['success' => true, 'message' => $text];
            }

            $status = $response->json('error.code');
            $lastError = $response->json('error.message', $response->body());

            if ($status === 503 && $attempt < self::API_MAX_RETRIES) {
                sleep($attempt * 2);
                continue;
            }

            break;
        }

        if (str_contains((string) $lastError, 'high demand') || str_contains((string) $lastError, 'UNAVAILABLE')) {
            return [
                'success' => false,
                'message' => 'The AI service is currently busy. Please try again in a few moments.',
            ];
        }

        throw new \Exception('Gemini API call failed: ' . $lastError);
    }

    protected function callQwen(array $messages): array
    {
        $lastError = null;
        for ($attempt = 1; $attempt <= self::API_MAX_RETRIES; $attempt++) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}"
            ])->post("https://api.together.xyz/v1/chat/completions", [
                'model' => $this->model,
                'messages' => $messages,
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content', '');
                return ['success' => true, 'message' => $text];
            }

            $status = $response->status();
            $lastError = $response->body();

            if ($status === 503 && $attempt < self::API_MAX_RETRIES) {
                sleep($attempt * 2);
                continue;
            }

            break;
        }

        if ($response->status() === 503) {
            return [
                'success' => false,
                'message' => 'The AI service is currently busy. Please try again in a few moments.',
            ];
        }

        throw new \Exception('Qwen API call failed: ' . $lastError);
    }

    protected function retryWithGuardPrompt(string $system, string $user, string $oldOutput, string $retryPrompt, array $history): array
    {
        $messages = [
            ['role' => 'system', 'content' => $system],
        ];

        foreach ($history as $chat) {
            $role = $chat['role'] ?? 'user';
            $content = $chat['content'] ?? '';
            if ($role === 'user' || $role === 'assistant') {
                $messages[] = ['role' => $role, 'content' => $content];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $user];
        $messages[] = ['role' => 'assistant', 'content' => $oldOutput];
        $messages[] = ['role' => 'user', 'content' => "CORRECTION: " . $retryPrompt];

        if ($this->provider === 'qwen') {
            return $this->callQwen($messages);
        }
        return $this->callGemini($messages);
    }

    protected function logConversation(int $studentId, string $userMsg, string $aiMsg, float $duration): void
    {
        $student = \App\Models\Student::find($studentId);
        AiConversationLog::create([
            'user_id' => $student?->user_id,
            'student_id' => $studentId,
            'question' => $userMsg,
            'answer' => $aiMsg,
            'response_time_ms' => round($duration * 1000),
            'model_used' => $this->model,
            'provider' => $this->provider,
        ]);
    }
}
