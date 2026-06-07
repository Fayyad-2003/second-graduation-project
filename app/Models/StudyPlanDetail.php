<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPlanDetail extends Model
{
    use HasFactory;

    protected $table = 'study_plan_details';

    protected $fillable = [
        'study_plan_id',
        'class_id',
    ];

    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class);
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
