<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisSupervision extends Model
{
    use HasFactory;

    protected $table = 'thesis_supervisions';

    protected $fillable = [
        'thesis_id',
        'lecturer_id',
        'supervision_date',
        'student_notes',
        'lecturer_notes',
        'document_file',
        'status',
    ];

    protected $casts = [
        'supervision_date' => 'date',
    ];

    const STATUS_WAITING = 'waiting';
    const STATUS_APPROVED = 'approved';
    const STATUS_REVISION = 'revision';

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_WAITING => 'amber',
            self::STATUS_APPROVED => 'emerald',
            self::STATUS_REVISION => 'red',
            default => 'slate'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_WAITING => __('Awaiting Review'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_REVISION => __('Needs Revision'),
            default => $this->status
        };
    }

    // Relationships
    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function theses()
    {
        return $this->belongsTo(Thesis::class, 'thesis_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function lecturers()
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }
}
