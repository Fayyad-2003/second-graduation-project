<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\ClassWaitlist;
use App\Models\Notification;
use App\Services\StudyPlanService;
use App\Services\AcademicCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyPlanController extends Controller
{
    protected $studyPlanService;
    protected $calculationService;

    public function __construct(StudyPlanService $studyPlanService, AcademicCalculationService $calculationService)
    {
        $this->studyPlanService = $studyPlanService;
        $this->calculationService = $calculationService;
    }

    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) abort(403, __('Unauthorized'));

        $studyPlan = $this->studyPlanService->getActiveStudyPlanOrNew($student);

        // Load available classes (that are not yet taken), grouped by semester
        // IMPORTANT: Only show classes from courses of student's study program
        $availableClasses = AcademicClass::with(['course', 'lecturer.user', 'details'])
            ->whereHas('course', fn($q) => $q->where('study_program_id', $student->study_program_id))
            ->whereDoesntHave('details', function($q) use ($studyPlan) {
                $q->where('study_plan_id', $studyPlan->id);
            })
            ->get()
            ->groupBy(fn($class) => 'Semester ' . $class->course->semester);

        // Sort by semester number
        $availableClasses = $availableClasses->sortKeys();

        // Get classification progress
        $classificationProgress = $this->calculationService->getClassificationProgress($student);

        // Get all finished subjects (with passing grades)
        $finishedSubjects = \App\Models\Grade::where('student_id', $student->id)
            ->whereIn('letter_grade', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'])
            ->with('academicClass.course')
            ->get()
            ->groupBy(fn($grade) => 'Semester ' . $grade->academicClass->course->semester);

        // Collect class IDs this student is currently on the waitlist for
        $waitlistedClassIds = ClassWaitlist::where('student_id', $student->id)
            ->pluck('class_id')
            ->flip(); // Use as a set for O(1) lookup in Blade

        return view('student.study-plan.index', compact(
            'studyPlan',
            'availableClasses',
            'classificationProgress',
            'waitlistedClassIds',
            'finishedSubjects'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:classes,id']);

        $student = Auth::user()->student;
        $studyPlan = $this->studyPlanService->getActiveStudyPlanOrNew($student);

        try {
            $this->studyPlanService->addClass($studyPlan, $request->class_id);
            return redirect()->back()->with('success', __('Class successfully added'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($detailId)
    {
        $student = Auth::user()->student;
        $studyPlan = $this->studyPlanService->getActiveStudyPlanOrNew($student);

        try {
            // Before removing, capture the class so we can check waitlist after
            $detail = $studyPlan->details()->findOrFail($detailId);
            $classId = $detail->class_id;

            $this->studyPlanService->removeClass($studyPlan, $detailId);

            // After removal, check if a slot opened and notify waiting students
            $this->notifyWaitlistIfSlotOpened($classId);

            return redirect()->back()->with('success', __('Class successfully removed'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function submit()
    {
        $student = Auth::user()->student;
        $studyPlan = $this->studyPlanService->getActiveStudyPlanOrNew($student);

        try {
            $this->studyPlanService->submitStudyPlan($studyPlan);
            return redirect()->back()->with('success', __('Study plan successfully submitted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function revise()
    {
        $student = Auth::user()->student;
        $studyPlan = $this->studyPlanService->getActiveStudyPlanOrNew($student);

        if ($studyPlan->status !== 'rejected') {
            return redirect()->back()->with('error', __('Only rejected study plans can be revised'));
        }

        $studyPlan->update(['status' => 'draft', 'notes' => null]);
        return redirect()->back()->with('success', __('Study plan successfully reset to draft. Please edit and resubmit.'));
    }

    /**
     * Toggle the current student's waitlist status for a given class.
     * POST /students/study-plan/waitlist/{classId}
     */
    public function waitlistToggle($classId)
    {
        $student = Auth::user()->student;
        if (!$student) abort(403);

        $class = AcademicClass::findOrFail($classId);

        // Only allow waitlisting if the class is actually full
        if (!$class->isFull()) {
            return redirect()->back()->with('error', __('This class still has available seats.'));
        }

        $existing = ClassWaitlist::where('class_id', $classId)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            // Toggle off — remove from waitlist
            $existing->delete();
            return redirect()->back()->with('success', __('You have been removed from the waitlist.'));
        } else {
            // Toggle on — add to waitlist
            ClassWaitlist::create([
                'class_id'   => $classId,
                'student_id' => $student->id,
            ]);
            return redirect()->back()->with('success', __('You will be notified when a seat becomes available.'));
        }
    }

    /**
     * When a student drops a class, check if a slot just opened.
     * If so, notify all non-yet-notified students on the waitlist.
     */
    private function notifyWaitlistIfSlotOpened(int $classId): void
    {
        $class = AcademicClass::with('course')->find($classId);
        if (!$class) return;

        // A slot has opened if enrolled < capacity
        $enrolled = $class->studyPlanDetails()->count();
        if ($enrolled >= $class->capacity) return;

        // Find all un-notified waitlist entries for this class
        $waiting = ClassWaitlist::where('class_id', $classId)
            ->whereNull('notified_at')
            ->with('student.user')
            ->get();

        foreach ($waiting as $entry) {
            if (!$entry->student?->user) continue;

            Notification::create([
                'user_id' => $entry->student->user->id,
                'type'    => Notification::TYPE_CLASS_AVAILABLE,
                'title'   => __('Seat Available!'),
                'message' => __(
                    'A seat has opened up in :course (:class). Register now before it fills up!',
                    [
                        'course' => $class->course->course_name,
                        'class'  => $class->class_name,
                    ]
                ),
                'data' => [
                    'class_id'   => $class->id,
                    'class_name' => $class->class_name,
                    'course'     => $class->course->course_name,
                ],
            ]);

            // Mark as notified so we don't spam
            $entry->update(['notified_at' => now()]);
        }
    }
}
