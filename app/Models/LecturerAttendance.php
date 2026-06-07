<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerAttendance extends Model
{
    use HasFactory;

    protected $table = 'lecturer_attendances';

    protected $fillable = [
        'lecturer_id',
        'course_schedule_id',
        'meeting_id',
        'date',
        'entry_time',
        'exit_time',
        'status',
        'description',
        'proof_file',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    const STATUS_PRESENT = 'present';
    const STATUS_EXCUSED = 'excused';
    const STATUS_SICK = 'sick';
    const STATUS_ASSIGNMENT = 'assignment';
    const STATUS_ABSENT = 'absent';

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PRESENT => __('Present'),
            self::STATUS_EXCUSED => __('Excused'),
            self::STATUS_SICK => __('Sick'),
            self::STATUS_ASSIGNMENT => __('Outside Assignment'),
            self::STATUS_ABSENT => __('Absent'),
        ];
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PRESENT => 'emerald',
            self::STATUS_EXCUSED => 'blue',
            self::STATUS_SICK => 'amber',
            self::STATUS_ASSIGNMENT => 'purple',
            self::STATUS_ABSENT => 'red',
            default => 'slate'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? $this->status;
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function courseSchedule()
    {
        return $this->belongsTo(CourseSchedule::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function scopeByLecturer($query, $lecturerId)
    {
        return $query->where('lecturer_id', $lecturerId);
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
