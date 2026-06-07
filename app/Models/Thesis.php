<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;

    protected $table = 'theses';

    protected $fillable = [
        'student_id',
        'supervisor1_id',
        'supervisor2_id',
        'title',
        'abstract',
        'research_field',
        'status',
        'submission_date',
        'title_approval_date',
        'proposal_seminar_date',
        'result_seminar_date',
        'defense_date',
        'completion_date',
        'final_grade',
        'letter_grade',
        'admin_notes',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'title_approval_date' => 'date',
        'proposal_seminar_date' => 'date',
        'result_seminar_date' => 'date',
        'defense_date' => 'date',
        'completion_date' => 'date',
        'final_grade' => 'decimal:2',
    ];

    // Status constants
    const STATUS_SUBMISSION = 'submission';
    const STATUS_REVIEW = 'review';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_SUPERVISION = 'supervision';
    const STATUS_PROPOSAL_SEMINAR = 'proposal_seminar';
    const STATUS_RESEARCH = 'research';
    const STATUS_RESULT_SEMINAR = 'result_seminar';
    const STATUS_DEFENSE = 'defense';
    const STATUS_REVISION = 'revision';
    const STATUS_COMPLETED = 'completed';

    public static function getStatusList(): array
    {
        return [
            self::STATUS_SUBMISSION => __('Title Submission'),
            self::STATUS_REVIEW => __('Under Review'),
            self::STATUS_REJECTED => __('Title Rejected'),
            self::STATUS_ACCEPTED => __('Title Approved'),
            self::STATUS_SUPERVISION => __('Supervision'),
            self::STATUS_PROPOSAL_SEMINAR => __('Proposal Seminar'),
            self::STATUS_RESEARCH => __('Research'),
            self::STATUS_RESULT_SEMINAR => __('Results Seminar'),
            self::STATUS_DEFENSE => __('Final Exam'),
            self::STATUS_REVISION => __('Revision'),
            self::STATUS_COMPLETED => __('Completed'),
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusList()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_SUBMISSION, self::STATUS_REVIEW => 'amber',
            self::STATUS_REJECTED => 'red',
            self::STATUS_ACCEPTED, self::STATUS_SUPERVISION, self::STATUS_RESEARCH => 'blue',
            self::STATUS_PROPOSAL_SEMINAR, self::STATUS_RESULT_SEMINAR, self::STATUS_DEFENSE => 'purple',
            self::STATUS_REVISION => 'orange',
            self::STATUS_COMPLETED => 'emerald',
            default => 'slate'
        };
    }

    public function getProgressPercentAttribute(): int
    {
        $progressMap = [
            self::STATUS_SUBMISSION => 5,
            self::STATUS_REVIEW => 10,
            self::STATUS_REJECTED => 5,
            self::STATUS_ACCEPTED => 15,
            self::STATUS_SUPERVISION => 30,
            self::STATUS_PROPOSAL_SEMINAR => 45,
            self::STATUS_RESEARCH => 60,
            self::STATUS_RESULT_SEMINAR => 75,
            self::STATUS_DEFENSE => 90,
            self::STATUS_REVISION => 95,
            self::STATUS_COMPLETED => 100,
        ];
        return $progressMap[$this->status] ?? 0;
    }

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor1()
    {
        return $this->belongsTo(Lecturer::class, 'supervisor1_id');
    }

    public function supervisor2()
    {
        return $this->belongsTo(Lecturer::class, 'supervisor2_id');
    }

    public function supervisions()
    {
        return $this->hasMany(ThesisSupervision::class)->orderBy('supervision_date', 'desc');
    }

    public function students()
    {
        return $this->student();
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_REJECTED]);
    }
}
