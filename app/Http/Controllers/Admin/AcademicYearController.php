<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    /**
     * Check if user is superadmin, abort if not
     */
    private function authorizeSuperAdmin(): void
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(response()->view('errors.403', ['message' => __('Only superadmins can manage academic years.')], 403));
        }
    }


    public function index()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')
            ->orderBy('semester', 'desc')
            ->paginate(config('system.pagination', 15));

        return view('admin.academic-year.index', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'year' => 'required|string|max:9', // e.g., "2024/2025"
            'semester' => 'required|in:odd,even',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'study_plan_start_date' => 'nullable|date',
            'study_plan_end_date' => 'nullable|date|after_or_equal:study_plan_start_date',
        ]);

        // Check for duplicate
        $exists = AcademicYear::where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['year' => __('This academic year and semester already exists.')]);
        }

        AcademicYear::create([
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'is_active' => false,
            'start_date' => $validated['start_date'] ?? null,
            'completion_date' => $validated['completion_date'] ?? null,
            'study_plan_start_date' => $validated['study_plan_start_date'] ?? null,
            'study_plan_end_date' => $validated['study_plan_end_date'] ?? null,
        ]);

        return back()->with('success', __('Academic year added successfully.'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'year' => 'required|string|max:9',
            'semester' => 'required|in:odd,even',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'study_plan_start_date' => 'nullable|date',
            'study_plan_end_date' => 'nullable|date|after_or_equal:study_plan_start_date',
        ]);

        // Check for duplicate (excluding current)
        $exists = AcademicYear::where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->where('id', '!=', $academicYear->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['year' => __('This academic year and semester already exists.')]);
        }

        $academicYear->update($validated);

        return back()->with('success', __('Academic year updated successfully.'));
    }

    public function destroy(AcademicYear $academicYear)
    {
        $this->authorizeSuperAdmin();

        if ($academicYear->studyPlans()->exists() || $academicYear->classes()->exists()) {
            return back()->withErrors(['error' => __('Cannot delete academic year with existing data.')]);
        }

        $academicYear->delete();
        return back()->with('success', __('Academic year deleted successfully.'));
    }

    public function activate(AcademicYear $academicYear)
    {
        $this->authorizeSuperAdmin();

        $this->academicService->activateAcademicYear($academicYear->id);

        return back()->with('success', __('Academic year activated successfully.'));
    }
}
