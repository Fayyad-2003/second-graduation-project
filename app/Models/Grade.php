<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $fillable = [
        'student_id',
        'class_id',
        'numeric_grade',
        'letter_grade',
        'attendance',
        'midterm',
        'final_exam',
        'practical_attendance',
        'practical_exam',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function academicClass()
    {
        return $this->class();
    }
}
