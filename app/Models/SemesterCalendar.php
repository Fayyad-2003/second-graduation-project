<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterCalendar extends Model
{
    protected $fillable = [
        'academic_year_id',
        'week_number',
        'date',
        'title',
        'description',
        'type',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
