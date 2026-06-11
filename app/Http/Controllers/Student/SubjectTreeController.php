<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class SubjectTreeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403, __('Unauthorized'));

        // Get all courses from student's study program, with prerequisites
        $courses = Course::with('prerequisites')
            ->where('study_program_id', $student->study_program_id)
            ->orderBy('semester')
            ->get();

        // Get all finished subjects (with passing grades)
        $finishedCourseIds = $student->grades()
            ->whereIn('letter_grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'])
            ->with('academicClass.course')
            ->get()
            ->pluck('academicClass.course.id')
            ->unique()
            ->toArray();

        // Group courses by semester
        $coursesBySemester = $courses->groupBy('semester');

        // Prepare course connections for JavaScript
        $courseConnections = [];
        foreach ($courses as $course) {
            foreach ($course->prerequisites as $prereq) {
                $courseConnections[] = [
                    'courseId' => $course->id,
                    'prereqId' => $prereq->id,
                    'prereqFinished' => in_array($prereq->id, $finishedCourseIds)
                ];
            }
        }

        return view('student.subject-tree.index', compact(
            'coursesBySemester',
            'finishedCourseIds',
            'courseConnections'
        ));
    }
}
