<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassWaitlist extends Model
{
    protected $table = 'class_waitlists';

    protected $fillable = [
        'class_id',
        'student_id',
        'notified_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Whether this waitlist entry has already been notified
     */
    public function hasBeenNotified(): bool
    {
        return !is_null($this->notified_at);
    }
}
