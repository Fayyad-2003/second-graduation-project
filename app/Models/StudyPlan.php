<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPlan extends Model
{
    use HasFactory;

    protected $table = 'study_plans';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'status',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function details()
    {
        return $this->hasMany(StudyPlanDetail::class);
    }
}
