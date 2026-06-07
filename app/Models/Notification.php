<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Notification Types
    const TYPE_SCHEDULE_CHANGE = 'schedule_change';
    const TYPE_STUDY_PLAN_APPROVED = 'study_plan_approved';
    const TYPE_STUDY_PLAN_REJECTED = 'study_plan_rejected';
    const TYPE_GRADE_UPDATED = 'grade_updated';
    const TYPE_ATTENDANCE_WARNING = 'attendance_warning';
    const TYPE_CLASS_INFO = 'class_info';
    const TYPE_ACADEMIC_LEVEL = 'academic_level';
    const TYPE_ALL_STUDENTS = 'all_students';
    const TYPE_LECTURER_INFO = 'lecturer_info';
    const TYPE_CLASS_AVAILABLE = 'class_available';
    const TYPE_GPA_WARNING = 'gpa_warning';
    const TYPE_ASSIGNMENT_DEADLINE = 'assignment_deadline';

    /**
     * User who owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Check if notification has been read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Get icon based on type
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SCHEDULE_CHANGE => '📅',
            self::TYPE_STUDY_PLAN_APPROVED => '✅',
            self::TYPE_STUDY_PLAN_REJECTED => '❌',
            self::TYPE_GRADE_UPDATED => '📊',
            self::TYPE_ATTENDANCE_WARNING => '⚠️',
            self::TYPE_CLASS_INFO => '📚',
            self::TYPE_ACADEMIC_LEVEL => '🎓',
            self::TYPE_ALL_STUDENTS => '📢',
            self::TYPE_LECTURER_INFO => '👨‍🏫',
            self::TYPE_CLASS_AVAILABLE => '🔔',
            self::TYPE_GPA_WARNING => '🚨',
            default => '🔔'
        };
    }

    /**
     * Get color based on type
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SCHEDULE_CHANGE => 'blue',
            self::TYPE_STUDY_PLAN_APPROVED => 'emerald',
            self::TYPE_STUDY_PLAN_REJECTED => 'red',
            self::TYPE_GRADE_UPDATED => 'purple',
            self::TYPE_ATTENDANCE_WARNING => 'amber',
            self::TYPE_CLASS_INFO => 'indigo',
            self::TYPE_ACADEMIC_LEVEL => 'cyan',
            self::TYPE_ALL_STUDENTS => 'rose',
            self::TYPE_LECTURER_INFO => 'violet',
            self::TYPE_CLASS_AVAILABLE => 'green',
            self::TYPE_GPA_WARNING => 'red',
            default => 'slate'
        };
    }
}
