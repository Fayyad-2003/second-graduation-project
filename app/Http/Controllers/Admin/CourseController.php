<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\SubjectClassification;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function index(Request $request)
    {
        $query = Course::with(['studyProgram.faculty', 'classification', 'prerequisites']);

        if ($request->filled('category')) {
            $query->where('course_code', 'like', $request->category . '%');
        }

        if ($request->filled('classification_id')) {
            $query->where('subject_classification_id', $request->classification_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                    ->orWhere('course_code', 'like', "%{$search}%");
            });
        }

        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $query->where(function ($q) use ($facultyId) {
                $q->whereHas('studyProgram', fn($q2) => $q2->where('faculty_id', $facultyId))
                    ->orWhereNull('study_program_id');
            });
        }

        $sortColumn = $request->get('sort', 'course_code');
        $sortDirection = $request->get('order', 'asc');

        $allowedSorts = ['course_code', 'course_name', 'credits', 'semester', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('course_code', 'asc');
        }

        $courses = $query->paginate(config('system.pagination', 15))->withQueryString();
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $faculties = $isSuperAdmin ? Faculty::all() : collect();

        $studyProgramsQuery = StudyProgram::with('faculty');
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $studyProgramsQuery->where('faculty_id', $request->get('faculty_scope'));
        }
        $studyPrograms = $studyProgramsQuery->get();
        $allCourses = Course::orderBy('course_name')->get();
        $classifications = SubjectClassification::all();

        return view('admin.course.index', compact('courses', 'studyPrograms', 'faculties', 'isSuperAdmin', 'allCourses', 'classifications'));
    }

    public function export(Request $request)
    {
        $query = Course::query();

        if ($request->filled('category')) {
            $query->where('course_code', 'like', $request->category . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('course_name', 'like', "%{$search}%")
                    ->orWhere('course_code', 'like', "%{$search}%");
            });
        }

        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $query->where(function ($q) use ($facultyId) {
                $q->whereHas('studyProgram', fn($q2) => $q2->where('faculty_id', $facultyId))
                    ->orWhereNull('study_program_id');
            });
        }

        $sortColumn = $request->get('sort', 'course_code');
        $sortDirection = $request->get('order', 'asc');
        $allowedSorts = ['course_code', 'course_name', 'credits', 'semester', 'created_at'];

        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Code', 'Course Name', 'Credits', 'Semester', 'Classification', 'Study Program', 'Created At']);

            $query->with(['studyProgram', 'classification'])->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->course_code,
                        $row->course_name,
                        $row->credits,
                        $row->semester,
                        $row->classification?->name ?? '-',
                        $row->studyProgram?->name ?? '-',
                        $row->created_at,
                    ]);
                }
            });

            fclose($handle);
        }, 'courses-' . date('Y-m-d-H-i') . '.csv');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|unique:courses,course_code',
            'course_name' => 'required|string',
            'credits' => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'subject_classification_id' => 'nullable|exists:subject_classifications,id',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id',
        ]);

        if (empty($validated['study_program_id']) && $request->get('faculty_scoped')) {
            $studyProgram = StudyProgram::where('faculty_id', $request->get('faculty_scope'))->first();
            if ($studyProgram) {
                $validated['study_program_id'] = $studyProgram->id;
            }
        }

        $course = Course::create($validated);

        if (!empty($request->prerequisites)) {
            $course->prerequisites()->sync($request->prerequisites);
        }

        return redirect()->back()->with('success', __('Course created successfully'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|string',
            'credits' => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'subject_classification_id' => 'nullable|exists:subject_classifications,id',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id',
        ]);

        $course->update($validated);
        $course->prerequisites()->sync($request->prerequisites ?? []);

        return redirect()->back()->with('success', __('Course updated successfully'));
    }

    public function destroy(Course $course)
    {
        if ($course->classes()->exists()) {
            return redirect()->back()->withErrors(['error' => __('Cannot delete course with existing classes.')]);
        }

        $course->delete();
        return redirect()->back()->with('success', __('Course deleted successfully'));
    }
}
