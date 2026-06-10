<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'course_code',
        'course_name',
        'credits',
        'semester',
        'study_program_id',
        'subject_classification_id',
        'is_elective',
    ];

    protected $casts = [
        'is_elective' => 'boolean',
    ];

    public function classification()
    {
        return $this->belongsTo(SubjectClassification::class, 'subject_classification_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function classes()
    {
        return $this->hasMany(AcademicClass::class, 'course_id');
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisites', 'course_id', 'prerequisite_id');
    }

    public function requiredFor()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisites', 'prerequisite_id', 'course_id');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }
}
