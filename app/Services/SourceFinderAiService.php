<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SourceFinderAiService
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
     * Search for academic sources based on a query
     */
    public function searchSources(string $query): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key not configured.',
            ];
        }

        try {
            $systemPrompt = "You are an AI Academic Research Assistant. Your goal is to provide students with high-quality, credible academic sources for their studies.
            
            You MUST output the response in JSON format. Use Arabic language (اللغة العربية) for titles and descriptions.

            JSON structure:
            {
                \"sources\": [
                    {
                        \"title\": \"Title of the paper/book in Arabic\",
                        \"author\": \"Authors names\",
                        \"type\": \"Paper / Book / Journal / Website\",
                        \"year\": \"Publication Year\",
                        \"link\": \"Actual or placeholder URL (e.g. Google Scholar search link)\",
                        \"description\": \"Why this is a good source for the topic (in Arabic)\",
                        \"credibility\": \"High / Medium\"
                    },
                    ... (at least 5 sources)
                ]
            }";

            $userPrompt = "Topic: $query\n\nPlease find 5-7 credible academic sources for this topic.";

            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ];

            if ($this->provider === 'qwen') {
                return $this->callQwenApi($messages);
            } else {
                return $this->callGeminiApi($messages);
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    protected function callGeminiApi(array $messages): array
    {
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://generativelanguage.googleapis.com/v1beta/openai/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '{}';
            return [
                'success' => true,
                'data' => json_decode($content, true),
            ];
        }

        return ['success' => false, 'message' => 'AI Error'];
    }

    protected function callQwenApi(array $messages): array
    {
        $url = 'https://api.bytez.com/models/v2/openai/v1/chat/completions';
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '{}';
            $text = preg_replace('/<think>.*?<\/think>/s', '', $text);
            if (preg_match('/```json\s*(.*?)\s*```/s', $text, $matches)) { $text = $matches[1]; }
            return [
                'success' => true,
                'data' => json_decode($text, true),
            ];
        }

        return ['success' => false, 'message' => __('AI Service Error')];
    }
}
