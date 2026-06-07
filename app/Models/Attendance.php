<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    const STATUS_PRESENT = 'present';
    const STATUS_SICK = 'sick';
    const STATUS_EXCUSED = 'excused';
    const STATUS_ABSENT = 'absent';

    protected $fillable = [
        'meeting_id',
        'student_id',
        'status',
        'attendance_time',
        'description',
    ];

    protected $casts = [
        'attendance_time' => 'datetime:H:i',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function isPresent(): bool
    {
        return $this->status === self::STATUS_PRESENT;
    }

    public function isValidAbsence(): bool
    {
        return in_array($this->status, [self::STATUS_EXCUSED, self::STATUS_SICK]);
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'emerald',
            self::STATUS_SICK => 'amber',
            self::STATUS_EXCUSED => 'blue',
            self::STATUS_ABSENT => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => __('Present'),
            self::STATUS_SICK => __('Sick'),
            self::STATUS_EXCUSED => __('Excused'),
            self::STATUS_ABSENT => __('Absent'),
            default => '-',
        };
    }
}
