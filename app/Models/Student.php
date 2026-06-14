<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'student_number',
        'study_program_id',
        'academic_advisor_id',
        'batch',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function academicAdvisor()
    {
        return $this->belongsTo(Lecturer::class, 'academic_advisor_id');
    }

    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }

    public function warnings()
    {
        return $this->hasMany(Warning::class);
    }
}
