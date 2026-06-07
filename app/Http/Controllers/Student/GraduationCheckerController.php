<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\AcademicCalculationService;
use Illuminate\Support\Facades\Auth;

class GraduationCheckerController extends Controller
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

        $student->load(['studyProgram.faculty']);

        // Overall credit requirements
        $facultyRequiredCredits = $student->studyProgram->faculty->total_credits ?? 0;
        
        // Passed credits (based on grades A to C)
        $cgpaData = $this->calculationService->calculateCGPA($student);
        $totalCreditsPassed = $cgpaData['total_credits_passed'] ?? 0;

        // Classification-wise requirements
        $classificationProgress = $this->calculationService->getClassificationProgress($student);

        // Evaluate eligibility status
        $overallCreditsMet = ($totalCreditsPassed >= $facultyRequiredCredits);
        $allClassificationsMet = true;

        $classificationsData = [];
        foreach ($classificationProgress as $prog) {
            $met = $prog['total'] >= $prog['required'];
            if (!$met) {
                $allClassificationsMet = false;
            }

            $classificationsData[] = [
                'name' => $prog['name'],
                'completed' => $prog['total'],
                'required' => $prog['required'],
                'enrolled' => $prog['enrolled'],
                'percentage' => $prog['percentage'],
                'remaining' => max(0, $prog['required'] - $prog['total']),
                'status' => $met ? 'MET' : 'NOT_MET',
            ];
        }

        $eligible = $overallCreditsMet && $allClassificationsMet;
        $overallRemaining = max(0, $facultyRequiredCredits - $totalCreditsPassed);

        return view('student.graduation-checker.index', compact(
            'student',
            'facultyRequiredCredits',
            'totalCreditsPassed',
            'overallRemaining',
            'overallCreditsMet',
            'classificationsData',
            'eligible',
            'cgpaData'
        ));
    }
}
