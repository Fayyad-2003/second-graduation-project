<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class StudyPlanException extends SystemException
{
    protected string $errorCode = 'STUDY_PLAN_ERROR';

    public static function noActiveSemester(): self
    {
        return new self('No active academic year found. Please contact administrator.');
    }

    public static function alreadySubmitted(): self
    {
        return new self('Study plan has already been submitted and cannot be modified.');
    }

    public static function classFull(string $className, int $capacity): self
    {
        return new self("Class {$className} is full (capacity: {$capacity}).");
    }

    public static function courseAlreadyTaken(string $courseName): self
    {
        return new self("Course {$courseName} is already in your study plan.");
    }

    public static function sksLimitExceeded(int $current, int $new, int $max): self
    {
        return new self("Credit limit exceeded. Current: {$current} credits, Adding: {$new} credits, Maximum allowed: {$max} credits.");
    }

    public static function classificationLimitExceeded(string $classification, int $current, int $new, int $max): self
    {
        return new self("Credit limit for {$classification} exceeded. Current: {$current} credits, Adding: {$new} credits, Maximum: {$max} credits.");
    }

    public static function prerequisiteNotMet(string $courseName, string $prerequisite): self
    {
        return new self("Cannot take {$courseName}. Prerequisite course {$prerequisite} has not been completed.");
    }

    public static function locked(): self
    {
        return new self('Study plan is locked and cannot be modified.');
    }

    public static function emptyStudyPlan(): self
    {
        return new self('Cannot submit empty study plan. Please add at least one course.');
    }

    public static function invalidStatus(string $current, string $expected): self
    {
        return new self("Invalid study plan status. Current: {$current}, Expected: {$expected}.");
    }
}
