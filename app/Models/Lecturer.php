<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $table = 'lecturers';

    protected $fillable = [
        'user_id',
        'lecturer_number',
        'study_program_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function classes()
    {
        return $this->hasMany(AcademicClass::class, 'lecturer_id');
    }

    public function advisedStudents()
    {
        return $this->hasMany(Student::class, 'academic_advisor_id');
    }

    public function supervisor1Theses()
    {
        return $this->hasMany(Thesis::class, 'supervisor1_id');
    }

    public function supervisor2Theses()
    {
        return $this->hasMany(Thesis::class, 'supervisor2_id');
    }

    public function internships()
    {
        return $this->hasMany(Internship::class, 'supervisor_id');
    }

    public function attendance()
    {
        return $this->hasMany(LecturerAttendance::class);
    }
}
