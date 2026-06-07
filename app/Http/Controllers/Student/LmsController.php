<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LmsController extends Controller
{
    /**
     * Show LMS dashboard with enrolled classes
     * Supports semester filtering: active, archive, all
     */
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, __('You do not have access as a student.'));
        }

        // Get active year akademik
        $activeYear = AcademicYear::active();

        // Get all approved study plans with classes data 
        $allKrs = $student->studyPlans()
            ->where('status', 'approved')
            ->with(['academicYear', 'details.academicClass.course', 'details.academicClass.lecturer.user', 'details.academicClass.academicYear', 'details.academicClass.chatRequests' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->get();

        // Extract all classes from study plan details with the plan's academic years for proper filtering 
        $allClass = $allKrs->flatMap(function ($studyPlans) {
            return $studyPlans->details->map(function ($detail) use ($studyPlans) {
                $classes = $detail->academicClass;
                $classes->enrolled_academic_year = $studyPlans->academicYear;
                $classes->enrolled_academic_year_id = $studyPlans->academic_year_id; // Use this for filtering! 
                return $classes;
            });
        })->unique('id');

        // Get available semesters for dropdown with sequential numbering
        $semesterList = $allKrs->pluck('academicYear')->unique('id')->sortBy('id'); // Sort chronologically (oldest first)

        // Add semester number (Semester 1, 2, 3...) based on enrollment order
        $semesterNumber = 1;
        $availableSemesters = $semesterList->map(function ($semester) use (&$semesterNumber) {
            $semester->semester_number = $semesterNumber;
            $semester->semester_label = __('Semester :number', ['number' => $semesterNumber]);
            $semesterNumber++;
            return $semester;
        })->sortByDesc('id'); // Sort back to newest first for dropdown display

        // Get current semester number (for active semester)
        $currentSemesterNumber = $activeYear ? $availableSemesters->firstWhere('id', $activeYear->id)?->semester_number : null;

        // Determine semester filter - USE enrolled_academic_year_id for filtering (semester when enrolled)
        if ($activeYear === null) {
            // Libur semester: check if specific semester is selected
            $semesterFilter = $request->query('semester', 'all');

            if (is_numeric($semesterFilter)) {
                // Filter by specific semester (using enrollment academic year)
                $classList = $allClass->filter(fn($k) => $k->enrolled_academic_year_id == $semesterFilter);
            } else {
                // Show all classes
                $classList = $allClass;
                $semesterFilter = 'all';
            }
        } else {
            $semesterFilter = $request->query('semester', 'active');

            if (is_numeric($semesterFilter)) {
                // Filter by specific semester ID (using enrollment academic year)
                $classList = $allClass->filter(fn($k) => $k->enrolled_academic_year_id == $semesterFilter);
            } elseif ($semesterFilter === 'active') {
                // Show active semester classes (using enrollment academic year)
                $classList = $allClass->filter(fn($k) => $k->enrolled_academic_year_id === $activeYear->id);
            } else {
                // Default to active
                $classList = $allClass->filter(fn($k) => $k->enrolled_academic_year_id === $activeYear->id);
                $semesterFilter = 'active';
            }
        }

        // Add pending assignments count and archive status to each classes
        $classList = $classList->map(function ($classes) use ($student, $activeYear) {
            // Count pending assignments (only for active semester)
            // Use enrolled_academic_year_id to determine if archived (based on enrollment semester)
            $isArchived = $activeYear === null || $classes->enrolled_academic_year_id !== $activeYear->id;
            $classes->is_archived = $isArchived;

            if (!$isArchived) {
                $submittedAssignmentIds = $student->assignmentSubmissions()->pluck('assignment_id')->toArray();
                $activeAssignments = $classes->assignments()->where('is_active', true)->get();
                $classes->pending_assignments = $activeAssignments->whereNotIn('id', $submittedAssignmentIds)->count();
            } else {
                $classes->pending_assignments = 0;
            }

            return $classes;
        });

        // Group by semester for display (especially for 'all' views)
        // Use enrolled_academic_year_id for proper grouping by enrollment semester
        $classGrouped = $classList->groupBy(function ($classes) use ($availableSemesters) {
            $semester = $availableSemesters->firstWhere('id', $classes->enrolled_academic_year_id);
            return $semester?->semester_label ?? $classes->enrolled_academic_year->display_name ?? 'Unknown';
        });

        return view('student.lms.index', compact(
            'classList',
            'classGrouped',
            'semesterFilter',
            'availableSemesters',
            'activeYear',
            'currentSemesterNumber'
        ));
    }
}
