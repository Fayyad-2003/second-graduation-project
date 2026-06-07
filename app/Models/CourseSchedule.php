<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSchedule extends Model
{
    protected $table = 'course_schedules';

    protected $fillable = [
        'class_id',
        'day',
        'start_time',
        'end_time',
        'room',
        'room_name',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    /**
     * Check if this schedule conflicts with another
     */
    public function conflictsWith(CourseSchedule $other): bool
    {
        if ($this->day !== $other->day) {
            return false;
        }

        return !($this->end_time <= $other->start_time || $this->start_time >= $other->end_time);
    }

    /**
     * Get all meetings for this schedule
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }
}
