<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'course_id',
        'lecturer_id',
        'academic_year_id',
        'class_name',
        'capacity',
        'is_closed',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function studyPlanDetails()
    {
        return $this->hasMany(StudyPlanDetail::class, 'class_id');
    }

    public function details()
    {
        return $this->hasMany(StudyPlanDetail::class, 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'class_id');
    }

    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class, 'class_id');
    }

    public function schedule()
    {
        return $this->hasMany(CourseSchedule::class, 'class_id');
    }

    public function schedules()
    {
        return $this->hasMany(CourseSchedule::class, 'class_id');
    }

    public function isFull(): bool
    {
        return $this->studyPlanDetails()->count() >= $this->capacity;
    }

    public function meetings()
    {
        return $this->hasManyThrough(Meeting::class, CourseSchedule::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function chatRequests()
    {
        return $this->hasMany(ChatRequest::class, 'academic_class_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id');
    }

    public function waitlist()
    {
        return $this->hasMany(ClassWaitlist::class, 'class_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'class_id');
    }
}
