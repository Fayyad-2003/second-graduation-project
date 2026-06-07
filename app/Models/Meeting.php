<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    const STATUS_COMPLETED = 'completed';
    const STATUS_HOLIDAY = 'holiday';
    const STATUS_RESCHEDULED = 'rescheduled';

    protected $fillable = [
        'course_schedule_id',
        'meeting_number',
        'date',
        'topic',
        'status',
        'ai_summary',
        'ai_quiz',
    ];

    protected $casts = [
        'date' => 'date',
        'ai_quiz' => 'array',
    ];

    public function courseSchedule(): BelongsTo
    {
        return $this->belongsTo(CourseSchedule::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope to get only completed meetings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope to filter by class
     */
    public function scopeByClass($query, $classId)
    {
        return $query->whereHas('courseSchedule', fn($q) => $q->where('class_id', $classId));
    }

    /**
     * Get list of students enrolled in this class
     */
    public function getStudentList()
    {
        return Student::whereHas('studyPlans', function ($q) {
            $q->where('status', 'approved')
                ->whereHas('details', fn($q2) => $q2->where('class_id', $this->courseSchedule->class_id));
        })->with('user')->get();
    }

    /**
     * List of materials for this meeting
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }
}
