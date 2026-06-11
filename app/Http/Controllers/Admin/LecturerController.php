<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\StudyProgram;
use App\Models\AcademicClass;
use App\Models\Grade;
use App\Models\StudyPlanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::with(['user', 'studyProgram.faculty'])
            ->withCount('classes');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by study program
        if ($studyProgramId = $request->get('study_program_id')) {
            $query->where('study_program_id', $studyProgramId);
        }

        // Faculty scoping
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $query->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $request->get('faculty_scope')));
        }

        // Sorting
        $sortColumn = $request->get('sort', 'lecturer_number');
        $sortDirection = $request->get('order', 'asc');

        if ($sortColumn === 'name') {
            $query->join('users', 'lecturers.user_id', '=', 'users.id')
                ->select('lecturers.*')
                ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'study_program') {
            $query->join('study_programs', 'lecturers.study_program_id', '=', 'study_programs.id')
                ->select('lecturers.*')
                ->orderBy('study_programs.name', $sortDirection);
        } else {
            $query->orderBy('lecturer_number', $sortDirection);
        }

        $lecturers = $query->paginate(config('system.pagination', 15))->withQueryString();

        // StudyProgram list scoped by faculty
        $studyProgramQuery = StudyProgram::with('faculty');
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $studyProgramQuery->where('faculty_id', $request->get('faculty_scope'));
        }
        $studyProgramList = $studyProgramQuery->get();

        return view('admin.lecturer.index', compact('lecturers', 'studyProgramList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'lecturer_number' => 'required|string|unique:lecturers,lecturer_number',
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'lecturer',
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'lecturer_number' => $validated['lecturer_number'],
                'study_program_id' => $validated['study_program_id'],
            ]);
        });

        return back()->with('success', __('Lecturer successfully added.'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $lecturer->user_id,
            'lecturer_number' => 'required|string|unique:lecturers,lecturer_number,' . $lecturer->id,
            'study_program_id' => 'required|exists:study_programs,id',
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($validated, $lecturer) {
            $lecturer->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $lecturer->user->update(['password' => Hash::make($validated['password'])]);
            }

            $lecturer->update([
                'lecturer_number' => $validated['lecturer_number'],
                'study_program_id' => $validated['study_program_id'],
            ]);
        });

        return back()->with('success', __('Lecturer successfully updated.'));
    }

    public function destroy(Lecturer $lecturer)
    {
        // Check if lecturer has classes
        if ($lecturer->classes()->exists()) {
            return back()->withErrors(['error' => __('Cannot delete lecturer with existing classes.')]);
        }

        // Check if lecturer is academic advisor for any students
        if ($lecturer->advisedStudents()->exists()) {
            return back()->withErrors(['error' => __('Cannot delete lecturer who is an academic advisor.')]);
        }

        DB::transaction(function () use ($lecturer) {
            $userId = $lecturer->user_id;
            $lecturer->delete();
            User::destroy($userId);
        });

        return back()->with('success', __('Lecturer successfully deleted.'));
    }

    public function show(Lecturer $lecturer)
    {
        $lecturer->load(['user', 'studyProgram.faculty', 'classes.course', 'classes.studyPlanDetails']);

        // Paginate classes (4 per page)
        $classIds = $lecturer->classes()->pluck('id');
        $teachingLoad = $lecturer->classes()->with(['course', 'studyPlanDetails'])->paginate(4);

        // Calculate totals for stats
        $totalCredits = $lecturer->classes->sum(fn($k) => $k->course->credits);
        $totalStudents = StudyPlanDetail::whereIn('class_id', $classIds)->count();

        return view('admin.lecturer.show', compact('lecturer', 'teachingLoad', 'totalCredits', 'totalStudents'));
    }

    public function export(Request $request)
    {
        $query = Lecturer::with(['user', 'studyProgram.faculty'])->withCount('classes');

        // Faculty scoping
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $query->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $request->get('faculty_scope')));
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('lecturer_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by study program
        if ($studyProgramId = $request->get('study_program_id')) {
            $query->where('study_program_id', $studyProgramId);
        }

        $lecturerList = $query->orderBy('lecturer_number')->get();

        // Generate HTML table for export
        $html = '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<thead><tr><th>No</th><th>Lecturer Number</th><th>Name</th><th>Email</th><th>Study Program</th><th>Faculty</th><th>Class Count</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($lecturerList as $idx => $lecturer) {
            $html .= '<tr>';
            $html .= '<td>' . ($idx + 1) . '</td>';
            $html .= '<td>' . $lecturer->lecturer_number . '</td>';
            $html .= '<td>' . $lecturer->user->name . '</td>';
            $html .= '<td>' . $lecturer->user->email . '</td>';
            $html .= '<td>' . ($lecturer->studyProgram->name ?? '-') . '</td>';
            $html .= '<td>' . ($lecturer->studyProgram->faculty->name ?? '-') . '</td>';
            $html .= '<td>' . $lecturer->classes_count . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="lecturers_export_' . date('Y-m-d') . '.xls"');
    }
}
