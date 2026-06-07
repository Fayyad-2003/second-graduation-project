<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    /**
     * Check if user can access a specific study program
     */
    private function authorizeStudyProgramAccess(StudyProgram $studyProgram): void
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $user->faculty_id !== $studyProgram->faculty_id) {
            abort(response()->view('errors.403', ['message' => __('You do not have access to this study program.')], 403));
        }
    }

    /**
     * Get faculty IDs that user can access
     */
    private function getAccessibleFacultyIds()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return Faculty::pluck('id')->toArray();
        }
        return $user->faculty_id ? [$user->faculty_id] : [];
    }

    public function index()
    {
        $user = auth()->user();
        
        $query = Faculty::with(['studyPrograms' => function($q) {
            $q->withCount(['students', 'lecturers']);
        }]);
        
        // Scope to user's faculty if not superadmin
        if (!$user->isSuperAdmin() && $user->faculty_id) {
            $query->where('id', $user->faculty_id);
        }
        
        $faculties = $query->get();
        $isSuperAdmin = $user->isSuperAdmin();
        
        return view('admin.study-program.index', compact('faculties', 'isSuperAdmin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255',
        ]);
        
        // Check if user can create study program in this faculty
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $user->faculty_id != $validated['faculty_id']) {
            abort(response()->view('errors.403', ['message' => __('You cannot add a study program to this faculty.')], 403));
        }
        
        $this->academicService->createStudyProgram($validated);
        return redirect()->back()->with('success', __('Study program successfully added.'));
    }

    public function update(Request $request, StudyProgram $studyProgram)
    {
        $this->authorizeStudyProgramAccess($studyProgram);
        
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255',
        ]);
        
        // Check if user can move study program to target faculty
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $user->faculty_id != $validated['faculty_id']) {
            abort(response()->view('errors.403', ['message' => __('You cannot move a study program to another faculty.')], 403));
        }
        
        $studyProgram->update($validated);
        return redirect()->back()->with('success', __('Study program successfully updated.'));
    }

    public function destroy(StudyProgram $studyProgram)
    {
        $this->authorizeStudyProgramAccess($studyProgram);
        
        if ($studyProgram->students()->exists()) {
            return redirect()->back()->withErrors(['error' => __('Cannot delete study program with existing students.')]);
        }
        
        $studyProgram->delete();
        return redirect()->back()->with('success', __('Study program successfully deleted.'));
    }
}
