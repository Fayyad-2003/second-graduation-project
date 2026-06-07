<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * List all assignments for a classes
     * Supports historical access for archived semesters
     */
    public function index($classId)
    {
        $student = Auth::user()->student;

        // Verify enrollment (from any semester)
        $isEnrolled = $student->studyPlans()
            ->where('status', 'approved')
            ->whereHas('details', fn($q) => $q->where('class_id', $classId))
            ->exists();

        if (!$isEnrolled) {
            abort(403, __('You are not enrolled in this class.'));
        }

        $class = AcademicClass::with(['course', 'lecturer.user', 'academicYear'])->findOrFail($classId);

        // Check if this is an archived class
        $activeYear = AcademicYear::active();
        $isArchived = $activeYear === null || $class->academic_year_id !== $activeYear->id;

        // Get assignments with submission status (show all assignments for archived, only active for current)
        $assignmentQuery = Assignment::where('class_id', $classId)
            ->with(['submissions' => fn($q) => $q->where('student_id', $student->id)]);

        // For active semester, only show active assignments. For archived, show all.
        if (!$isArchived) {
            $assignmentQuery->where('is_active', true);
        }

        $assignments = $assignmentQuery->latest()->get();

        return view('student.assignment.index', compact('class', 'assignments', 'student', 'isArchived'));
    }

    /**
     * Show assignments detail
     * Supports historical access for archived semesters
     */
    public function show($classId, Assignment $assignment)
    {
        $student = Auth::user()->student;

        // Verify enrollment (from any semester)
        $isEnrolled = $student->studyPlans()
            ->where('status', 'approved')
            ->whereHas('details', fn($q) => $q->where('class_id', $classId))
            ->exists();

        if (!$isEnrolled || $assignment->class_id != $classId) {
            abort(403);
        }

        $class = AcademicClass::with(['course', 'lecturer.user', 'academicYear'])->findOrFail($classId);

        // Check if this is an archived class
        $activeYear = AcademicYear::active();
        $isArchived = $activeYear === null || $class->academic_year_id !== $activeYear->id;

        $submission = $assignment->submissions()->where('student_id', $student->id)->first();

        return view('student.assignment.show', compact('class', 'assignment', 'submission', 'isArchived'));
    }

    /**
     * Submit assignments
     */
    public function submit(Request $request, $classId, Assignment $assignment)
    {
        $student = Auth::user()->student;

        // Verify enrollment
        $isEnrolled = $student->studyPlans()
            ->where('status', 'approved')
            ->whereHas('details', fn($q) => $q->where('class_id', $classId))
            ->exists();

        if (!$isEnrolled || $assignment->class_id != $classId) {
            abort(403);
        }

        // Check if still open
        if (!$assignment->isOpen()) {
            return back()->with('error', __('Deadline has passed or assignment is not active.'));
        }

        // Check if already submitted
        $existingSubmission = $assignment->submissions()
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return back()->with('error', __('You have already submitted this assignment.'));
        }

        // Validate file
        $maxSize = $assignment->max_file_size / 1024; // Convert to KB for validation
        $allowedExt = $assignment->allowed_extensions;

        $validated = $request->validate([
            'file' => "required|file|max:{$maxSize}|mimes:{$allowedExt}",
            'notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $student->student_number . '_' . $file->getClientOriginalName();
        $path = $file->storeAs("assignments/class_{$classId}/assignment_{$assignment->id}", $filename);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'notes' => $validated['notes'] ?? null,
            'submitted_at' => now(),
        ]);

        return back()->with('success', __('Assignment successfully submitted.'));
    }

    /**
     * Download assignments file (soal)
     */
    public function download($classId, Assignment $assignment)
    {
        $student = Auth::user()->student;

        // Verify enrollment
        $isEnrolled = $student->studyPlans()
            ->where('status', 'approved')
            ->whereHas('details', fn($q) => $q->where('class_id', $classId))
            ->exists();

        if (!$isEnrolled || $assignment->class_id != $classId) {
            abort(403);
        }

        if (!$assignment->assignment_file || !Storage::exists($assignment->assignment_file)) {
            abort(404, __('File not found'));
        }

        return Storage::download($assignment->assignment_file);
    }
}
