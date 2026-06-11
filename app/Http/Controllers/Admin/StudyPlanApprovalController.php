<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyPlan;
use Illuminate\Http\Request;

class StudyPlanApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $studyPlanList = StudyPlan::with(['student.user', 'student.studyProgram.faculty', 'academicYear', 'details.class.course'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status));

        // Faculty scoping for faculty admin
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $studyPlanList->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Sorting
        $sortColumn = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('order', 'desc');

        if ($sortColumn === 'name') {
            $studyPlanList = $studyPlanList->join('students', 'study_plans.student_id', '=', 'students.id')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->select('study_plans.*')
                ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'student_number') {
            $studyPlanList = $studyPlanList->join('students', 'study_plans.student_id', '=', 'students.id')
                ->select('study_plans.*')
                ->orderBy('students.student_number', $sortDirection);
        } elseif ($sortColumn === 'study_program') {
            $studyPlanList = $studyPlanList->join('students', 'study_plans.student_id', '=', 'students.id')
                ->join('study_programs', 'students.study_program_id', '=', 'study_programs.id')
                ->select('study_plans.*')
                ->orderBy('study_programs.study_program_name', $sortDirection);
        } elseif ($sortColumn === 'status') {
            $studyPlanList = $studyPlanList->orderBy('status', $sortDirection);
        } else {
            $studyPlanList = $studyPlanList->orderBy('updated_at', 'desc');
        }

        $studyPlanList = $studyPlanList->paginate(config('system.pagination', 15))->withQueryString();

        // Status counts - also scoped for faculty admin
        $statusCountsQuery = StudyPlan::query();
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $statusCountsQuery->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Optimized: Single query for status counts using groupBy
        $statusCountsRaw = (clone $statusCountsQuery)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $statusCounts = [
            'pending' => $statusCountsRaw->get('pending', 0),
            'approved' => $statusCountsRaw->get('approved', 0),
            'rejected' => $statusCountsRaw->get('rejected', 0),
            'draft' => $statusCountsRaw->get('draft', 0),
        ];

        return view('admin.study-plan-approval.index', compact('studyPlanList', 'status', 'statusCounts'));
    }


    public function show(StudyPlan $studyPlan)
    {
        $studyPlan->load(['student.user', 'student.studyProgram', 'academicYear', 'details.class.course', 'details.class.lecturer.user']);

        $totalCredits = $studyPlan->details->sum(fn($d) => $d->class->course->credits);

        return view('admin.study-plan-approval.show', compact('studyPlan', 'totalCredits'));
    }

    public function approve(Request $request, StudyPlan $studyPlan)
    {
        if ($studyPlan->status !== 'pending') {
            return redirect()->back()->with('error', __('Study plan is not in pending status'));
        }

        $studyPlan->update(['status' => 'approved']);

        return redirect()->route('admin.study-plan-approval.index')
            ->with('success', __('Study plan for student :name has been approved', ['name' => $studyPlan->student->user->name]));
    }

    public function reject(Request $request, StudyPlan $studyPlan)
    {
        if ($studyPlan->status !== 'pending') {
            return redirect()->back()->with('error', __('Study plan is not in pending status'));
        }

        $studyPlan->update(['status' => 'rejected']);

        return redirect()->route('admin.study-plan-approval.index')
            ->with('success', __('Study plan for student :name has been rejected', ['name' => $studyPlan->student->user->name]));
    }

    public function bulkApprove(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'study_plan_ids' => 'required|array|min:1',
            'study_plan_ids.*' => 'required|integer|exists:study_plans,id',
        ], [
            'study_plan_ids.required' => __('Select at least one study plan'),
            'study_plan_ids.array' => __('Invalid data format'),
            'study_plan_ids.min' => __('Select at least one study plan'),
            'study_plan_ids.*.integer' => __('Invalid study plan ID'),
            'study_plan_ids.*.exists' => __('Study plan not found'),
        ]);

        $ids = $validated['study_plan_ids'];

        // Build query with faculty scope for faculty admin
        $query = StudyPlan::whereIn('id', $ids)->where('status', 'pending');

        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $query->whereHas('student.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        $updatedCount = $query->update(['status' => 'approved']);

        if ($updatedCount === 0) {
            return redirect()->back()->with('warning', __('No study plans could be approved (may have already been processed or outside your authority)'));
        }

        return redirect()->back()->with('success', __('Successfully approved :count study plans', ['count' => $updatedCount]));
    }
}
