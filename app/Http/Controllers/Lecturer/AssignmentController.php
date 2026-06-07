<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * List all assignments for a class
     */
    public function index($classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()
            ->with(['course', 'assignments' => fn($q) => $q->latest()])
            ->findOrFail($classId);

        return view('lecturer.assignment.index', compact('class'));
    }

    /**
     * Create new assignment
     */
    public function store(Request $request, $classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
            'assignment_file' => 'nullable|file|max:10240',
            'allowed_extensions' => 'nullable|string|max:255',
        ]);

        $assignment = new Assignment([
            'class_id' => $class->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'],
            'allowed_extensions' => $validated['allowed_extensions'] ?? 'pdf,doc,docx,zip,rar',
        ]);

        if ($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $assignment->assignment_file = $file->storeAs("assignments/class_{$classId}", $filename);
        }

        $assignment->save();

        return back()->with('success', __('Assignment successfully created.'));
    }

    /**
     * Show assignment detail with submissions
     */
    public function show($classId, Assignment $assignment)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        if ($assignment->class_id != $classId) {
            abort(403);
        }

        $assignment->load(['submissions.student.user']);

        // Get enrolled students
        $enrolledStudents = \App\Models\Student::whereHas('studyPlans', function ($q) use ($classId) {
            $q->where('status', 'approved')
                ->whereHas('details', fn($q2) => $q2->where('class_id', $classId));
        })->with('user')->get();

        return view('lecturer.assignment.show', compact('class', 'assignment', 'enrolledStudents'));
    }

    /**
     * Grade a submission
     */
    public function grade(Request $request, $classId, AssignmentSubmission $submission)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        if ($submission->assignment->class_id != $classId) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'] ?? null,
            'graded_at' => now(),
            'graded_by' => Auth::id(),
        ]);

        return back()->with('success', __('Grade successfully saved.'));
    }

    /**
     * Download submission file
     */
    public function downloadSubmission($classId, AssignmentSubmission $submission)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        if ($submission->assignment->class_id != $classId) {
            abort(403);
        }

        if (!Storage::exists($submission->file_path)) {
            abort(404, __('File not found'));
        }

        return Storage::download($submission->file_path, $submission->file_name);
    }

    /**
     * Toggle assignment active status
     */
    public function toggle($classId, Assignment $assignment)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        if ($assignment->class_id != $classId) {
            abort(403);
        }

        $assignment->update(['is_active' => !$assignment->is_active]);

        return back()->with('success', __('Assignment status successfully changed.'));
    }

    /**
     * Delete assignment
     */
    public function destroy($classId, Assignment $assignment)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        if ($assignment->class_id != $classId) {
            abort(403);
        }

        // Delete related files
        if ($assignment->assignment_file) {
            Storage::delete($assignment->assignment_file);
        }

        foreach ($assignment->submissions as $submission) {
            Storage::delete($submission->file_path);
        }

        $assignment->delete();

        return back()->with('success', __('Assignment successfully deleted.'));
    }
}
