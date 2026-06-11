<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\StudyProgram;
use App\Models\Lecturer;
use App\Models\Faculty;
use App\Services\AcademicCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    protected AcademicCalculationService $calculationService;

    public function __construct(AcademicCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function index(Request $request)
    {
        $query = Student::with(['user', 'studyProgram.faculty', 'academicAdvisor.user']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by faculty
        if ($facultyId = $request->get('faculty_id')) {
            $query->whereHas('studyProgram', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        // Faculty scoping for admin_faculty
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $query->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $request->get('faculty_scope')));
        }

        // Filter by study program
        if ($studyProgramId = $request->get('study_program_id')) {
            $query->where('study_program_id', $studyProgramId);
        }

        // Filter by batch
        if ($batch = $request->get('batch')) {
            $query->where('batch', $batch);
        }

        // Variable Sorting
        $sortColumn = $request->get('sort', 'student_number');
        $sortDirection = $request->get('order', 'asc');

        if ($sortColumn === 'name') {
            $query->join('users', 'students.user_id', '=', 'users.id')
                ->select('students.*')
                ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'study_program') {
            $query->join('study_programs', 'students.study_program_id', '=', 'study_programs.id')
                ->select('students.*')
                ->orderBy('study_programs.name', $sortDirection);
        } elseif ($sortColumn === 'student_number') {
            $query->orderBy('student_number', $sortDirection);
        } elseif ($sortColumn === 'batch') {
            $query->orderBy('batch', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $students = $query->paginate(config('system.pagination', 15))->withQueryString();

        $facultyList = Faculty::orderBy('name')->get();
        $studyProgramList = StudyProgram::with('faculty')->orderBy('name')->get();
        $batchList = Student::distinct()->pluck('batch')->sort()->reverse();
        $lecturerList = Lecturer::with('user')->get();

        return view('admin.student.index', compact('students', 'facultyList', 'studyProgramList', 'batchList', 'lecturerList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'student_number' => 'required|string|unique:students,student_number',
            'study_program_id' => 'required|exists:study_programs,id',
            'batch' => 'required|numeric|min:2000|max:' . (date('Y') + 1),
            'academic_advisor_id' => 'nullable|exists:lecturers,id',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_number' => $validated['student_number'],
                'study_program_id' => $validated['study_program_id'],
                'batch' => $validated['batch'],
                'academic_advisor_id' => $validated['academic_advisor_id'] ?? null,
                'status' => 'active',
            ]);
        });

        return back()->with('success', __('Student successfully added.'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_number' => 'required|string|unique:students,student_number,' . $student->id,
            'study_program_id' => 'required|exists:study_programs,id',
            'batch' => 'required|numeric',
            'academic_advisor_id' => 'nullable|exists:lecturers,id',
            'status' => 'required|in:active,leave,graduated,dropout',
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($validated, $student) {
            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $student->user->update(['password' => Hash::make($validated['password'])]);
            }

            $student->update([
                'student_number' => $validated['student_number'],
                'study_program_id' => $validated['study_program_id'],
                'batch' => $validated['batch'],
                'academic_advisor_id' => $validated['academic_advisor_id'] ?? null,
                'status' => $validated['status'],
            ]);
        });

        return back()->with('success', __('Student successfully updated.'));
    }

    public function destroy(Student $student)
    {
        // Check if student has study plans
        if ($student->studyPlans()->exists()) {
            return back()->withErrors(['error' => __('Cannot delete student with study plan data.')]);
        }

        DB::transaction(function () use ($student) {
            $userId = $student->user_id;
            $student->delete();
            User::destroy($userId);
        });

        return back()->with('success', __('Student successfully deleted.'));
    }

    public function export(Request $request)
    {
        $query = Student::with(['user', 'studyProgram']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }
        if ($facultyId = $request->get('faculty_id')) {
            $query->whereHas('studyProgram', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }
        if ($studyProgramId = $request->get('study_program_id')) {
            $query->where('study_program_id', $studyProgramId);
        }
        if ($batch = $request->get('batch')) {
            $query->where('batch', $batch);
        }

        $query->orderBy('student_number');

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Student Number', 'Student Name', 'Study Program', 'Batch', 'Status', 'GPA']);

            $query->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->student_number,
                        $row->user->name ?? '-',
                        $row->studyProgram->name ?? '-',
                        $row->batch,
                        $row->status,
                        $row->gpa ?? 0,
                    ]);
                }
            });
            fclose($handle);
        }, 'students-data-' . date('Y-m-d') . '.csv');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'studyProgram.faculty', 'studyPlans.academicYear', 'studyPlans.details.academicClass.course', 'academicAdvisor.user']);

        $gpaData = $this->calculationService->calculateCGPA($student);
        $gpaHistory = $this->calculationService->getGPAHistory($student);
        $gradeDistribution = $this->calculationService->getGradeDistribution($student);

        return view('admin.student.show', [
            'student' => $student,
            'gpaData' => $gpaData,
            'gpaHistory' => $gpaHistory,
            'gradeDistribution' => $gradeDistribution
        ]);
    }
}
