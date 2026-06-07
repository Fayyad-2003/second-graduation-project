<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculties';

    protected $fillable = [
        'name',
        'total_credits',
    ];

    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function requirements()
    {
        return $this->hasMany(FacultyRequirement::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
