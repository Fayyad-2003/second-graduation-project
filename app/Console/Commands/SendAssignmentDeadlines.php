<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAssignmentDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-assignment-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send assignment deadline reminders to students 24 hours before due date';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $tomorrow = $now->copy()->addDay();

        // Find assignments due in the next 24 hours
        $assignments = Assignment::where('is_active', true)
            ->where('deadline', '>', $now)
            ->where('deadline', '<=', $tomorrow)
            ->with(['class.studyPlanDetails.studyPlan.student.user'])
            ->get();

        if ($assignments->isEmpty()) {
            $this->info('No assignments due in the next 24 hours.');
            return;
        }

        foreach ($assignments as $assignment) {
            $this->info("Processing assignment: {$assignment->title} (Due: {$assignment->deadline})");

            // Get IDs of students who already submitted
            $submittedStudentIds = $assignment->submissions()->pluck('student_id')->toArray();

            // Get all students enrolled in the class
            $studentsToNotify = [];
            foreach ($assignment->class->studyPlanDetails as $detail) {
                $student = $detail->studyPlan->student;

                // Only notify if student hasn't submitted and is active
                if ($student && !in_array($student->id, $submittedStudentIds)) {
                    $studentsToNotify[] = $student->user_id;
                }
            }

            $studentsToNotify = array_unique($studentsToNotify);

            if (!empty($studentsToNotify)) {
                $title = __('Assignment Deadline Reminder');
                $message = __('Reminder: The assignment ":title" is due in :time.', [
                    'title' => $assignment->title,
                    'time' => $assignment->deadline->diffForHumans(),
                ]);

                // Filter out students who already received a reminder for this assignment
                $alreadyNotifiedUserIds = Notification::where('type', Notification::TYPE_ASSIGNMENT_DEADLINE)
                    ->where('data->assignment_id', $assignment->id)
                    ->pluck('user_id')
                    ->toArray();

                $studentsToNotify = array_diff($studentsToNotify, $alreadyNotifiedUserIds);

                if (empty($studentsToNotify)) {
                    $this->info('All eligible students already notified for assignment: ' . $assignment->title);
                    continue;
                }

                $this->notificationService->sendToUsers(
                    $studentsToNotify,
                    $title,
                    $message,
                    Notification::TYPE_ASSIGNMENT_DEADLINE,
                    ['assignment_id' => $assignment->id]
                );

                $this->info('Sent ' . count($studentsToNotify) . ' notifications for assignment: ' . $assignment->title);
            } else {
                $this->info('No students to notify for assignment: ' . $assignment->title);
            }
        }

        $this->info('Assignment deadline reminders sent successfully.');
    }
}
