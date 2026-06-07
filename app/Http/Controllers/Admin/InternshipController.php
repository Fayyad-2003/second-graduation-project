<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Internship::with(['student.user', 'student.studyProgram', 'supervisor.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhereHas('student.user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('student', fn($q) => $q->where('student_number', 'like', "%{$search}%"));
            });
        }

        // Faculty scoping
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $query->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('order', 'desc');

        if ($sortColumn === 'student_name' || $sortColumn === '') {
            $query->join('students', 'internships.student_id', '=', 'students.id')
                  ->join('users', 'students.user_id', '=', 'users.id')
                  ->select('internships.*')
                  ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'student_number' || $sortColumn === '') {
            $query->join('students', 'internships.student_id', '=', 'students.id')
                  ->select('internships.*')
                  ->orderBy('students.student_number', $sortDirection);
        } elseif ($sortColumn === 'supervisor_name' || $sortColumn === '') {
            $query->leftJoin('lecturers', 'internships.supervisor_id', '=', 'lecturers.id')
                  ->leftJoin('users', 'lecturers.user_id', '=', 'users.id')
                  ->select('internships.*')
                  ->orderBy('users.name', $sortDirection);
        } elseif (in_array($sortColumn, ['company_name', 'status', 'created_at'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $internshipList = $query->paginate(20)->withQueryString();
        
        // Scope lecturers list for dropdown
        $lecturerQuery = Lecturer::with('user');
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $lecturerQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        $lecturerList = $lecturerQuery->get();
        $statusList = Internship::getStatusList();

        // Stats - also scoped
        $statsQuery = Internship::query();
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $statsQuery->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->active()->count(),
            'needs_supervisor' => (clone $statsQuery)->whereNull('supervisor_id')->count(),
            'completed' => (clone $statsQuery)->where('status', Internship::STATUS_COMPLETED)->count(),
        ];

        return view('admin.internship.index', compact('internshipList', 'lecturerList', 'statusList', 'stats'));
    }


    public function show(Internship $internship)
    {
        $internship->load(['student.user', 'supervisor.user', 'logbooks']);
        $lecturerList = Lecturer::with('user')->get();

        return view('admin.internship.show', compact('internship', 'lecturerList'));
    }

    public function assignSupervisor(Request $request, Internship $internship)
    {
        $validated = $request->validate([
            'supervisor_id' => 'required|exists:lecturers,id',
        ]);

        $internship->update([
            'supervisor_id' => $validated['supervisor_id'],
            'status' => Internship::STATUS_APPROVED,
        ]);

        return redirect()->back()->with('success', __('Supervisor successfully assigned.'));
    }

    public function updateStatus(Request $request, Internship $internship)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Internship::getStatusList())),
            'notes' => 'nullable|string',
        ]);

        $internship->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $internship->notes,
        ]);

        return redirect()->back()->with('success', __('Status successfully updated'));
    }

    public function updateGrades(Request $request, Internship $internship)
    {
        $validated = $request->validate([
            'company_grade' => 'nullable|numeric|min:0|max:100',
            'supervisor_grade' => 'nullable|numeric|min:0|max:100',
            'seminar_grade' => 'nullable|numeric|min:0|max:100',
            'final_grade' => 'required|numeric|min:0|max:100',
            'letter_grade' => 'required|in:A,B+,B,C+,C,D,E',
        ]);

        $internship->update([
            ...$validated,
            'status' => Internship::STATUS_COMPLETED,
        ]);

        return redirect()->back()->with('success', __('Grade successfully saved'));
    }
}
