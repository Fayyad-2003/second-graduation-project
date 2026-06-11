<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;

    const DIFFICULTY_EASY = 'easy';
    const DIFFICULTY_MEDIUM = 'medium';
    const DIFFICULTY_HARD = 'hard';
    const DIFFICULTY_VERY_HARD = 'very_hard';

    protected $fillable = [
        'course_id',
        'lecturer_id',
        'question_type',
        'question_text',
        'options',
        'correct_answer',
        'points',
        'order',
        'difficulty',
    ];

    protected $casts = [
        'options' => 'json',
        'points' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class);
    }
}
