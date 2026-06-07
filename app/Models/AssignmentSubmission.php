<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = 'assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'file_name',
        'notes',
        'submitted_at',
        'grade',
        'feedback',
        'graded_at',
        'graded_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'grade' => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Check if submission is graded
     */
    public function isGraded(): bool
    {
        return $this->grade !== null;
    }

    /**
     * Check if submitted on time
     */
    public function isOnTime(): bool
    {
        return $this->submitted_at->isBefore($this->assignment->deadline);
    }

    /**
     * Get grade letter (A, B, C, D, E)
     */
    public function getGradeLetterAttribute(): ?string
    {
        if ($this->grade === null) return null;

        return match (true) {
            $this->grade >= 85 => 'A',
            $this->grade >= 70 => 'B',
            $this->grade >= 55 => 'C',
            $this->grade >= 40 => 'D',
            default => 'E',
        };
    }
}
