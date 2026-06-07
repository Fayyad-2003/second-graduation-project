<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Material;
use App\Models\Meeting;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Show materials for a specific classes (students view)
     * Supports historical access for archived semesters
     */
    public function index($classId)
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(403, __('You do not have access as a student.'));
        }

        // Verify student is enrolled in this class (from any semester)
        $isEnrolled = $student->studyPlans()
            ->where('status', 'approved')
            ->whereHas('details', fn($q) => $q->where('class_id', $classId))
            ->exists();

        if (!$isEnrolled) {
            abort(403, __('You are not enrolled in this class.'));
        }

        $class = AcademicClass::with('course', 'lecturer.user', 'academicYear')->findOrFail($classId);

        // Check if this is an archived class
        $activeYear = AcademicYear::active();
        $isArchived = $activeYear === null || $class->academic_year_id !== $activeYear->id;

        // Get all meetings for this class
        $meetingList = Meeting::whereHas('courseSchedule', fn($q) => $q->where('class_id', $classId))
            ->with('materials')
            ->orderBy('meeting_number')
            ->get();

        return view('student.material.index', compact('class', 'meetingList', 'isArchived'));
    }

    /**
     * Download materials file
     */
    public function download($classId, Material $material)
    {
        $student = Auth::user()->student;

        // Verify student is enrolled in this class
        $isEnrolled = $student->studyPlans()
            ->where('status', 'approved')
            ->whereHas('details', fn($q) => $q->where('class_id', $classId))
            ->exists();

        if (!$isEnrolled) {
            abort(403, __('You are not enrolled in this class.'));
        }

        // Verify material belongs to this class
        $meeting = $material->meeting;
        if ($meeting->courseSchedule->class_id != $classId) {
            abort(403);
        }

        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            abort(404, __('File not found'));
        }

        return Storage::download($material->file_path, $material->file_name);
    }
}
