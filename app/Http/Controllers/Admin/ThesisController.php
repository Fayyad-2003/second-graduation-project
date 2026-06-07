<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class ThesisController extends Controller
{
    public function index(Request $request)
    {
        $query = Thesis::with(['student.user', 'student.studyProgram', 'supervisor1.user', 'supervisor2.user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('student.user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('student', fn($q) => $q->where('student_number', 'like', "%{$search}%"));
            });
        }

        // Faculty scoping for admin_faculty
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $query->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('order', 'desc');

        if ($sortColumn === 'student_name' || $sortColumn === '') {
            $query->join('students', 'theses.student_id', '=', 'students.id')
                  ->join('users', 'students.user_id', '=', 'users.id')
                  ->select('theses.*')
                  ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'student_number' || $sortColumn === '') {
            $query->join('students', 'theses.student_id', '=', 'students.id')
                  ->select('theses.*')
                  ->orderBy('students.student_number', $sortDirection);
        } elseif ($sortColumn === 'supervisor_name' || $sortColumn === '') {
            $query->leftJoin('lecturers', 'theses.supervisor1_id', '=', 'lecturers.id')
                  ->leftJoin('users', 'lecturers.user_id', '=', 'users.id')
                  ->select('theses.*')
                  ->orderBy('users.name', $sortDirection);
        } elseif (in_array($sortColumn, ['title', 'status', 'created_at'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $thesisList = $query->paginate(20)->withQueryString();
        
        // Scope lecturers list for dropdown
        $lecturerQuery = Lecturer::with('user');
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $lecturerQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        $lecturerList = $lecturerQuery->get();
        $statusList = Thesis::getStatusList();

        // Stats - also scoped
        $statsQuery = Thesis::query();
        if (($request->get('faculty_scoped') || $request->get('faculty_scoped')) && 
            ($request->get('faculty_scope') || $request->get('faculty_scope'))) {
            $facultyId = $request->get('faculty_scope') ?? $request->get('faculty_scope');
            $statsQuery->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->whereNotIn('status', [Thesis::STATUS_COMPLETED, Thesis::STATUS_REJECTED])->count(),
            'waiting_for_supervisor' => (clone $statsQuery)->whereNull('supervisor1_id')->count(),
            'completed' => (clone $statsQuery)->where('status', Thesis::STATUS_COMPLETED)->count(),
        ];

        return view('admin.thesis.index', compact('thesisList', 'lecturerList', 'statusList', 'stats'));
    }


    public function show(Thesis $theses)
    {
        $theses->load(['student.user', 'supervisor1.user', 'supervisor2.user', 'supervisions.supervisor1.user']);
        $lecturerList = Lecturer::with('user')->get();

        return view('admin.thesis.show', compact('theses', 'lecturerList'));
    }

    public function assignSupervisor(Request $request, Thesis $theses)
    {
        $validated = $request->validate([
            'supervisor1_id' => 'required|exists:lecturers,id',
            'supervisor2_id' => 'nullable|exists:lecturers,id|different:supervisor1_id',
        ]);

        $theses->update([
            'supervisor1_id' => $validated['supervisor1_id'],
            'supervisor2_id' => $validated['supervisor2_id'] ?? null,
            'status' => Thesis::STATUS_ACCEPTED,
            'title_approval_date' => now(),
        ]);

        return redirect()->back()->with('success', __('Supervisor successfully assigned'));
    }

    public function updateStatus(Request $request, Thesis $theses)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Thesis::getStatusList())),
            'admin_notes' => 'nullable|string',
        ]);

        $updateData = ['status' => $validated['status']];

        if (!empty($validated['admin_notes'])) {
            $updateData['admin_notes'] = $validated['admin_notes'];
        }

        // Update milestone dates based on status
        $dateFields = [
            Thesis::STATUS_ACCEPTED => 'title_approval_date',
            Thesis::STATUS_PROPOSAL_SEMINAR => 'proposal_seminar_date',
            Thesis::STATUS_RESULT_SEMINAR => 'result_seminar_date',
            Thesis::STATUS_DEFENSE => 'defense_date',
            Thesis::STATUS_COMPLETED => 'completion_date',
        ];

        if (isset($dateFields[$validated['status']]) && empty($theses->{$dateFields[$validated['status']]})) {
            $updateData[$dateFields[$validated['status']]] = now();
        }

        $theses->update($updateData);

        return redirect()->back()->with('success', __('Thesis status successfully updated'));
    }

    public function updateGrades(Request $request, Thesis $theses)
    {
        $validated = $request->validate([
            'final_grade' => 'required|numeric|min:0|max:100',
            'letter_grade' => 'required|in:A,B+,B,C+,C,D,E',
        ]);

        $theses->update([
            'final_grade' => $validated['final_grade'],
            'letter_grade' => $validated['letter_grade'],
            'status' => Thesis::STATUS_COMPLETED,
            'completion_date' => now(),
        ]);

        return redirect()->back()->with('success', __('Thesis grade successfully saved'));
    }
}
