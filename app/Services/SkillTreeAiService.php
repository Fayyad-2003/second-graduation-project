<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Http;

class SkillTreeAiService
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
     * Generate the skill tree data based on student's academic history
     */
    public function generateTreeData(Student $student): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key has not been configured.',
            ];
        }

        try {
            // 1. Get student's academic profile (subjects taken and current)
            $subjects = $this->getStudentSubjects($student);

            // 2. Build prompt for AI to organize the tree
            $prompt = $this->buildPrompt($student, $subjects);

            // 3. Call LLM to categorize and structure
            $response = $this->callLlm($prompt);

            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    protected function getStudentSubjects(Student $student): array
    {
        // 1. Get all subjects in the student's Study Program
        $allSubjects = Course::where('study_program_id', $student->study_program_id)
            ->with('prerequisites')
            ->get();
        
        // 2. Get student's enrollment/passed history
        $student->load('studyPlans.details.academicClass');
        $enrolledMkIds = [];
        $passedMkIds = [];

        foreach ($student->studyPlans as $studyPlan) {
            foreach ($studyPlan->details as $detail) {
                $mkId = $detail->academicClass->course_id;
                if ($studyPlan->status === 'approved') {
                    $passedMkIds[] = $mkId;
                } else {
                    $enrolledMkIds[] = $mkId;
                }
            }
        }
        
        $subjects = [];
        foreach ($allSubjects as $mk) {
            $status = 'locked';
            if (in_array($mk->id, $passedMkIds)) {
                $status = 'mastered';
            } elseif (in_array($mk->id, $enrolledMkIds)) {
                $status = 'unlocked';
            }

            $subjects[] = [
                'id' => $mk->id,
                'name' => $mk->course_name,
                'code' => $mk->course_code,
                'credits' => $mk->credits,
                'semester' => $mk->semester,
                'status' => $status, 
                'prerequisites' => $mk->prerequisites->pluck('course_name')->toArray(),
            ];
        }

        return $subjects;
    }

    protected function buildPrompt(Student $student, array $subjects): string
    {
        $studentName = $student->user->name;
        $studyProgram = $student->studyProgram->name;
        $subjectJson = json_encode($subjects, JSON_PRETTY_PRINT);

        return <<<PROMPT
You are an expert academic advisor. Based on the academic history of student $studentName in the $studyProgram study program, organize the following courses into a logical "Skill Tree" structure.

Student Courses Data:
$subjectJson

Instructions:
1. Group these courses into 4-6 "Categories" (e.g., Fundamentals, Advanced Development, Mathematics, Soft Skills, etc.).
2. For each category, determine which courses are "Roots" (no prerequisites) and which are "Leafs" (require others).
3. The output MUST be a JSON structure suitable for a frontend tree visualization (like React Flow or D3).
4. Include 'status' (mastered, unlocked, locked) for each node based on the data.
5. Use only the provided courses.

Return ONLY a valid JSON object with the following structure:
{
  "categories": [
    {
      "name": "Category Name",
      "nodes": [
         { "id": "mk_id", "label": "Course Name", "status": "mastered|unlocked|locked", "connections": ["target_mk_id"] }
      ]
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
                            ['text' => $prompt . "\n\nReturn JSON ONLY. No markdown blocks."]
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
