<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\AcademicClass;
use App\Models\SentNotification;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Send notification to specific users
     */
    public function sendToUsers(array $userIds, string $title, string $message, string $type = 'general', array $data = [])
    {
        $notifications = [];
        $now = now();

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => json_encode($data),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Chunk insert for better performance
        foreach (array_chunk($notifications, 100) as $chunk) {
            DB::table('notifications')->insert($chunk);
        }
    }

    /**
     * Send to students in a specific class
     */
    public function sendToClass(int $classId, string $title, string $message, int $senderId)
    {
        $userIds = User::whereHas('student.studyPlanDetails', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->pluck('id')->toArray();

        $this->sendToUsers($userIds, $title, $message, Notification::TYPE_CLASS_INFO, ['class_id' => $classId]);

        SentNotification::create([
            'sender_id' => $senderId,
            'title' => $title,
            'message' => $message,
            'target_type' => 'subject',
            'target_id' => $classId,
        ]);

        return count($userIds);
    }

    /**
     * Send to students in a specific level (Semester)
     */
    public function sendToLevel(int $level, string $title, string $message, int $senderId)
    {
        $userIds = User::whereHas('student', function ($query) use ($level) {
            $query->where('semester', $level);
        })->pluck('id')->toArray();

        $this->sendToUsers($userIds, $title, $message, Notification::TYPE_ACADEMIC_LEVEL, ['level' => $level]);

        SentNotification::create([
            'sender_id' => $senderId,
            'title' => $title,
            'message' => $message,
            'target_type' => 'level',
            'target_id' => $level,
        ]);

        return count($userIds);
    }

    /**
     * Send to all students
     */
    public function sendToAllStudents(string $title, string $message, int $senderId)
    {
        $userIds = User::where('role', 'student')->pluck('id')->toArray();

        $this->sendToUsers($userIds, $title, $message, Notification::TYPE_ALL_STUDENTS);

        SentNotification::create([
            'sender_id' => $senderId,
            'title' => $title,
            'message' => $message,
            'target_type' => 'all_students',
        ]);

        return count($userIds);
    }

    /**
     * Send to all lecturers
     */
    public function sendToLecturers(string $title, string $message, int $senderId)
    {
        $userIds = User::where('role', 'lecturer')->pluck('id')->toArray();

        $this->sendToUsers($userIds, $title, $message, Notification::TYPE_LECTURER_INFO);

        SentNotification::create([
            'sender_id' => $senderId,
            'title' => $title,
            'message' => $message,
            'target_type' => 'lecturers',
        ]);

        return count($userIds);
    }

    /**
     * Notify students when schedule changes
     */
    public function notifyScheduleChange($class, $schedule, $changes)
    {
        $className = $class->course->course_name . ' (' . $class->class_name . ')';
        $title = "Schedule Update: {$className}";

        $message = "The schedule for {$className} has been updated.\n\nChanges:\n";
        foreach ($changes as $field => $val) {
            $message .= "- " . ucfirst($field) . ": " . $val['old'] . " -> " . $val['new'] . "\n";
        }

        $message .= "\nPlease check your latest study plan.";

        return $this->sendToClass($class->id, $title, $message, auth()->id() ?? 1);
    }

    /**
     * Send GPA warning notification to a single student
     */
    public function sendGpaWarning(Student $student, string $level, float $gpa): void
    {
        $title = $level === 'danger'
            ? __('Academic Probation Warning')
            : __('Low GPA Warning');

        $message = $level === 'danger'
            ? __('Your cumulative GPA is :gpa, which is below :threshold. You are at risk of academic probation. Please consult your academic advisor immediately.', [
                'gpa' => number_format($gpa, 2),
                'threshold' => number_format(config('siakad.gpa_warning.danger', 2.00), 2),
            ])
            : __('Your cumulative GPA is :gpa, which is below :threshold. Please take steps to improve your academic performance.', [
                'gpa' => number_format($gpa, 2),
                'threshold' => number_format(config('siakad.gpa_warning.caution', 2.50), 2),
            ]);

        $this->sendToUsers(
            [$student->user_id],
            $title,
            $message,
            Notification::TYPE_GPA_WARNING,
            ['gpa' => $gpa, 'level' => $level]
        );
    }

    /**
     * Send GPA warning notifications in bulk
     */
    public function sendBulkGpaWarnings(iterable $studentsWithGpa): int
    {
        $count = 0;
        foreach ($studentsWithGpa as $item) {
            $this->sendGpaWarning($item['student'], $item['level'], $item['gpa']);
            $count++;
        }
        return $count;
    }

    /**
     * Get notifications for a user
     */
    public function getForUser(User $user, int $limit = 20)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
