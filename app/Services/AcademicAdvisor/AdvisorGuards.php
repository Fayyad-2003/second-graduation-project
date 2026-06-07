<?php

namespace App\Services\AcademicAdvisor;

use InvalidArgumentException;

class AdvisorGuards
{
    /**
     * Result of a guard check
     */
    public const GUARD_PASS = 'pass';
    public const GUARD_FAIL = 'fail';
    public const GUARD_RETRY = 'retry';

    /**
     * Forbidden assumption phrases from config
     */
    protected array $forbiddenPhrases;

    public function __construct()
    {
        $this->forbiddenPhrases = config('academic_rules.forbidden_assumption_phrases', [
            'usually',
            'generally',
            'depends on',
            'in general',
            'normally',
            'often',
            'maybe around',
            'biasanya',
            'umumnya',
            'tergantung',
            'pada umumnya',
            'lazimnya',
            'seringkali',
            'mungkin sekitar',
        ]);
    }

    /**
     * Assert that required rules are present in context
     *
     * @throws InvalidArgumentException
     */
    public function assertRulesPresent(array $context): void
    {
        $requiredRules = [
            'graduation_total_credits',
            'thesis_min_credits',
        ];

        foreach ($requiredRules as $rule) {
            if (!isset($context['study_program_rules'][$rule]) || $context['study_program_rules'][$rule] <= 0) {
                throw new InvalidArgumentException(
                    "Required academic rule '{$rule}' is missing or invalid in context."
                );
            }
        }
    }

    /**
     * Validate that context has minimum required data
     *
     * @throws InvalidArgumentException
     */
    public function validateContext(array $context): void
    {
        $requiredKeys = ['student', 'study_program_rules', 'academic_summary'];

        foreach ($requiredKeys as $key) {
            if (!isset($context[$key]) || empty($context[$key])) {
                throw new InvalidArgumentException(
                    "Required context key '{$key}' is missing or empty."
                );
            }
        }

        if (!isset($context['student']['student_number'])) {
            throw new InvalidArgumentException(
                "Student number is required in context."
            );
        }
    }

    /**
     * Check if output contains generic assumption phrases
     */
    public function preventGenericAssumptions(string $output): array
    {
        $violations = [];
        $outputLower = strtolower($output);

        foreach ($this->forbiddenPhrases as $phrase) {
            if (str_contains($outputLower, strtolower($phrase))) {
                $violations[] = $phrase;
            }
        }

        if (empty($violations)) {
            return [
                'status' => self::GUARD_PASS,
                'violations' => [],
                'sanitized_output' => null,
                'retry_prompt' => null,
            ];
        }

        return [
            'status' => self::GUARD_RETRY,
            'violations' => $violations,
            'sanitized_output' => null,
            'retry_prompt' => "Your previous response contained generic assumption phrases (" . implode(', ', $violations) . "). Please re-answer using only the specific data and rules provided in the context. If the data is not available, state that clearly instead of assuming.",
        ];
    }

    /**
     * Run all post-generation guards
     */
    public function runPostGuards(array $context, string $output): array
    {
        $genericResult = $this->preventGenericAssumptions($output);
        
        if ($genericResult['status'] === self::GUARD_PASS) {
            return ['passed' => true, 'should_retry' => false, 'retry_prompt' => null, 'replacement_output' => null];
        }

        return [
            'passed' => false,
            'should_retry' => $genericResult['status'] === self::GUARD_RETRY,
            'retry_prompt' => $genericResult['retry_prompt'],
            'failed_guards' => ['generic_assumptions'],
            'replacement_output' => null,
        ];
    }
}
