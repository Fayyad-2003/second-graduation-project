<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Course;
use App\Models\FacultyRequirement;
use App\Models\GpaCreditRule;
use App\Models\StudyPlan;
use Illuminate\Support\Facades\Http;

class GraduationPredictorAiService
{
    protected string $apiKey;
    protected string $model;
    protected string $provider;

    public function __construct()
    {
        $this->provider = config('services.ai_provider', 'gemini');

        if ($this->provider === 'qwen') {
            $this->apiKey = config('services.qwen.api_key', '');
            $this->model  = config('services.qwen.model', 'Qwen/Qwen3-4B-Instruct-2507');
        } else {
            $this->apiKey = config('services.gemini.api_key', '');
            $this->model  = 'gemini-2.5-flash-lite';
        }
    }

    public function predict(Student $student): array
    {
        if (empty($this->apiKey)) {
            return ['success' => false, 'message' => 'API key has not been configured.'];
        }

        try {
            $data   = $this->buildStudentData($student);
            $prompt = $this->buildPrompt($data);
            $result = $this->callLlm($prompt);
            $result['success']      = true;
            $result['student_data'] = $data;
            return $result;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    protected function buildStudentData(Student $student): array
    {
        $student->load(['user', 'studyProgram.faculty.requirements.classification', 'studyPlans.academicYear']);

        // --- GPA history per semester ---
        $gpaHistory = [];
        $studyPlans = StudyPlan::where('student_id', $student->id)
            ->where('status', 'approved')
            ->with('academicYear')
            ->orderBy('created_at')
            ->get();

        $calcService = app(AcademicCalculationService::class);

        foreach ($studyPlans as $plan) {
            $gpaData      = $calcService->calculateGPA($student, $plan->academic_year_id);
            $gpaHistory[] = [
                'semester'      => $plan->academicYear->year . ' ' . $plan->academicYear->semester,
                'gpa'           => $gpaData['gpa'],
                'credits_taken' => $gpaData['total_credits'],
            ];
        }

        // --- Cumulative data ---
        $cgpa = $calcService->calculateCGPA($student);

        // --- Classification progress ---
        $classificationProgress = $calcService->getClassificationProgress($student)->toArray();

        // --- Failed courses ---
        $failedGrades = Grade::where('student_id', $student->id)
            ->whereNotIn('letter_grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'])
            ->with('academicClass.course')
            ->get()
            ->map(fn($g) => [
                'course'  => $g->academicClass->course->course_name,
                'code'    => $g->academicClass->course->course_code,
                'credits' => $g->academicClass->course->credits,
                'grade'   => $g->letter_grade,
            ])->toArray();

        // --- Remaining courses (not yet passed) ---
        $passedCourseIds = Grade::where('student_id', $student->id)
            ->whereIn('letter_grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'])
            ->with('academicClass')
            ->get()
            ->pluck('academicClass.course_id')
            ->unique()
            ->toArray();

        $remainingCourses = Course::where('study_program_id', $student->study_program_id)
            ->whereNotIn('id', $passedCourseIds)
            ->with('prerequisites')
            ->get()
            ->map(fn($c) => [
                'course'        => $c->course_name,
                'code'          => $c->course_code,
                'credits'       => $c->credits,
                'semester'      => $c->semester,
                'prerequisites' => $c->prerequisites->pluck('course_name')->toArray(),
            ])->toArray();

        // --- Max credits allowed by current GPA ---
        $maxCreditsAllowed = $calcService->getMaxCredits($cgpa['gpa']);

        // --- Faculty total required credits ---
        $facultyRequiredCredits = $student->studyProgram->faculty->total_credits ?? 144;

        return [
            'student_name'            => $student->user->name,
            'student_number'          => $student->student_number,
            'study_program'           => $student->studyProgram->name ?? '',
            'batch'                   => $student->batch,
            'current_semester'        => count($gpaHistory),
            'cgpa'                    => $cgpa['gpa'],
            'total_credits_passed'    => $cgpa['total_credits_passed'],
            'faculty_required_credits'=> $facultyRequiredCredits,
            'remaining_credits'       => max(0, $facultyRequiredCredits - $cgpa['total_credits_passed']),
            'max_credits_per_semester'=> $maxCreditsAllowed,
            'gpa_history'             => $gpaHistory,
            'classification_progress' => $classificationProgress,
            'failed_courses'          => $failedGrades,
            'remaining_courses'       => $remainingCourses,
        ];
    }

    protected function buildPrompt(array $data): string
    {
        $gpaHistoryText = collect($data['gpa_history'])->map(
            fn($h) => "- {$h['semester']}: GPA {$h['gpa']}, Credits {$h['credits_taken']}"
        )->implode("\n");

        $classificationText = collect($data['classification_progress'])->map(
            fn($c) => "- {$c['name']}: {$c['total']}/{$c['required']} credits ({$c['percentage']}%)"
        )->implode("\n");

        $failedText = empty($data['failed_courses'])
            ? 'None'
            : collect($data['failed_courses'])->map(fn($f) => "- {$f['code']} {$f['course']} (Grade: {$f['grade']}, {$f['credits']} credits)")->implode("\n");

        $remainingText = collect($data['remaining_courses'])->map(
            fn($r) => "- Sem {$r['semester']}: {$r['code']} {$r['course']} ({$r['credits']} credits)" .
                      (!empty($r['prerequisites']) ? ' [Requires: ' . implode(', ', $r['prerequisites']) . ']' : '')
        )->implode("\n");

        return <<<PROMPT
You are an expert academic graduation analyst. Analyze the following student data and provide a detailed graduation readiness prediction.

Student: {$data['student_name']} ({$data['student_number']})
Study Program: {$data['study_program']}
Batch Year: {$data['batch']}
Current Semester: {$data['current_semester']}
Cumulative GPA: {$data['cgpa']}
Credits Passed: {$data['total_credits_passed']} / {$data['faculty_required_credits']}
Remaining Credits: {$data['remaining_credits']}
Max Credits Allowed Next Semester (based on GPA): {$data['max_credits_per_semester']}

GPA History Per Semester:
{$gpaHistoryText}

Classification Progress:
{$classificationText}

Failed / Retake Courses:
{$failedText}

Remaining Courses to Complete:
{$remainingText}

Instructions:
1. Calculate the estimated number of semesters needed to finish remaining credits (considering max credits per semester based on GPA).
2. Predict the estimated graduation semester (e.g., "Semester 8 - Even 2027").
3. Assess the graduation risk level: "On Track", "At Risk", or "Critical".
4. Identify the top 3-5 bottleneck courses that are blocking graduation (courses with prerequisites that many others depend on, or failed courses).
5. Analyze the GPA trend (improving, stable, declining) and its impact.
6. Provide 3-5 specific, actionable recommendations to help the student graduate on time.
7. Respond entirely in Arabic (اللغة العربية) for all fields including summary, risk_reason, gpa_trend_analysis, bottleneck reasons, and recommendation descriptions.

Return ONLY a valid JSON object with this structure:
{
  "estimated_graduation_semester": "e.g. Semester 8 - Even 2027",
  "estimated_semesters_remaining": 3,
  "risk_level": "On Track | At Risk | Critical",
  "risk_reason": "Brief explanation of the risk level",
  "gpa_trend": "Improving | Stable | Declining",
  "gpa_trend_analysis": "Analysis of GPA trend and its impact on graduation",
  "bottleneck_courses": [
    {
      "course": "Course Name",
      "code": "Course Code",
      "reason": "Why this is a bottleneck"
    }
  ],
  "recommendations": [
    {
      "title": "Recommendation title",
      "description": "Detailed actionable advice"
    }
  ],
  "summary": "An encouraging 2-3 sentence overall summary for the student"
}
PROMPT;
    }

    protected function callLlm(string $prompt): array
    {
        return $this->provider === 'qwen'
            ? $this->callQwen($prompt)
            : $this->callGemini($prompt);
    }

    protected function callGemini(string $prompt): array
    {
        $response = Http::timeout(60)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [['parts' => [['text' => $prompt . "\n\nReturn JSON ONLY."]]]],
                'generationConfig' => ['response_mime_type' => 'application/json'],
            ]);

        if ($response->failed()) {
            throw new \Exception('Gemini API call failed: ' . $response->body());
        }

        $jsonText = $response->json('candidates.0.content.parts.0.text', '{}');
        return json_decode($jsonText, true) ?? [];
    }

    protected function callQwen(string $prompt): array
    {
        $response = Http::timeout(60)
            ->withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ])
            ->post('https://api.together.xyz/v1/chat/completions', [
                'model'           => $this->model,
                'messages'        => [['role' => 'user', 'content' => $prompt]],
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            throw new \Exception('Qwen API call failed: ' . $response->body());
        }

        $jsonText = $response->json('choices.0.message.content', '{}');
        return json_decode($jsonText, true) ?? [];
    }
}
