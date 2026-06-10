<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'exam_date',
        'duration',
        'location',
        'max_score',
        'is_active',
    ];

    protected $casts = [
        'exam_date' => 'datetime',
        'is_active' => 'boolean',
        'duration' => 'integer',
        'max_score' => 'decimal:2',
    ];

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function questions()
    {
        return $this->belongsToMany(ExamQuestion::class, 'exam_exam_question')->withPivot('order')->orderBy('pivot_order');
    }

    public function isUpcoming(): bool
    {
        return Carbon::now()->isBefore($this->exam_date);
    }

    public function isPast(): bool
    {
        return Carbon::now()->isAfter($this->exam_date);
    }

    public function getFormattedExamDateAttribute(): string
    {
        return $this->exam_date->translatedFormat('d M Y, H:i');
    }
}
