<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Services\AcademicCalculationService;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    protected $calculationService;

    public function __construct(AcademicCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Export Transcript as printable HTML (PDF-ready)
     */
    public function transcript()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        $student->load(['studyProgram.faculty', 'academicAdvisor.user']);

        // Get all grades
        $gradeList = Grade::where('student_id', $student->id)
            ->with('academicClass.course')
            ->get()
            ->sortBy('academicClass.course.course_code');

        // Calculate CGPA
        $cgpaData = $this->calculationService->calculateCGPA($student);

        // Get grade distribution
        $gradeDistribution = $this->calculationService->getGradeDistribution($student);

        return view('student.export.transcript', compact('student', 'gradeList', 'cgpaData', 'gradeDistribution'));
    }

    /**
     * Export grade report as printable HTML (PDF-ready)
     */
    public function gradeReport(AcademicYear $academicYear)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, __('Unauthorized'));
        }

        $student->load(['studyProgram.faculty', 'academicAdvisor.user']);

        // Check if student has approved Study Plan for this semester
        $studyPlan = StudyPlan::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('status', 'approved')
            ->first();

        if (!$studyPlan) {
            return redirect()->back()->with('error', __('Study plan for this semester has not been approved'));
        }

        // Get all grades for this semester
        $gradeList = Grade::where('student_id', $student->id)
            ->whereHas('academicClass', function ($q) use ($academicYear) {
                $q->where('academic_year_id', $academicYear->id);
            })
            ->with(['academicClass.course', 'academicClass.lecturer.user'])
            ->get()
            ->sortBy('academicClass.course.course_code');

        // Calculate GPA for this semester
        $gpaData = $this->calculationService->calculateGPA($student, $academicYear->id);

        // Get CGPA cumulative
        $cgpaData = $this->calculationService->calculateCGPA($student);

        return view('student.export.grade-report', compact('student', 'academicYear', 'gradeList', 'gpaData', 'cgpaData'));
    }
}
