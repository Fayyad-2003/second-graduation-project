<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'year',
        'semester',
        'is_active',
        'start_date',
        'completion_date',
        'study_plan_start_date',
        'study_plan_end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'completion_date' => 'date',
        'study_plan_start_date' => 'date',
        'study_plan_end_date' => 'date',
    ];

    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function classes()
    {
        return $this->hasMany(AcademicClass::class, 'academic_year_id');
    }

    public static function active()
    {
        return static::where('is_active', true)->first();
    }

    public function isSemesterActive(): bool
    {
        if (!$this->start_date || !$this->completion_date) {
            return $this->is_active;
        }
        
        $today = now()->toDateString();
        return $today >= $this->start_date->toDateString() 
            && $today <= $this->completion_date->toDateString();
    }

    public function isStudyPlanPeriod(): bool
    {
        if (!$this->study_plan_start_date || !$this->study_plan_end_date) {
            return $this->is_active;
        }
        
        $today = now()->toDateString();
        return $today >= $this->study_plan_start_date->toDateString() 
            && $today <= $this->study_plan_end_date->toDateString();
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->year} {$this->semester}";
    }
}
