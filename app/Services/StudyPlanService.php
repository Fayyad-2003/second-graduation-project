<?php

namespace App\Services;

use App\Exceptions\StudyPlanException;
use App\Models\AcademicClass;
use App\Models\StudyPlan;
use App\Models\StudyPlanDetail;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudyPlanService
{
    protected AcademicService $academicService;
    protected AcademicCalculationService $calculationService;

    public function __construct(
        AcademicService $academicService,
        AcademicCalculationService $calculationService
    ) {
        $this->academicService = $academicService;
        $this->calculationService = $calculationService;
    }

    public function getActiveStudyPlanOrNew(Student $student)
    {
        // Use cached Academic Year from AcademicService
        $activeYear = $this->academicService->getActiveAcademicYear();
        if (!$activeYear) {
            throw StudyPlanException::noActiveSemester();
        }

        return StudyPlan::firstOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $activeYear->id
            ],
            ['status' => 'draft']
        );
    }

    /**
     * Calculate max credits based on last semester's GPA
     */
    public function getMaxCreditsForStudent(Student $student): int
    {
        // Get GPA history to find last semester's GPA
        $gpaHistory = $this->calculationService->getGPAHistory($student);

        // Filter only semesters with actual grades (GPA > 0)
        $semestersWithGrades = $gpaHistory->filter(fn($s) => $s['gpa'] > 0);

        if ($semestersWithGrades->isEmpty()) {
            // New student (semester 1) - use default max credits
            return config('siakad.max_credits.default', 24);
        }

        // Get the last semester's GPA
        $lastSemester = $semestersWithGrades->last();
        $lastGpa = $lastSemester['gpa'] ?? 0;

        // Calculate max credits based on GPA using rules from config
        return $this->calculationService->getMaxCredits($lastGpa);
    }

    public function addClass(StudyPlan $studyPlan, $classId)
    {
        return DB::transaction(function () use ($studyPlan, $classId) {
            if ($studyPlan->status !== 'draft') {
                throw StudyPlanException::alreadySubmitted();
            }

            $class = AcademicClass::with('course')->findOrFail($classId);

            // 1. Check capacity
            $enrolled = StudyPlanDetail::where('class_id', $classId)->count();
            if ($enrolled >= $class->capacity) {
                throw StudyPlanException::classFull($class->class_name, $class->capacity);
            }

            // 2. Check if course already taken in this study plan (different class)
            $courseTaken = $studyPlan->details()->whereHas('class', function ($q) use ($class) {
                $q->where('course_id', $class->course_id);
            })->exists();

            if ($courseTaken) {
                throw StudyPlanException::courseAlreadyTaken($class->course->course_name);
            }

            // 3. Check credit limit based on last semester's GPA
            $currentCredits = $studyPlan->details->sum(fn($detail) => $detail->class->course->credits);
            $newCredits = $class->course->credits;

            // Get student from study plan and calculate max credits based on GPA
            $student = $studyPlan->student;
            $maxCredits = $this->getMaxCreditsForStudent($student);

            if (($currentCredits + $newCredits) > $maxCredits) {
                throw StudyPlanException::sksLimitExceeded($currentCredits, $newCredits, $maxCredits);
            }

            // 4. Check credit limit per classification (Faculty Requirement)
            if ($class->course->subject_classification_id) {
                $classification = $class->course->classification;
                $requirement = \App\Models\FacultyRequirement::where('faculty_id', $student->studyProgram->faculty_id)
                    ->where('subject_classification_id', $classification->id)
                    ->first();

                if ($requirement && $requirement->required_credits > 0) {
                    // Count credits already taken (approved in any study plan)
                    $creditsTaken = StudyPlanDetail::whereHas('studyPlan', function ($q) use ($student) {
                        $q->where('student_id', $student->id)
                            ->where('status', 'approved');
                    })->whereHas('class.course', function ($q) use ($classification) {
                        $q->where('subject_classification_id', $classification->id);
                    })->get()->sum(fn($d) => $d->class->course->credits);

                    // Count credits in current draft (excluding the new one)
                    $creditsInDraft = $studyPlan->details->filter(function ($d) use ($classification) {
                        return $d->class->course->subject_classification_id == $classification->id;
                    })->sum(fn($d) => $d->class->course->credits);

                    if (($creditsTaken + $creditsInDraft + $newCredits) > $requirement->required_credits) {
                        throw StudyPlanException::classificationLimitExceeded(
                            $classification->name,
                            $creditsTaken + $creditsInDraft,
                            $newCredits,
                            $requirement->required_credits
                        );
                    }
                }
            }

            // 5. Check prerequisites
            $prerequisites = $class->course->prerequisites;
            foreach ($prerequisites as $prerequisite) {
                $isPassed = StudyPlanDetail::whereHas('studyPlan', function ($q) use ($student) {
                    $q->where('student_id', $student->id)
                        ->where('status', 'approved');
                })->whereHas('class', function ($q) use ($prerequisite) {
                    $q->where('course_id', $prerequisite->id);
                })->exists();

                if (!$isPassed) {
                    throw StudyPlanException::prerequisiteNotMet($class->course->course_name, $prerequisite->course_name);
                }
            }

            // Add class to study plan
            return StudyPlanDetail::create([
                'study_plan_id' => $studyPlan->id,
                'class_id' => $classId
            ]);
        });
    }

    public function removeClass(StudyPlan $studyPlan, $detailId)
    {
        if ($studyPlan->status !== 'draft') {
            throw StudyPlanException::locked();
        }

        $detail = $studyPlan->details()->findOrFail($detailId);
        $detail->delete();
    }

    public function submitStudyPlan(StudyPlan $studyPlan)
    {
        if ($studyPlan->details()->count() === 0) {
            throw StudyPlanException::emptyStudyPlan();
        }
        $studyPlan->update(['status' => 'pending']);
    }

    public function approveStudyPlan(StudyPlan $studyPlan)
    {
        if ($studyPlan->status !== 'pending') {
            throw StudyPlanException::invalidStatus($studyPlan->status, 'pending');
        }
        $studyPlan->update(['status' => 'approved']);
    }

    public function rejectStudyPlan(StudyPlan $studyPlan)
    {
        if ($studyPlan->status !== 'pending') {
            throw StudyPlanException::invalidStatus($studyPlan->status, 'pending');
        }
        $studyPlan->update(['status' => 'rejected']);
    }
}
