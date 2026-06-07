<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'student_number', // Changed from 'nim'
        'study_program_id',
        'academic_advisor_id',
        'batch', // Changed from 'angkatan'
        'status',
    ];

    // ============================================
    // BACKWARD COMPATIBILITY ACCESSORS
    // Remove these after full migration is complete
    // ============================================
    
    /**
     * Backward compatibility for 'nim' attribute
     * @deprecated Use student_number instead
     */
    public function getNimAttribute()
    {
        return $this->student_number;
    }
    
    /**
     * Backward compatibility for 'nim' attribute setter
     * @deprecated Use student_number instead
     */
    public function setNimAttribute($value)
    {
        $this->attributes['student_number'] = $value;
    }
    
    /**
     * Backward compatibility for 'angkatan' attribute
     * @deprecated Use batch instead
     */
    public function getAngkatanAttribute()
    {
        return $this->batch;
    }
    
    /**
     * Backward compatibility for 'angkatan' attribute setter
     * @deprecated Use batch instead
     */
    public function setAngkatanAttribute($value)
    {
        $this->attributes['batch'] = $value;
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function academicAdvisor()
    {
        return $this->belongsTo(Lecturer::class, 'academic_advisor_id');
    }

    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByBatch($query, $batch)
    {
        return $query->where('batch', $batch);
    }

    public function scopeByStudyProgram($query, $studyProgramId)
    {
        return $query->where('study_program_id', $studyProgramId);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get student's current semester based on batch year
     */
    public function getCurrentSemester(): int
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        // If before August, use previous academic year
        if ($currentMonth < 8) {
            $currentYear--;
        }
        
        $yearDiff = $currentYear - $this->batch;
        $semester = ($yearDiff * 2) + ($currentMonth >= 8 ? 1 : 2);
        
        return max(1, min($semester, 14)); // Cap at 14 semesters
    }

    /**
     * Get student's cumulative GPA
     */
    public function getCumulativeGpa(): float
    {
        $grades = $this->grades()
            ->whereNotNull('grade_point')
            ->get();
        
        if ($grades->isEmpty()) {
            return 0.0;
        }
        
        $totalPoints = 0;
        $totalCredits = 0;
        
        foreach ($grades as $grade) {
            $credits = $grade->class->course->credits ?? 0;
            $totalPoints += $grade->grade_point * $credits;
            $totalCredits += $credits;
        }
        
        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }

    /**
     * Get student's semester GPA for specific academic year
     */
    public function getSemesterGpa($academicYearId): float
    {
        $grades = $this->grades()
            ->whereHas('class', function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->whereNotNull('grade_point')
            ->get();
        
        if ($grades->isEmpty()) {
            return 0.0;
        }
        
        $totalPoints = 0;
        $totalCredits = 0;
        
        foreach ($grades as $grade) {
            $credits = $grade->class->course->credits ?? 0;
            $totalPoints += $grade->grade_point * $credits;
            $totalCredits += $credits;
        }
        
        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
    }
}
