<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectClassification extends Model
{
    protected $fillable = ['name', 'slug'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function facultyRequirements()
    {
        return $this->hasMany(FacultyRequirement::class);
    }
}
