<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AcademicCalculationService;
use Illuminate\Support\Facades\Auth;

class TranscriptController extends Controller
{
    protected AcademicCalculationService $calculationService;

    public function __construct(AcademicCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function index()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        $transcript = $this->calculationService->getTranscript($student);
        $gpaHistory = $this->calculationService->getGPAHistory($student);
        $gradeDistribution = $this->calculationService->getGradeDistribution($student);
        $classificationProgress = $this->calculationService->getClassificationProgress($student);
        
        // Calculate max credits for next semester (use last semester WITH grades)
        $semestersWithGrades = $gpaHistory->filter(fn($s) => $s['gpa'] > 0);
        $lastGpa = $semestersWithGrades->last()['gpa'] ?? 0;
        $maxCredits = $this->calculationService->getMaxCredits($lastGpa);

        return view('student.transcript.index', compact(
            'transcript', 'gpaHistory', 'gradeDistribution', 'maxCredits', 'student', 'classificationProgress'
        ));
    }
}
