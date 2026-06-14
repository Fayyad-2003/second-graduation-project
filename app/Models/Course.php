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
        'description',
        'study_program_id',
        'subject_classification_id',
        'is_elective',
        'has_practical',
        'practical_hours',
        'theory_credits',
        'attendance_weight',
        'midterm_weight',
        'final_exam_weight',
        'practical_attendance_weight',
        'practical_exam_weight',
    ];

    protected $casts = [
        'is_elective' => 'boolean',
        'has_practical' => 'boolean',
        'attendance_weight' => 'integer',
        'midterm_weight' => 'integer',
        'final_exam_weight' => 'integer',
        'practical_attendance_weight' => 'integer',
        'practical_exam_weight' => 'integer',
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

    public function getGradeComponentsAttribute()
    {
        if ($this->has_practical) {
            return [
                'attendance' => $this->attendance_weight ?? 10,
                'midterm' => $this->midterm_weight ?? 20,
                'final_exam' => $this->final_exam_weight ?? 50,
                'practical_attendance' => $this->practical_attendance_weight ?? 5,
                'practical_exam' => $this->practical_exam_weight ?? 20,
            ];
        }

        return [
            'attendance' => $this->attendance_weight ?? 10,
            'midterm' => $this->midterm_weight ?? 30,
            'final_exam' => $this->final_exam_weight ?? 60,
        ];
    }
}
