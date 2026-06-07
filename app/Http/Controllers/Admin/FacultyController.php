<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\SubjectClassification;
use App\Services\AcademicService;
use Illuminate\Http\Request;

class FacultyController extends Controller
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
            abort(response()->view('errors.403', ['message' => __('Only superadmins can manage faculties.')], 403));
        }
    }


    public function index()
    {
        $this->authorizeSuperAdmin();
        
        $faculties = $this->academicService->getAllFaculties();
        $classifications = SubjectClassification::all();
        return view('admin.faculty.index', compact('faculties', 'classifications'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();
        
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'total_credits' => 'required|integer|min:0',
            'requirements'  => 'nullable|array',
            'requirements.*'=> 'integer|min:0',
        ]);

        $faculty = $this->academicService->createFaculty([
            'name'          => $validated['name'],
            'total_credits' => $validated['total_credits'],
        ]);

        // Save per-classification requirements right away
        if (!empty($validated['requirements'])) {
            foreach ($validated['requirements'] as $classificationId => $credits) {
                $faculty->requirements()->updateOrCreate(
                    ['subject_classification_id' => $classificationId],
                    ['required_credits' => $credits]
                );
            }
        }

        return redirect()->back()->with('success', __('Faculty successfully added.'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $this->authorizeSuperAdmin();
        
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $faculty->update($validated);
        return redirect()->back()->with('success', __('Faculty successfully updated.'));
    }

    public function destroy(Faculty $faculty)
    {
        $this->authorizeSuperAdmin();
        
        $faculty->delete();
        return redirect()->back()->with('success', __('Faculty successfully deleted.'));
    }

    public function requirements(Faculty $faculty)
    {
        $this->authorizeSuperAdmin();
        
        $classifications = SubjectClassification::all();
        $requirements = $faculty->requirements()->get()->keyBy('subject_classification_id');
        
        return view('admin.faculty.requirements', compact('faculty', 'classifications', 'requirements'));
    }

    public function updateRequirements(Request $request, Faculty $faculty)
    {
        $this->authorizeSuperAdmin();
        
        $validated = $request->validate([
            'total_credits'  => 'required|integer|min:0',
            'requirements'   => 'required|array',
            'requirements.*' => 'integer|min:0',
        ]);

        $faculty->update(['total_credits' => $validated['total_credits']]);
        
        foreach ($validated['requirements'] as $classificationId => $credits) {
            $faculty->requirements()->updateOrCreate(
                ['subject_classification_id' => $classificationId],
                ['required_credits' => $credits]
            );
        }
        
        return redirect()->route('admin.faculty.index')->with('success', __('Credit requirements successfully updated.'));
    }
}
