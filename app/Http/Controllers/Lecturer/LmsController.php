<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class LmsController extends Controller
{
    /**
     * Show LMS dashboard with all classes for lecturers
     */
    public function index()
    {
        $lecturer = Auth::user()->lecturer;
        if (!$lecturer) {
            abort(403, __('You do not have access as a lecturer.'));
        }

        $activeTA = AcademicYear::active();

        $classQuery = $lecturer->classes()
            ->with(['course', 'assignments', 'materials']);
        
        if ($activeTA) {
            $classQuery->where(function($q) use ($activeTA) {
                $q->where('academic_year_id', $activeTA->id)
                  ->orWhereNull('academic_year_id');
            });
        }

        $classList = $classQuery->get()
            ->map(function($class) {
                $class->material_count = $class->materials()->count();
                $class->assignment_count = $class->assignments->count();
                return $class;
            });

        return view('lecturer.lms.index', compact('classList', 'activeTA'));
    }
}
