<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    use HasFactory;

    protected $table = 'study_programs';

    protected $fillable = [
        'faculty_id',
        'name',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'study_program_id');
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class, 'study_program_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'study_program_id');
    }
}
