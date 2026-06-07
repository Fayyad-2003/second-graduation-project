<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'lecturer_id',
        'academic_class_id',
        'status',
        'message',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
