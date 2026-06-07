<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyRequirement extends Model
{
    protected $table = 'faculty_requirements';
    protected $fillable = ['faculty_id', 'subject_classification_id', 'required_credits'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function classification()
    {
        return $this->belongsTo(SubjectClassification::class, 'subject_classification_id');
    }
}
