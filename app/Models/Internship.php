<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $table = 'internships';

    protected $fillable = [
        'student_id',
        'supervisor_id',
        'company_name',
        'company_address',
        'business_field',
        'field_supervisor_name',
        'field_supervisor_title',
        'supervisor_phone',
        'start_date',
        'completion_date',
        'report_title',
        'status',
        'seminar_date',
        'company_grade',
        'supervisor_grade',
        'seminar_grade',
        'final_grade',
        'letter_grade',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
        'seminar_date' => 'date',
        'company_grade' => 'decimal:2',
        'supervisor_grade' => 'decimal:2',
        'seminar_grade' => 'decimal:2',
        'final_grade' => 'decimal:2',
    ];

    const STATUS_SUBMISSION = 'submission';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_INTERNSHIP_COMPLETED = 'internship_completed';
    const STATUS_REPORT_DRAFTING = 'report_drafting';
    const STATUS_SEMINAR = 'seminar';
    const STATUS_REVISION = 'revision';
    const STATUS_COMPLETED = 'completed';

    public static function getStatusList(): array
    {
        return [
            self::STATUS_SUBMISSION => __('Submission'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_REJECTED => __('Rejected'),
            self::STATUS_ONGOING => __('Ongoing'),
            self::STATUS_INTERNSHIP_COMPLETED => __('Internship Completed'),
            self::STATUS_REPORT_DRAFTING => __('Report Drafting'),
            self::STATUS_SEMINAR => __('Seminar'),
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
            self::STATUS_SUBMISSION => 'amber',
            self::STATUS_APPROVED, self::STATUS_ONGOING => 'blue',
            self::STATUS_REJECTED => 'red',
            self::STATUS_INTERNSHIP_COMPLETED, self::STATUS_REPORT_DRAFTING => 'purple',
            self::STATUS_SEMINAR => 'indigo',
            self::STATUS_REVISION => 'orange',
            self::STATUS_COMPLETED => 'emerald',
            default => 'slate'
        };
    }

    public function getProgressPercentAttribute(): int
    {
        $map = [
            self::STATUS_SUBMISSION => 5,
            self::STATUS_APPROVED => 10,
            self::STATUS_REJECTED => 0,
            self::STATUS_ONGOING => 40,
            self::STATUS_INTERNSHIP_COMPLETED => 60,
            self::STATUS_REPORT_DRAFTING => 75,
            self::STATUS_SEMINAR => 90,
            self::STATUS_REVISION => 95,
            self::STATUS_COMPLETED => 100,
        ];
        return $map[$this->status] ?? 0;
    }

    public function getDurationAttribute(): string
    {
        if (!$this->start_date || !$this->completion_date) return '-';
        $weeks = Carbon::parse($this->start_date)->diffInWeeks(Carbon::parse($this->completion_date));

        return rtrim(rtrim(number_format($weeks, 1, '.', ''), '0'), '.') . ' weeks';
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Lecturer::class, 'supervisor_id');
    }

    public function logbooks()
    {
        return $this->hasMany(InternshipLogbook::class)->orderBy('date', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_REJECTED]);
    }
}
