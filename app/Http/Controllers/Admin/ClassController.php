<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Lecturer;
use App\Models\CourseSchedule;
use App\Models\StudyPlanDetail;
use App\Services\AcademicService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassController extends Controller
{
    protected $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function index(Request $request)
    {
        $query = AcademicClass::with(['course', 'lecturer.user', 'lecturer.studyProgram', 'courseSchedules']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('class_name', 'like', "%{$search}%")
                    ->orWhereHas('course', fn($q2) => $q2->where('course_name', 'like', "%{$search}%")->orWhere('course_code', 'like', "%{$search}%"))
                    ->orWhereHas('lecturer.user', fn($q3) => $q3->where('name', 'like', "%{$search}%"));
            });
        }

        // Faculty scoping
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $query->whereHas('lecturer.studyProgram', fn($q) => $q->where('faculty_id', $facultyId));
        }

        // Sorting
        $sortColumn = $request->get('sort', 'class_name');
        $sortDirection = $request->get('order', 'asc');

        if ($sortColumn === 'course') {
            $query->join('courses', 'classes.course_id', '=', 'courses.id')
                ->select('classes.*')
                ->orderBy('courses.course_name', $sortDirection);
        } elseif ($sortColumn === 'lecturer') {
            $query->join('lecturers', 'classes.lecturer_id', '=', 'lecturers.id')
                ->join('users', 'lecturers.user_id', '=', 'users.id')
                ->select('classes.*')
                ->orderBy('users.name', $sortDirection);
        } elseif (in_array($sortColumn, ['class_name', 'capacity'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('class_name', 'asc');
        }

        $classes = $query->paginate(config('system.pagination', 15))->withQueryString();
        $courses = $this->academicService->getAllCourses();

        // Scope lecturers list for dropdown
        $lecturersQuery = Lecturer::with('user');
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $lecturersQuery->whereHas('studyProgram', fn($q) => $q->where('faculty_id', $request->get('faculty_scope')));
        }
        $lecturers = $lecturersQuery->get();

        return view('admin.class.index', compact('classes', 'courses', 'lecturers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'class_name' => 'required|string',
            'capacity' => 'nullable|integer|min:1',
            'day' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'room'        => 'nullable|string|max:50',
        ]);

        $class = $this->academicService->createClass($validated);

        // Create schedule if provided
        if (!empty($validated['day']) && !empty($validated['start_time']) && !empty($validated['end_time'])) {
            CourseSchedule::create([
                'class_id' => $class->id,
                'day' => $validated['day'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'room' => $validated['room'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', __('Class successfully added.'));
    }

    public function update(Request $request, AcademicClass $class)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lecturer_id' => 'required|exists:lecturers,id',
            'class_name' => 'required|string',
            'capacity' => 'nullable|integer|min:1',
            'day' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'room'        => 'nullable|string|max:50',
        ]);

        $class->update([
            'course_id' => $validated['course_id'],
            'lecturer_id' => $validated['lecturer_id'],
            'class_name' => $validated['class_name'],
            'capacity' => $validated['capacity'],
        ]);

        // Update or create schedule with notification
        if (!empty($validated['day']) && !empty($validated['start_time']) && !empty($validated['end_time'])) {
            $oldSchedule = $class->courseSchedules()->first();

            // Track changes
            $changes = [];
            if ($oldSchedule) {
                if ($oldSchedule->day !== $validated['day']) {
                    $changes['day'] = ['old' => $oldSchedule->day, 'new' => $validated['day']];
                }
                $oldTime = Carbon::parse($oldSchedule->start_time)->format('H:i') . '-' . Carbon::parse($oldSchedule->end_time)->format('H:i');
                $newTime = $validated['start_time'] . '-' . $validated['end_time'];
                if ($oldTime !== $newTime) {
                    $changes['time'] = ['old' => $oldTime, 'new' => $newTime];
                }
                if (($oldSchedule->room ?? '') !== ($validated['room'] ?? '')) {
                    $changes['room'] = ['old' => $oldSchedule->room ?? '-', 'new' => $validated['room'] ?? '-'];
                }
            }

            $schedule = $class->courseSchedules()->updateOrCreate(
                ['class_id' => $class->id],
                [
                    'day' => $validated['day'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'room' => $validated['room'] ?? null,
                ]
            );

            // Send notification if there are changes
            if (!empty($changes)) {
                $class->load('course');
                $notificationService = app(NotificationService::class);
                $count = $notificationService->notifyScheduleChange($class, $schedule, $changes);

                if ($count > 0) {
                    return redirect()->back()->with('success', __('Class successfully updated.') . " Notification sent to {$count} students.");
                }
            }
        }

        return redirect()->back()->with('success', __('Class successfully updated.'));
    }

    public function destroy(AcademicClass $class)
    {
        $class->delete();
        return redirect()->back()->with('success', __('Class successfully deleted.'));
    }
}
