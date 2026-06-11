<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisionController extends Controller
{
    /**
     * List of advised students
     */
    public function index(Request $request)
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, __('Unauthorized'));
        }

        $query = $lecturer->advisedStudents()
            ->with(['user', 'studyProgram', 'studyPlans' => fn($q) => $q->latest()]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter batch
        if ($request->filled('batch')) {
            $query->where('batch', $request->batch);
        }

        // Sorting
        $sortBy = $request->get('sort', 'batch');
        $sortDir = $request->get('dir', 'desc');

        // Validate sort direction
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

        // Apply sorting
        if ($sortBy === 'name') {
            // Sort by user name using join
            $query->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortDir)
                ->select('students.*');
        } elseif (in_array($sortBy, ['student_number', 'batch', 'status'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('batch', 'desc');
        }

        $advisedStudents = $query->paginate(10)->withQueryString();

        // Get available batches for filter
        $batchList = $lecturer->advisedStudents()->distinct()->pluck('batch')->sort()->reverse();

        if ($request->ajax()) {
            return view('lecturer.supervision._table', compact('advisedStudents'))->render();
        }

        return view('lecturer.supervision.index', compact('advisedStudents', 'batchList'));
    }

    /**
     * List of study plans that need approval
     */
    public function studyPlanApproval(Request $request)
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, __('Unauthorized'));
        }

        $status = $request->get('status', 'pending');

        // Get study plans from advised students
        $studentIds = $lecturer->advisedStudents()->pluck('id');

        $query = StudyPlan::with(['student.user', 'student.studyProgram', 'academicYear', 'details.class.course'])
            ->whereIn('study_plans.student_id', $studentIds)
            ->when($status !== 'all', fn($q) => $q->where('study_plans.status', $status));

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'updated_at');
        $sortDir = $request->get('dir', 'desc');
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

        if ($sortBy === 'name') {
            $query->join('students', 'study_plans.student_id', '=', 'students.id')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortDir)
                ->select('study_plans.*');
        } elseif ($sortBy === 'student_number') {
            $query->join('students', 'study_plans.student_id', '=', 'students.id')
                ->orderBy('students.student_number', $sortDir)
                ->select('study_plans.*');
        } elseif ($sortBy === 'status') {
            $query->orderBy('study_plans.status', $sortDir);
        } else {
            $query->orderBy('study_plans.updated_at', 'desc');
        }

        $studyPlanList = $query->paginate(config('system.pagination', 15))->withQueryString();

        // Optimized: Single query for status counts using groupBy
        $statusCountsRaw = StudyPlan::whereIn('student_id', $studentIds)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $statusCounts = [
            'pending' => $statusCountsRaw->get('pending', 0),
            'approved' => $statusCountsRaw->get('approved', 0),
            'rejected' => $statusCountsRaw->get('rejected', 0),
        ];

        if ($request->ajax()) {
            return view('lecturer.supervision._study-plan-table', compact('studyPlanList'))->render();
        }

        return view('lecturer.supervision.study-plan-approval', compact('studyPlanList', 'status', 'statusCounts'));
    }

    /**
     * Study plan detail
     */
    public function showStudyPlan(StudyPlan $studyPlan)
    {
        $lecturer = Auth::user()->lecturer;

        // Verify this student is under this lecturer's supervision
        if ($studyPlan->student->academic_advisor_id !== $lecturer->id) {
            abort(403, __('You do not have permission to access this study plan'));
        }

        $studyPlan->load(['student.user', 'student.studyProgram', 'academicYear', 'details.class.course', 'details.class.lecturer.user']);

        $totalCredits = $studyPlan->details->sum(fn($d) => $d->class->course->credits);

        return view('lecturer.supervision.study-plan-show', compact('studyPlan', 'totalCredits'));
    }

    /**
     * Approve study plan
     */
    public function approveStudyPlan(StudyPlan $studyPlan)
    {
        $lecturer = Auth::user()->lecturer;

        if ($studyPlan->student->academic_advisor_id !== $lecturer->id) {
            abort(403, __('You do not have permission to access this study plan'));
        }

        if ($studyPlan->status !== 'pending') {
            return redirect()->back()->with('error', __('Study plan is not in pending status'));
        }

        $studyPlan->update(['status' => 'approved']);

        return redirect()->route('lecturers.supervision.study-plan-approval')
            ->with('success', __('Study plan for student :name has been approved', ['name' => $studyPlan->student->user->name]));
    }

    /**
     * Reject study plan
     */
    public function rejectStudyPlan(Request $request, StudyPlan $studyPlan)
    {
        $lecturer = Auth::user()->lecturer;

        if ($studyPlan->student->academic_advisor_id !== $lecturer->id) {
            abort(403, __('You do not have permission to access this study plan'));
        }

        if ($studyPlan->status !== 'pending') {
            return redirect()->back()->with('error', __('Study plan is not in pending status'));
        }

        $notes = $request->input('notes', __('Study plan rejected by academic advisor. Please revise and resubmit.'));
        $studyPlan->update(['status' => 'rejected', 'notes' => $notes]);

        return redirect()->route('lecturers.supervision.study-plan-approval')
            ->with('success', __('Study plan for student :name has been rejected', ['name' => $studyPlan->student->user->name]));
    }
}
